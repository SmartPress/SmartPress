<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb">
<head>
	<?php //echo $this->Html->charset(); ?>
	<title>
		<?php echo $this->title(); ?>
	</title>
	<meta name="google-site-verification" content="chfl-c50h8yHCcRVlQD_VmgEbWXhQtGpCO-b0kXlyl4" />
	<link rel="icon" type="image/png" href="/favicon.png" />
	<link rel="shortcut icon" type="image/png" href="/favicon.png" />
	<link rel="stylesheet" href="/js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/resources/css/custom-theme/jquery-ui-1.8.9.custom.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="/resources/css/style.css" type="text/css" media="screen" />
	<!--[if gte IE 6]>
	<link rel="stylesheet" href="/theme/greenish/css/ie6.css" type="text/css" media="screen" />
	<![endif]-->
	
	<!--[if lt IE 9]>
	<link rel="stylesheet" href="/theme/greenish/css/ie8.css" type="text/css" media="screen" />
	<![endif]-->
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="/js/jquery.windows.js"></script> -->
	<script type="text/javascript" src="/resources/js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="/resources/js/fancybox/jquery.easing-1.3.pack.js"></script>
	<script type="text/javascript" src="/resources/js/jquery-ui/jquery-ui-1.8.9.custom.min.js"></script>
	<script type="text/javascript" src="/resources/js/jquery-v/vector/object.js"></script>
	<script type="text/javascript" src="/resources/js/jquery-v/vector/form.js"></script>
	<?php
		//echo $this->Html->meta('icon');
		//echo $this->Meta->render();
		//echo $this->Html->css('style.css') . "\n";
		//echo $this->Html->css('ie6.css') . "\n";
		//echo $scripts_for_layout . "\n";
	?>
	<?php //$this->Block->block('head') ?>
</head>
<body class="default <?php //echo $this->params['controller'] . ' ' . $this->params['action'] ?>">
	<?php echo $this->block('after-body-start'); ?>
	<div id="wrapper-main">
		<div id="wrapper-inner">
			<!-- Header Section -->
			<div id="header">
				<?php $this->block('header'); ?>
			</div>
			<!-- End Header Section -->
			<div id="content">
				<?php //echo $this->Session->flash(); ?>
				<div id="contentleft">
					<?php $this->block('before-content') ?>
					<?php $this->yield(); ?>
					<?php $this->block('after-content') ?>
				</div>
				<div id="contentright">
					<?php $this->block('right-column') ?>
				</div>
			</div>
			<div id="footer">
				<?php $this->block('before-footer') ?>
				<p>&copy; Copyrights 2010. CarePlacement. All rights reserved.</p>
			</div>
			<?php $this->block('after-footer') ?>
		</div>
	</div>
	<?php $this->block('before-body-end') ?>
	<?php /*Iif ($currentSite['status'] == 2): ?>
		<?php echo $this->element('sql_dump'); ?>
	<?php endif;*/ ?>
</body>
</html>
