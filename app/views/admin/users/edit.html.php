<h1>Editing user</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->user_path($this->user->id)); ?> 
|
<?php $this->linkTo('Back', $this->users_url()); ?>