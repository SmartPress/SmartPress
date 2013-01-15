<h1>Editing config</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->admin_config_path($this->config->id)); ?> 
|
<?php $this->linkTo('Back', $this->admin_configs_url()); ?>
