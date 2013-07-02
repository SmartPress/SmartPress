<!DOCTYPE html>
<html>
<head>
	<title>SmartPress</title>
	<?php echo $this->javascript('jquery'); ?>
</head>
<body>
	<?php echo $this->yield(); ?>
	<?php echo $this->printSqlLog(); ?>
</body>
</html>
