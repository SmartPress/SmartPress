<h1>Listing comments</h1>

<table>
	<tr>
		<th>Id</th>
		<th>Post</th>
		<th>User</th>
		<th>Content</th>
		<th>Author</th>
		<th>Author Email</th>
		<th>Author Url</th>
		<th>Author Ip</th>
		<th>Status</th>
		<th>Created At</th>
		<th>Updated At</th>
		<th></th>
		<th></th>
		<th></th>
	</tr>
	
	<?php $this->comments->each(function($comment) { ?>
		<tr>
			<td><?php echo $comment->id; ?></td>
			<td><?php echo $comment->post_id; ?></td>
			<td><?php echo $comment->user_id; ?></td>
			<td><?php echo $comment->content; ?></td>
			<td><?php echo $comment->author; ?></td>
			<td><?php echo $comment->author_email; ?></td>
			<td><?php echo $comment->author_url; ?></td>
			<td><?php echo $comment->author_ip; ?></td>
			<td><?php echo $comment->status; ?></td>
			<td><?php echo $comment->created_at; ?></td>
			<td><?php echo $comment->updated_at; ?></td>
			<td><?php $this->linkTo('Show', $this->admin_comment_path($comment->id)); ?></td>
			<td><?php $this->linkTo('Edit', $this->edit_admin_comment_path($comment->id)); ?></td>
			<td><?php $this->linkTo('Destroy', $this->admin_comment_path($comment->id), array( 'confirm' => 'Are you sure?', 'method' => 'delete' )); ?></td>
		</tr>
	<?php }); ?>
</table>