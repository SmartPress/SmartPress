<h1>Editing comment</h1>

<?php echo $this->render("form"); ?>

<?php echo $this->linkTo('Show', $this->comment_path($this->comment->id)); ?> 
|
<?php echo $this->linkTo('Back', $this->comments_url()); ?>
