<h1>Editing comment</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->comment_path($this->comment->id)); ?> 
|
<?php $this->linkTo('Back', $this->comments_url()); ?>