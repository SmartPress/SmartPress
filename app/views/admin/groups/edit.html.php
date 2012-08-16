<h1>Editing group</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->group_path($this->group->id)); ?> 
|
<?php $this->linkTo('Back', $this->groups_url()); ?>