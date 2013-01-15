<?php
require_once('defines.php');
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

define('INSTALL_STATUS', 0);
define('SUCCESS_STATUS', 1);
define('ERROR_STATUS', 2);
define('MAX_STATUS', 2);

define('WARNING_ERROR_LEVEL', 0);
define('FATAL_ERROR_LEVEL', 1);
define('INFO_ERROR_LEVEL', 2);

$titles = [
	'Install SmartPress',
	'Successfully Installed SmartPress',
	'Error Installing SmartPress'
];
$errorLevels = [
	'warning',
	'fatal',
	'info'
];
$errors = [];
$fatalError = false;

$step = (isset($_REQUEST['status']) ||  $_REQUEST['status'] > MAX_STATUS) ? $_REQUEST['status'] : 0;

function pushError($error, $fix, $level = WARNING_ERROR_LEVEL) {
	global $errors, $errorLevels;

	if ($level == FATAL_ERROR_LEVEL)
		$fatalError = true;

	$errors[]	= [
		'error' => $error,
		'fix'	=> $fix,
		'level'	=> $errorLevels[$level]
	]
}

if ($step == INSTALL_STATUS) {
	try {
		$app	= App::instance();
		$app->bootstrap();

		$connection = \ActiveRecord\Connection::instance();
	} catch (\ActiveRecord\Exceptions\DatabaseException $e) {
		pushError(
			'No database connection available.',
			'Check database connection settings in initializer "' . SPEEDY_ENV . '" ' .
			'or change environment in defines.php',
			FATAL_ERROR_LEVEL
		);
	}
}
?>
<html>
<head>
	<title>Install SmartPress</title>
	<style>
	.container {
		margin: 10px auto 10px auto;
	}
	</style>
</head>
<body>
	<div class="container">
		<h1><?php echo $title[$step]; ?></h1>

		<?php if ($step == INSTALL_STATUS): ?>
		<form action="/install.php" method="POST">
			<fieldset>
				<legend>Data Base Settings</legend>

				<div class="grid">
					<?php //if (count($errors) > 0): ?>
					<table>
						<tr>
							<th>Setting</th>
							<th>Error</th>
						</tr>
						<tr>
							<td>
							</td>
						</tr>
						<?php /*foreach ($errors as $error): ?>
							<tr class="error <?php echo $error['level']; ?>">
								<td class="error"><?php echo $error['error']; ?></td>
								<td class="fix"><?php echo $error['fix']; ?></td>
							</tr>
						<?php endforeach;*/ ?>
					</table>
					<?php //endif; ?>
				</div>

				<?php if (!$fatalError): ?>
					<div class="actions">
						<input type="submit" value="Install" />
					</div>
				<?php endif; ?>
			</fieldset>
		</form>
		<?php endif; ?>
	</div>
</body>
</html>