<?php
require_once('defines.php');
$composerAutoload = dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

if (file_exists($composerAutoload))
	include_once $composerAutoload;

define('PATH_TO_APP', CONFIG_PATH . DS . 'App.php');

define('PREINSTALL_STATUS', 0);
define('INSTALL_STATUS', 1);
define('SUCCESS_STATUS', 2);
define('ERROR_STATUS', 3);
define('FAILED_CREATE_USER_STATUS', 4);
define('RECREATE_USER_STATUS', 5);
define('MIGRATION_FAILED_STATUS', 6);
define('MIGRATION_RETRY_STATUS', 7);
define('MAX_STATUS', 7);

define('WARNING_COND_LEVEL', 0);
define('FATAL_COND_LEVEL', 1);
define('INFO_COND_LEVEL', 2);
define('SUCCESS_COND_LEVEL', 3);

define('LIBRARY_FIX_MSG', 'Run composer install to install all missing libraries');
define('PERMISSIONS_FIX_MSG', 'Ensure file permissions are correct');

define('DEFAULT_THEME_NAME', 'Greenish');

use SmartPress\Models\Permissions;

$titles = [
	'Install SmartPress',				// Preinstall status
	'',									// Install
	'Successfully Installed SmartPress',// Success
	'Error Installing SmartPress',		// Error installing
	'Failed Creating Admin User',		// Failed to create user
	'',									// Recreate user
	'Failed to Create Database Tables',	// Migration failed
	''									// Migration retry
];
$errorLevels = [
	'warning',
	'error',
	'info',
	'success'
];
$conditions = [];
$fatalError = false;

$status = (isset($_REQUEST['status']) && $_REQUEST['status'] <= MAX_STATUS) ? 
	intval($_REQUEST['status']) : PREINSTALL_STATUS;


function pushCondition($message, $level = WARNING_COND_LEVEL, $fix = '') {
	global $conditions, $errorLevels;

	if ($level == FATAL_COND_LEVEL)
		$fatalError = true;

	$conditions[]	= [
		'message' => $message,
		'fix'	=> $fix,
		'level'	=> $errorLevels[$level]
	];
}

function fdump($var) {
	echo "<pre>\n";
	var_dump($var);
	echo "</pre>\n";
}

function includeApp() {
	if (!include_once(PATH_TO_APP)) {
		trigger_error("Could not find App class for current application, please check that app file is in CONFIG_PATH/App.php");
	}
}

function migrate() {
	$migrations	= ROOT . DS . 'db' . DS . 'migrate' . DS . '*.php';
		
	foreach (glob($migrations) as $migration) {
		require_once $migration;
			
		$info	= pathinfo($migration);
		$file	= $info['filename'];
		$fileArr= explode('_', $file);
		$version= array_shift($fileArr);
		$class	= \Speedy\Utility\Inflector::camelize(implode('_', $fileArr));
			
			
		$obj	= new $class(\ActiveRecord\Connection::instance());
		if ($obj->migrated()) {
			continue;
			//return false;
		}
			
		\Speedy\Logger::info("===================================================");
		\Speedy\Logger::info("Starting Migration for $class");
		\Speedy\Logger::info("===================================================");
		\Speedy\Logger::info();
			
		$obj->runUp();
		$log	= $obj->log();
		foreach ($log as $l) {
			\Speedy\Logger::info($l);
		}
	}
		
	\Speedy\Logger::info();
	\Speedy\Logger::info("============= Successfully Completed ==============");

	return true;
}

$user;
function createUser($data) {
	global $user;

	$data['permissions'] = 255;

	$user = new \SmartPress\Models\User($data);
	if (!$user->save()) {
		pushCondition(
			'Failed to create user',
			FATAL_COND_LEVEL
			);
		return false;
	}

	return true;
}

function addDefaultConfigs() {
	$defaults = [
		['name'	=> 'theme', 'value' => \SmartPress\Models\Theme::DIR . DS . DEFAULT_THEME_NAME],
		['name'	=> 'title/default', 'value'	=> 'My Awesome SmartPress Blog'],
		['name'	=> 'home/type', 'value'	=> \SmartPress\Models\Post::BlogRollHomeType]
	];
}

function recreateUserAction() {
	global $status;

	includeApp();
	$app	= App::instance();

	if (!createUser($_REQUEST['admin_user'])) {
		$status = FAILED_CREATE_USER_STATUS;
		return false;
	}

	$status = SUCCESS_STATUS;
	return true;
}

function seedDb() {
	try {
		$connection = \ActiveRecord\Connection::instance();
		$connection->transaction();
		$sql	= "CREATE TABLE schema_migrations (" .
					'`version` varchar(255) NOT NULL);';
		$connection->query($sql);
				
		$sql	= "CREATE UNIQUE INDEX `unique_schema_migrations` ON `schema_migrations` (`version`)";
		$connection->query($sql);
				
		$connection->commit();
		\Speedy\Logger::info("Database seeded");
	} catch (\Exception $e) {
		$connection->rollback();
		\Speedy\Logger::info();
		\Speedy\Logger::info($e);
		pushCondition($e->getMessage(), FATAL_COND_LEVEL);
		return false;
	}

	return true;
}

