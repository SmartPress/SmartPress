<!DOCTYPE html>
<html>
<head>
	<title>SmartPress</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php $this->javascript('jquery'); ?>
	<?php $this->javascript('/admin.js?debug=1'); ?>
	<?php $this->javascript('/vendor/bootstrap/js/bootstrap.js'); ?>
	<?php $this->javascript('/vendor/jquery-ui/js/jquery-ui-1.8.23.custom.min.js'); ?>
	
	<?php $this->stylesheet('/vendor/bootstrap/css/bootstrap.css'); ?>
	<style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      .sidebar-nav {
        padding: 9px 0;
      }
    </style>
	<?php $this->stylesheet('/vendor/bootstrap/css/bootstrap-responsive.css'); ?>
	<?php $this->stylesheet('/vendor/jquery-ui/css/jquery-ui-1.8.23.custom.css'); ?>
	<?php $this->stylesheet('/application.css?debug=1'); ?>
	<?php $this->stylesheet('/admin.css?debug=1'); ?>
</head>
<body class="<?php echo implode(' ', $this->param('controller')) . ' ' . $this->param('action'); ?>">
	<?php $this->render("admin/shared/top-nav"); ?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<?php if ($this->hasContentFor('left-sidebar')): ?>
					<?php $this->yield("left-sidebar"); ?>
				<?php else: ?>
					<?php $this->render("admin/shared/left-sidebar"); ?>
				<?php endif; ?>
			</div>
			<div class="span9">
				<?php $this->yield(); ?>
			</div>
		</div>
	</div>
	<?php $this->printSqlLog(); ?>
</body>
</html>