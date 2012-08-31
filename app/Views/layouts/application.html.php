<!DOCTYPE html>
<html>
<head>
	<title>Cms</title>
	<?php $this->javascript('jquery'); ?>
</head>
<body>
	<?php $this->yield(); ?>
	<?php $this->printSqlLog(); ?>
</body>
</html>