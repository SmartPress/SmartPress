<h1>Editing block</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('Show', $this->admin_block_path($this->block->id)); ?> 
|
<?php $this->linkTo('Back', $this->admin_blocks_url()); ?>