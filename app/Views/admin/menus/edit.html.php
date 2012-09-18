<h1>Editing menu</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->menu_path($this->menu->id)); ?> 
|
<?php $this->linkTo('Back', $this->menus_url()); ?>