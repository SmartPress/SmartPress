<?php
use SmartPress\Models\Config\Manager as ConfigManager;
use SmartPress\Models\Config;
?>

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
			$title	= ConfigManager::get(Config::DefaultTitleName);
			$home	= ConfigManager::get(Config::HomeTypeName);
			$tagLine= ConfigManager::get(Config::BlogTagLineName);
			$format = ConfigManager::get(Config::TitleFormat);
			$gaCode = ConfigManager::get(Config::GACode);
			$postUrlSchema = ConfigManager::get(Config::PostUrlSchema);
			?>
			

			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[0][name]', Config::DefaultTitleName); ?>
				<?php echo $this->labelTag('config[0][value]', 'Default Title', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->textFieldTag('config[0][value]', ['value' => ($title) ? $title : '' ]); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[1][name]', Config::TitleFormat); ?>
				<?php echo $this->labelTag('config[1][value]', 'Title Format', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->textFieldTag('config[1][value]', ['value' => ($format) ? $format : '' ]); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[2][name]', Config::BlogTagLineName); ?>
				<?php echo $this->labelTag('config[2][value]', 'Tag Line', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->textFieldTag('config[2][value]', ['value' => ($tagLine) ? $tagLine : '' ]); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[3][name]', Config::HomeTypeName); ?>
				<?php echo $this->labelTag('config[3][value]', 'Home Page Type', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->selectTag('config[3][value]', $this->optionsForSelect(\SmartPress\Models\Post::$homeTypes, (isset($home)) ? $home : null)); ?>
				</div>
			</div>
			
			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[4][name]', Config::HomeSingleName); ?>
				<?php echo $this->labelTag('config[4][value]', 'Single Page', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->selectTag('config[4][value]', $this->optionsForSelect(
							$this->optionsFromCollectionForSelect(\SmartPress\Models\Post::allPages(), 'id', 'title'), 
							(isset($home['single_id'])) ? $home['single_id'] : null)); ?>
				</div>
			</div>

			<div class="control-group">
				<?php echo $this->hiddenFieldTag('config[5][name]', Config::GACode); ?>
				<?php echo $this->labelTag('config[5][value]', 'Google Analytics Code', ['class' => 'control-label']); ?>
				
				<div class="controls">
					<?php echo $this->textAreaTag('config[5][value]', !empty($gaCode) ? $gaCode : ''); ?>
				</div>
			</div>

			<fieldset>
				<legend>URL Schemas</legend>

				<div class="control-group">
					<?php echo $this->hiddenFieldTag('config[6][name]', Config::PostUrlSchema); ?>
					<?php echo $this->labelTag('config[6][value]', 'Post Url Schema', ['class' => 'control-label']); ?>
					
					<div class="controls">
						<?php echo $this->textFieldTag('config[6][value]', !empty($postUrlSchema) ? $postUrlSchema : ''); ?>
					</div>
				</div>
			</fieldset>
			
			<div class="form-actions">
				<?php echo $this->submit('Save', ['class' => 'btn btn-primary']); ?>
				<?php echo $this->linkTo('Cancel', $this->admin_configs_url(), ['class' => 'btn']); ?>
			</div>
		<?php }); ?>
	</div>
</div>
