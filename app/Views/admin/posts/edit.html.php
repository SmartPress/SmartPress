<div class="row-fluid">
	<div class="page-header">
		<h1>Editing Post</h1>
	</div>
		
	<?php $this->render('form'); ?>
</div>
<div class="row-fluid">
	<div class="page-header">
		<h2>Blocks <button class="btn btn-small btn-primary"><i class="icon-plus icon-white"></i></button></h2>
	</div>
	<?php $this->render('admin/blocks/_current_form', ['controller' => 'posts', 'action' => 'show']); ?>
</div>