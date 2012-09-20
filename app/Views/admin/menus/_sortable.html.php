<?php $menus->each(function($menu) use ($level) { ?>
	<li class="ui-state-default level-<?php echo $level ?>">
		<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
		<?php echo $menu->title; ?>
	</li>
	<?php if (isset($menu->children) && $menu->children->count() > 0): ?>
		<?php $this->render('sortable', ['menus' => $menu->children, 'level' => ++$level]); ?>
	<?php endif; ?>
<?php }); ?>
