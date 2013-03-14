<h1>Editing config</h1>

<?php echo $this->render("form"); ?>

<?php echo $this->linkTo('Show', $this->admin_config_path($this->config->id)); ?> 
|
<?php echo $this->linkTo('Back', $this->admin_configs_url()); ?>
