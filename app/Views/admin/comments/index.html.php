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
		</tr>
		
		<?php $this->comments->each(function($comment) { ?>
			<tr>
				<td><?php echo $comment->author; ?></td>
				<td><?php echo $comment->summary(); ?></td>
				<td>
					<?php if ($comment->status != \Cms\Models\Comment::ApprovedStatus): ?>
						<?php $this->linkTo('<i class="icon-thumbs-up"></i> Approve', $this->admin_comments_approve_path($comment->id), ['class' => 'btn']); ?>
					<?php endif; ?>
					<?php $this->linkTo('<i class="icon-edit icon-white"></i> Edit', $this->edit_admin_comment_path($comment->id), ['class' => 'btn btn-primary']); ?>
					<?php $this->linkTo('<i class="icon-trash icon-white"></i> Destroy', $this->admin_comment_path($comment->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger' )); ?>
				</td>
			</tr>
		<?php }); ?>
	</table>
</section>