<?php $this->formFor($this->user, null, function($f) { ?>

	<?php if ($this->user->errors && $this->user->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->user, 'error')} prohibited this user from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->user->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<div class="field">
		<?php $f->label("id"); ?>
		<?php $f->textField("id"); ?>
	</div>
	<div class="field">
		<?php $f->label("username"); ?>
		<?php $f->textField("username"); ?>
	</div>
	<div class="field">
		<?php $f->label("password"); ?>
		<?php $f->textField("password"); ?>
	</div>
	<div class="field">
		<?php $f->label("group_id"); ?>
		<?php $f->textField("group_id"); ?>
	</div>
	<div class="field">
		<?php $f->label("created_at"); ?>
		<?php $f->textField("created_at"); ?>
	</div>
	<div class="field">
		<?php $f->label("updated_at"); ?>
		<?php $f->textField("updated_at"); ?>
	</div>
	<div class="actions">
		<?php $f->submit('Save'); ?>
	</div>
	
<?php }); ?>