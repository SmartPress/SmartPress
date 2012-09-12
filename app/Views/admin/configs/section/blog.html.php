<div class="row-fluid">
	<div class="span12">
		<?php if ($this->session->has('flash.error')): ?>
			<div class="alert alert-error fade in">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Error!</strong> <?php echo $this->session->read('flash.error'); ?>
			</div>
		<?php endif; ?>
		
		<div class="page-header">
			<h2>Blog Settings</h2>
		</div>
		<?php $this->formTag($this->admin_configs_url(), ['method' => 'POST', 'class' => 'form-horizontal'], function() use ($theme) { 
			$title = \Cms\Models\ConfigManager::get('title/default');
			?>
			
			<div class="control-group">
				<?php $this->hiddenFieldTag('config[0][name]', 'title/default'); ?>
				<?php $this->labelTag('config[0][value]', 'Default Title', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php $this->textFieldTag('config[0][value]', ['value' => ($title) ? $title : '' ]); ?>
				</div>
			</div>
			
			<div class="form-actions">
				<?php $this->submit('Save', ['class' => 'btn btn-primary']); ?>
				<?php $this->linkTo('Cancel', $this->admin_configs_url(), ['class' => 'btn']); ?>
			</div>
		<?php }); ?>
	</div>
</div>