<!DOCTYPE html>
<html>
<head>
	<title>Cms</title>
	<?php $this->javascript('jquery'); ?>
	<?php $this->javascript('js/Object.js'); ?>
</head>
<body>
	<?php $this->yield(); ?>
	<?php $this->printSqlLog(); ?>
</body>
</html>