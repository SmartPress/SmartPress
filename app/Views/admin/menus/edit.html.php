<h1>Editing menu</h1>

<?php echo $this->render("full_form"); ?>

<?php echo $this->linkTo('Show', $this->admin_menu_path($this->menu->id)); ?> 
|
<?php echo $this->linkTo('Back', $this->admin_menus_url()); ?>
