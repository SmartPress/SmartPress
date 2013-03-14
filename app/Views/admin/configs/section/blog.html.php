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
		<?php echo $this->formTag($this->admin_configs_url(), ['method' => 'POST', 'class' => 'form-horizontal'], function()  { 
			$title	= \SmartPress\Models\Config\Manager::get(\SmartPress\Models\Config::DefaultTitleName);
			$home	= \SmartPress\Models\Config\Manager::get(\SmartPress\Models\Config::HomeTypeName);
			$tagLine= \SmartPress\Models\Config\Manager::get(\SmartPress\Models\Config::BlogTagLineName);
			?>
			
			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[0][name]', \SmartPress\Models\Config::DefaultTitleName); ?>
				<?php echo $this->labelTag('config[0][value]', 'Default Title', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->textFieldTag('config[0][value]', ['value' => ($title) ? $title : '' ]); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[1][name]', \SmartPress\Models\Config::BlogTagLineName); ?>
				<?php echo $this->labelTag('config[1][value]', 'Tag Line', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->textFieldTag('config[1][value]', ['value' => ($tagLine) ? $tagLine : '' ]); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[2][name]', \SmartPress\Models\Config::HomeTypeName); ?>
				<?php echo $this->labelTag('config[2][value]', 'Home Page Type', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->selectTag('config[2][value]', $this->optionsForSelect(\SmartPress\Models\Post::$homeTypes, (isset($home['type'])) ? $home['type'] : null)); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[3][name]', \SmartPress\Models\Config::HomeSingleName); ?>
				<?php echo $this->labelTag('config[3][value]', 'Single Page', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->selectTag('config[3][value]', $this->optionsForSelect(
							$this->optionsFromCollectionForSelect(\SmartPress\Models\Post::allPages(), 'id', 'title'), 
							(isset($home['single_id'])) ? $home['single_id'] : null)); ?>
				</div>
			</div>
			
			<div class="form-actions">
				<?php echo $this->submit('Save', ['class' => 'btn btn-primary']); ?>
				<?php echo $this->linkTo('Cancel', $this->admin_configs_url(), ['class' => 'btn']); ?>
			</div>
		<?php }); ?>
	</div>
</div>
