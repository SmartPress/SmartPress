<?php 
$class	= \Speedy\Loader::instance()->toClass($this->block['element']);
$info	= (class_exists($class)) ? $class::info() : null;
?>
<?php echo $this->formFor(['admin', $this->block], ['class' => 'form-horizontal', 'id' => 'block_form'], function($f) use ($info) { ?>
	<?php $params = $this->block['params']; ?>
	<?php if ($this->block->errors && $this->block->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->block, 'error')} prohibited this block from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->block->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>
	<div class="control-group">
		<?php echo $f->label("path", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("path"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("block", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->select("block", \SmartPress\Models\Theme::blockOptions()); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("element", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->select("element", \SmartPress\Models\Block\Manager::availableBlocks()); ?>
		</div>
	</div>
	<div id="block_params_container">
		<?php if (!empty($info)): ?>
			<?php echo $this->render('dynamic_fields_horz', ['info' => $info, 'params' => $params]); ?>
		<?php endif; ?>
	</div>
	<div class="control-group">
		<?php echo $this->labelTag('block[params][except]', 'Excluding', ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $this->textFieldTag('block[params][except]', ['value' => (!empty($params['except']) ? implode(',', $params['except']) : '')]); ?>
			<span class="help-inline">Leave blank if you don't want exclusions.</span>
		</div>
	</div>
	<div class="control-group">
		<?php echo $this->labelTag('block[params][only]', 'Only On', ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $this->textFieldTag('block[params][only]', ['value' => (!empty($params['only'])) ? implode(',', $params['only']) : '']); ?>
			<span class="help-inline">Leave blank if you don't want to limit.</span>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("priority", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("priority"); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php echo $this->linkTo('Cancel', $this->admin_blocks_url(), ['class' => 'btn']); ?>
		<?php echo $f->submit('Save', ['class' => 'btn btn-primary']); ?>
	</div>
	
<?php }); ?>
