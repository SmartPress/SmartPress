<h1>Editing module</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->module_path($this->module->id)); ?> 
|
<?php $this->linkTo('Back', $this->modules_url()); ?>