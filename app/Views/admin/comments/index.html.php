<section id="grid">
	<div class="page-header">
		<h1>Comments</h1>
	</div>
	<table class="table table-striped">
		<tr>
			<th>Author</th>
			<th>Content</th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
		
		<?php $this->comments->each(function($comment) { ?>
			<tr>
				<td><?php echo $comment->author; ?></td>
				<td><!-- Content --></td>
				<td><?php $this->linkTo('Show', $this->admin_comment_path($comment->id)); ?></td>
				<td><?php $this->linkTo('Edit', $this->edit_admin_comment_path($comment->id)); ?></td>
				<td><?php $this->linkTo('Destroy', $this->admin_comment_path($comment->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
			</tr>
		<?php }); ?>
	</table>
</section>