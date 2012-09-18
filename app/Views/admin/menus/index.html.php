<?php $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Menus</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Parent</th>
			<th>Lft</th>
			<th>Rght</th>
			<th>Title</th>
			<th>Url</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->menus->each(function($menu) { ?>
			<tr>
				<td><?php echo $menu->parent_id; ?></td>
				<td><?php echo $menu->lft; ?></td>
				<td><?php echo $menu->rght; ?></td>
				<td><?php echo $menu->title; ?></td>
				<td><?php echo $menu->url; ?></td>
				<td><?php $this->linkTo('Show', $this->admin_menu_path($menu->id)); ?></td>
				<td><?php $this->linkTo('Edit', $this->edit_admin_menu_path($menu->id)); ?></td>
				<td><?php $this->linkTo('Destroy', $this->admin_menu_path($menu->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>