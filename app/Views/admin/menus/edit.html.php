<h1>Editing menu</h1>

<?php $this->render("full_form"); ?>

<?php $this->linkTo('Show', $this->admin_menu_path($this->menu->id)); ?> 
|
<?php $this->linkTo('Back', $this->admin_menus_url()); ?>