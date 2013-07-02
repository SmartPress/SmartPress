<!DOCTYPE html>
<html>
<head>
	<title>SmartPress</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php echo $this->javascript('jquery'); ?>
	<?php echo $this->javascript('/application.js?debug=1'); ?>
	<?php echo $this->javascript('/vendor/bootstrap/js/bootstrap.js'); ?>
	
	<?php echo $this->stylesheet('/vendor/bootstrap/css/bootstrap.css'); ?>
	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
	<?php echo $this->stylesheet('/vendor/bootstrap/css/bootstrap-responsive.css'); ?>
	<?php echo $this->stylesheet('/application.css?debug=1'); ?>
</head>
<body>
	<?php //$this->render("admin/shared/_top-nav"); ?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3"></div>
			<div class="span6">
				<?php echo $this->yield(); ?>
			</div>
			<div class="span3"></div>
		</div>
	</div>
	<?php if (SPEEDY_ENV != 'production') $this->printSqlLog(); ?>
</body>
</html>
