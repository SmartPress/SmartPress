<?php $menus->each(function($menu) use ($level) { ?>
	<li class="ui-state-default level-<?php echo $level ?>" data-id="<?php echo $menu->id; ?>">
		<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
		<?php echo $menu->title; ?>
		<?php echo $this->linkTo('<i class="icon-remove-sign icon-white"></i>', $this->admin_menu_path($menu), ['class' => 'btn btn-mini btn-danger pull-right', 'confirm' => 'Are you sure?', 'method' => 'delete']); ?>
	</li>
	<?php if (isset($menu->children) && $menu->children->count() > 0): ?>
		<?php echo $this->render('sortable', ['menus' => $menu->children, 'level' => ++$level]); ?>
	<?php endif; ?>
<?php }); ?>
