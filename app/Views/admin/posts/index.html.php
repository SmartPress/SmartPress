<?php $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Posts</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Title</th>
			<th>Status</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->posts->each(function($post) { ?>
			<tr>
				<td><?php echo $post->title; ?></td>
				<td><?php echo $post->status_label; ?></td>
				<td><?php $this->linkTo('<i class="icon-eye-open"></i> Show', $this->admin_post_path($post->id), ['class' => 'btn']); ?></td>
				<td><?php $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_post_path($post->id), ['class' => 'btn btn-primary']); ?></td>
				<td><?php $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_post_path($post->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>