<div class="row-fluid">
	<div class="page-header">
		<h1>Editing Post</h1>
	</div>
		
	<?php $this->render('form'); ?>
</div>
<div class="row-fluid">
	<div class="page-header">
		<h2>Blocks <button class="btn btn-small btn-primary" data-toggle="modal" data-target="#new_block"><i class="icon-plus icon-white"></i></button></h2>
	</div>
	<?php $this->render('admin/blocks/_current_form', ['controller' => 'posts', 'action' => 'show']); ?>
</div>
<div id="new_block" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3>Add Block</h3>
	</div>
	
	<div class="modal-body">
		<?php $this->selectTag('new_block', $this->optionsForSelect($this->blocks), ['id' => 'new_block_select', 'data' => ['controller' => 'posts', 'action' => 'show']]); ?>
		<div id="block_form"></div>
	</div>
	
	<div class="modal-footer">
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<?php $this->submit('Save', ['class' => 'btn btn-primary', 'id' => 'new_block_save_btn']); ?>
	</div>
</div>