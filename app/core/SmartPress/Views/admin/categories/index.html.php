<?php echo $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Categories</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Name</th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->category->each(function($category) { ?>
			<tr>
				<td><?php echo $category->name; ?></td>
				<td><?php echo $this->linkTo('Edit', $this->edit_admin_category_path($category->id), array('class' => 'btn btn-primary')); ?></td>
				<td><?php echo $this->linkTo('Destroy', $this->admin_category_path($category->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>