function installAction() {
	global $status;

	includeApp();
	$app	= App::instance();
	
	if (!seedDb()) {
		$status = MIGRATION_FAILED_STATUS;
		pushCondition('Failed to seed database', FATAL_COND_LEVEL);
	}

	if (!migrate()) {
		$status = MIGRATION_FAILED_STATUS;
		pushCondition(
			'Failed to migrate',
			FATAL_COND_LEVEL
			);
		return false;
	}

	if (!createUser($_REQUEST['admin_user'])) {
		$status = FAILED_CREATE_USER_STATUS;
		return false;
	}

	$status = SUCCESS_STATUS;
	return true;
}

function preinstallAction() {
	$requiredLibs = [
		'ActiveRecord' => '\\ActiveRecord\\Connection',
		'Speedy Sprockets' => '\\Speedy\\Sprocket\\Sprocket',
		'PLinq'				=> '\\PLinq\\PLinq',
		'Speedy Framework'	=> "\\Speedy\\App",
	];
	$allLibs = true;

	foreach ($requiredLibs as $name => $class) {
		if (!class_exists($class)) {
			$allLibs = false;
			pushCondition(
				"Missing $name library",
				FATAL_COND_LEVEL,
				LIBRARY_FIX_MSG
			);
		} else {
			pushCondition(
				"Found library $name",
				SUCCESS_COND_LEVEL
				);
		}
	}

	$alreadyInstalled = false;
	if ($allLibs) {
		includeApp();

		try {
			$app	= App::instance();
			//$app->bootstrap();

			$connection = \ActiveRecord\Connection::instance();
			pushCondition(
				'Database connection found for initializer "' . SPEEDY_ENV . '"',
				SUCCESS_COND_LEVEL
			);
		} catch (\ActiveRecord\Exceptions\DatabaseException $e) {
			pushCondition(
				'No database connection available.',
				FATAL_COND_LEVEL,
				'Check database connection settings in initializer "' . SPEEDY_ENV . '" ' .
				'or change environment in defines.php'
			);
		}

		if ($connection) {
			try {
				$table = \SmartPress\Models\Post::table();
				$alreadyInstalled = true;
			} catch (\ActiveRecord\Exceptions\DatabaseException $e) {
				$alreadyInstalled = false;
			}
		}

		if ($alreadyInstalled) {
			pushCondition(
				'SmartPress may be already installed',
				FATAL_COND_LEVEL,
				'Please ensure you want to perform this action'
				);
		}
	} else {
		pushCondition(
			'Unable to test database, missing required libraries',
			FATAL_COND_LEVEL,
			LIBRARY_FIX_MSG
			);
	}
	

	$tempPerms = fileperms(TMP_PATH);
	$desired 	= 0x4000 + 0x0100 + 0x0080 + 0x0040;	// Directory and owner
	$desired	+= 0x0020 + 0x0010 + 0x0008;			// Group
	$desired 	+= 0x0004 + 0x0002 + 0x0001;			// World
	$permsCheck = $tempPerms & $desired;
	
	if ($permsCheck != $desired) {
		pushCondition(
			'Tmp directory does not have full permissions, this may cause errors.',
			INFO_COND_LEVEL,
			PERMISSIONS_FIX_MSG
			);
	} else {
		pushCondition(
			'Tmp directory has full permissions',
			SUCCESS_COND_LEVEL
			);
	}

	if (file_exists(MODULES_PATH)) {
		$modulesPerms = fileperms(MODULES_PATH);
		$permsCheck = $modulesPerms & $desired;

		if ($permsCheck != $desired) {
			pushCondition(
				'Modules directory does not have full permissions, this may cause errors.',
				INFO_COND_LEVEL,
				PERMISSIONS_FIX_MSG
				);
		} else {
			pushCondition(
				'Modules directory has full permissions',
				SUCCESS_COND_LEVEL
				);
		}
	}
}

function migrationRetryAction() {
	global $status;
	includeApp();

	$app	= App::instance();

	if (!$migrate()) {
		$status = MIGRATION_FAILED_STATUS;
		pushCondition(
			'Failed to migrate',
			FATAL_COND_LEVEL
			);
		return false;
	}

	if (!createUser($_REQUEST['admin_user'])) {
		$status = FAILED_CREATE_USER_STATUS;
		return false;
	}

	return true;
}

if ($status === PREINSTALL_STATUS) {
	preinstallAction();
} elseif ($status === INSTALL_STATUS) {
	installAction();
} elseif ($status === RECREATE_USER_STATUS) {
	recreateUserAction();
} elseif ($status === MIGRATION_RETRY_STATUS) {
	migrationRetryAction();
}

