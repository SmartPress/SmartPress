<?php
require_once('defines.php');
include_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

if (!include(CONFIG_PATH . DS . 'App.php')) {
	trigger_error("Could not find App class for current application, please check that app file is in CONFIG_PATH/App.php");
}

define('PREINSTALL_STATUS', 0);
define('INSTALL_STATUS', 1);
define('SUCCESS_STATUS', 2);
define('ERROR_STATUS', 3);
define('MAX_STATUS', 3);

define('WARNING_COND_LEVEL', 0);
define('FATAL_COND_LEVEL', 1);
define('INFO_COND_LEVEL', 2);
define('SUCCESS_COND_LEVEL', 3);

define('LIBRARY_FIX_MSG', 'Run composer install to install all missing libraries');
define('PERMISSIONS_FIX_MSG', 'Ensure file permissions are correct');

$titles = [
	'Install SmartPress',
	'Successfully Installed SmartPress',
	'Error Installing SmartPress'
];
$errorLevels = [
	'warning',
	'error',
	'info',
	'success'
];
$conditions = [];
$fatalError = false;

$step = (isset($_REQUEST['status']) && $_REQUEST['status'] > MAX_STATUS) ? $_REQUEST['status'] : PREINSTALL_STATUS;

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

if ($step === PREINSTALL_STATUS) {
	$requiredLibs = [
		'ActiveRecord' => '\\ActiveRecord\\Connection',
		'Speedy Sprockets' => '\\Speedy\\Sprocket\\Sprocket',
		'PLinq'				=> '\\YaLinqo\\Enumerable',
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
				<h1><?php echo $titles[$step]; ?></h1>

				<?php if ($step == PREINSTALL_STATUS): ?>
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
								<input type="text" name="admin_user[password]" />
							</div>
						</div>

						<div class="control-group">
							<label class="control-label">Password Confirmation</label>
							<div class="controls">
								<input type="text" name="admin_user[password_confirmation]" />
							</div>
						</div>
					</fieldset>

						<?php if (!$fatalError): ?>
							<div class="form-actions">
								<input type="hidden" name="status" value="<?php echo INSTALL_STATUS; ?>" />
								<input type="submit" value="Install" class="btn btn-primary"  />
							</div>
						<?php endif; ?>
				</form>
				<?php endif; ?>
			</div>
			<div class="span3"></div>
		</div>
	</div>
</body>
</html>
