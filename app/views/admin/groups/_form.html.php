<?php $this->formFor($this->group, null, function($f) { ?>

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
	<div class="field">
		<?php $f->label("id"); ?>
		<?php $f->textField("id"); ?>
	</div>
	<div class="field">
		<?php $f->label("name"); ?>
		<?php $f->textField("name"); ?>
	</div>
	<div class="field">
		<?php $f->label("read_privileges"); ?>
		<?php $f->textField("read_privileges"); ?>
	</div>
	<div class="field">
		<?php $f->label("write_privileges"); ?>
		<?php $f->textField("write_privileges"); ?>
	</div>
	<div class="actions">
		<?php $f->submit('Save'); ?>
	</div>
	
<?php }); ?>