?>
<html>
<head>
	<title>Install SmartPress</title>
	<script src="http://code.jquery.com/jquery-1.9.0.min.js" type="text/javascript"></script>
	<script src="/application.js" type="text/javascript"></script>
	<script src="/vendor/bootstrap/js/bootstrap.js" type="text/javascript"></script>
	
	<link rel="stylesheet" type="text/css" href="/vendor/bootstrap/css/bootstrap.css" />
	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
	<link rel="stylesheet" type="text/css" href="/vendor/bootstrap/css/bootstrap-responsive.css" />
	<link rel="stylesheet" type="text/css" href="application.css" />
</head>
<body>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3"></div>
			<div class="span6">
				<h1><?php echo $titles[$status]; ?></h1>

				<?php if ($status === PREINSTALL_STATUS): ?>
				<form action="/install.php" method="POST" class="form-horizontal">
					<fieldset>
						<legend>Conditions</legend>

						<div>
							<table class="table table-striped">
								<tr>
									<th>Condition</th>
									<th>Message</th>
								</tr>
								<?php foreach ($conditions as $condition): ?>
									<tr class="<?php echo $condition['level']; ?>">
										<td class="error"><?php echo $condition['message']; ?></td>
										<td class="fix"><?php echo $condition['fix']; ?></td>
									</tr>
								<?php endforeach; ?>
							</table>
						</div>
					</fieldset>

					<?php if (!$fatalError): ?>
					<fieldset>
						<legend>Admin User</legend>

						<div class="control-group">
							<label class="control-label" for="admin_user_email">Email</label>
							<div class="controls">
								<input type="text" name="admin_user[email]" id="admin_user_email" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Password</label>
							<div class="controls">
								<input type="password" name="admin_user[password]" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Password Confirmation</label>
							<div class="controls">
								<input type="password" name="admin_user[password_confirm]" />
							</div>
						</div>
					</fieldset>

						
					<div class="form-actions">
						<input type="hidden" name="status" value="<?php echo INSTALL_STATUS; ?>" />
						<input type="submit" value="Install" class="btn btn-primary"  />
					</div>
					<?php endif; ?>
				</form>
				<?php elseif ($status === FAILED_CREATE_USER_STATUS): ?>
				<form action="/install.php" method="POST" class="form-horizontal">
					<fieldset>
						<legend>Admin User</legend>

						<?php if ($user->errors && $user->errors->count()): ?>
							<div id="error_explanation">
								<p>Errors prohibited this user from being saved:</p>
							</div>
							
							<ul>
								<?php $user->errors->each(function($error) { ?>
									<li><?php echo $error; ?></li>
								<?php }); ?>
							</ul>
						<?php endif; ?>

						<div class="control-group">
							<label class="control-label" for="admin_user_email">Email</label>
							<div class="controls">
								<input type="text" name="admin_user[email]" id="admin_user_email" value="<?php echo $user->email; ?>" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Password</label>
							<div class="controls">
								<input type="password" name="admin_user[password]" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Password Confirmation</label>
							<div class="controls">
								<input type="password" name="admin_user[password_confirm]" />
							</div>
						</div>
					</fieldset>

					<div class="form-actions">
						<input type="hidden" name="status" value="<?php echo RECREATE_USER_STATUS; ?>" />
						<input type="submit" value="Create" class="btn btn-primary"  />
					</div>
				</form>
				<?php elseif ($status === SUCCESS_STATUS): ?>
					<p>Successfully installed SmartPress. Login via <a href="/admin/signin">sign in</a>.</p>
				<?php elseif ($status === MIGRATION_FAILED_STATUS): ?>
					<p>Failed creating database.</p>
					<?php fdump($conditions); ?>

					<form class="form-horizontal" action="/install.php" method="POST" class="form-horizontal">
						<fieldset>
							<legend>Admin User</legend>

							<?php if (isset($user) && $user->errors && $user->errors->count()): ?>
								<div id="error_explanation">
									<p>Errors prohibited this user from being saved:</p>
								</div>
								
								<ul>
									<?php $user->errors->each(function($error) { ?>
										<li><?php echo $error; ?></li>
									<?php }); ?>
								</ul>
							<?php endif; ?>

							<div class="control-group">
								<label class="control-label" for="admin_user_email">Email</label>
								<div class="controls">
									<input type="text" name="admin_user[email]" id="admin_user_email" value="<?php echo (isset($_REQUEST['admin_user']['email'])) ? $_REQUEST['admin_user']['email'] : ''; ?>" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">Password</label>
								<div class="controls">
									<input type="password" name="admin_user[password]" />
								</div>
							</div>

							<div class="control-group">
								<label class="control-label">Password Confirmation</label>
								<div class="controls">
									<input type="password" name="admin_user[password_confirm]" />
								</div>
							</div>
						</fieldset>

						<div class="form-actions">
							<input type="hidden" name="status" value="<?php echo MIGRATION_RETRY_STATUS; ?>" />
							<input type="submit" value="Retry" class="btn btn-primary"  />
						</div>
					</form>
				<?php endif; ?>
			</div>
			<div class="span3"></div>
		</div>
	</div>
</body>
</html>
