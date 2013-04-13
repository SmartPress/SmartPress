<!DOCTYPE html>
<html>
<head>
	<title>SmartPress</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<?php echo $this->javascript('jquery'); ?>
	<?php echo $this->javascript('/admin.js?debug=1'); ?>
	<?php echo $this->javascript('/vendor/bootstrap/js/bootstrap.js'); ?>
	<?php echo $this->javascript('/vendor/jquery-ui/js/jquery-ui-1.8.23.custom.min.js'); ?>
	<?php //$this->javascript('http://code.jquery.com/ui/1.9.2/jquery-ui.js'); ?>
	<?php echo $this->javascript('/vendor/jquery-plugins/imgselector/scripts/jquery.imgareaselect.pack.js')?>
	
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
	<?php echo $this->stylesheet('/vendor/jquery-ui/css/jquery-ui-1.8.23.custom.css'); ?>
	<?php echo $this->stylesheet('/vendor/jquery-plugins/imgselector/css/imgareaselect-default.css'); ?>
	<?php echo $this->stylesheet('/application.css?debug=1'); ?>
	<?php echo $this->stylesheet('/admin.css?debug=1'); ?>
	
	<script type="text/javascript">
		var CKEDITOR_BASEPATH = '/js/ckeditor/';
	</script> 
	
	<?php echo $this->javascript('/js/ckeditor/ckeditor.js'); ?>
	<?php echo $this->javascript('/js/ckeditor/adapters/jquery.js'); ?>
	
	<script type="text/javascript">
		$(document).ready(function() {
			$("tr.highlight").vectorEffect();

			$(".ckeditor").ckeditor(function() {}, {
					filebrowserUploadUrl	: "/admin/uploads.jsonp"
				});
		});
	</script>
</head>
<body class="<?php echo implode(' ', $this->param('controller')) . ' ' . $this->param('action'); ?>">
	<?php echo $this->render("admin/shared/top-nav"); ?>
	
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3">
				<?php if ($this->hasContentFor('left-sidebar')): ?>
					<?php echo $this->yield("left-sidebar"); ?>
				<?php else: ?>
					<?php echo $this->render("admin/shared/left-sidebar"); ?>
				<?php endif; ?>
			</div>
			<div class="span9">
				<?php echo $this->render('admin/shared/flash'); ?>
				<?php echo $this->yield(); ?>
			</div>
		</div>
	</div>
	<?php $this->printSqlLog(); ?>
</body>
</html>
