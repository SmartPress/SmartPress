<!DOCTYPE html>
<html>
<head>
	<title>SmartPress</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php $this->javascript('jquery'); ?>
	<?php $this->javascript('/application.js?debug=1'); ?>
	<?php $this->javascript('/js/Object.js'); ?>
	<?php $this->javascript('/bootstrap/js/bootstrap.js'); ?>
	
	<?php $this->stylesheet('/bootstrap/css/bootstrap.css'); ?>
	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
	<?php $this->stylesheet('/bootstrap/css/bootstrap-responsive.css'); ?>
	<?php $this->stylesheet('/application.css?debug=1'); ?>
</head>
<body>
	<?php $this->render("admin/shared/_top-nav"); ?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<?php $this->render("admin/shared/_left-sidebar"); ?>
				<?php $this->yield("left-sidebar"); ?>
			</div>
			<div class="span9">
				<?php $this->yield(); ?>
			</div>
		</div>
	</div>
	<?php $this->printSqlLog(); ?>
</body>
</html>