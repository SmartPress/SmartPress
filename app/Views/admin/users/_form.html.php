<?php $this->formFor(['admin', $this->user], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->user->errors && $this->user->errors->count()): ?>
		<div id="error_explanation">
			<?php $this->element('h2', "{$this->pluralize($this->user, 'Error')} prohibited this user from being saved:"); ?>
		</div>
		
		<ul>
			<?php $this->user->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>

	<div class="control-group">
		<?php $f->label("username", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("username"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("email", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->textField("email"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("password", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->password("password"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("password_confirm", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->password("password_confirm"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php $f->label("group_id", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php $f->collectionSelect("group_id", $this->groups, 'id', 'name'); ?>
		</div>
	</div>
	<div class="form-actions">
		<?php $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php $this->linkTo('Cancel', $this->admin_users_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>