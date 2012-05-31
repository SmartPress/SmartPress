<?php $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Posts</h1>
	</div>
	
	<table class="table table-striped">
		<tr>
			<th>Title</th>
			<th>Status</th>
			<th>Updated At</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->posts->each(function($post) { ?>
			<tr>
				<td><?php echo $post->title; ?></td>
				<td><?php echo $post->status; ?></td>
				<td><?php echo $post->updated_at; ?></td>
				<td><?php $this->linkTo('Show', $this->admin_post_path($post->id)); ?></td>
				<td><?php $this->linkTo('Edit', $this->edit_admin_post_path($post->id)); ?></td>
				<td><?php $this->linkTo('Destroy', $this->admin_post_path($post->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>