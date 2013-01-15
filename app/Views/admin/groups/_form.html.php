<?php $this->formFor(['admin', $this->group], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->group->errors && $this->group->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->group, 'error')} prohibited this group from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->group->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="control-group">
		<?php $f->label("name", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("name"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("privilege", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("privilege"); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php $this->linkTo('Cancel', $this->admin_groups_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>
