<h1>Editing comment</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->admin_comment_path($this->comment->id)); ?> 
|
<?php $this->linkTo('Back', $this->admin_comments_url()); ?>
