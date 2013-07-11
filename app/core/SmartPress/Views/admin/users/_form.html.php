<?php 
use SmartPress\Models\Permissions;
?>

<?php echo $this->formFor(['admin', $this->user], ['class' => 'form-horizontal'], function($f) { ?>

	<?php if ($this->user->errors && $this->user->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize('User', 'Error')} prohibited this user from being saved:"); ?>
		</div>
		
		<ul>
			<?php $this->user->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>

	<div class="control-group">
		<?php echo $f->label("name", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("name"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("email", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->textField("email"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("password", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->password("password"); ?>
		</div>
	</div>
	<div class="control-group">
		<?php echo $f->label("password_confirm", null, ['class' => 'control-label']); ?>
		<div class="controls">
			<?php echo $f->password("password_confirm"); ?>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">Permissions</label>
		<div class="controls">
			<?php foreach (Permissions::all() as $label => $perm): ?>

				<label class="checkbox">
					<?php echo $label; ?>
					<?php echo $f->checkBox("permissions." . $perm, [
							'value' => $perm
						], ($this->user->permissions & $perm) ? true : false); ?>
				</label>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<label class="checkbox">
				<?php echo $f->checkBox('generate_password', ['value' => '1']); ?>
				Generate a password
			</label>
			<label class="checkbox">
				<?php echo $f->checkBox('send_welcome', ['value' => '1']); ?>
				Send welcome email
			</label>
		</div>
	</div>
	<div class="control-group">
		<label class="control-label">API Access</label>
		<div class="controls">
			<?php if (!empty($this->user->api_id)): ?>
				<div><strong>ID:</strong> <span><?php echo $this->user->api_id; ?></span></div>
				<div><strong>Key:</strong> <span><?php echo $this->user->api_key; ?></span></div>
			<?php else: ?>
				<?php echo $this->linkTo('Generate', $this->generateApi_admin_user_path($this->user), ['class' => 'btn']); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="form-actions">
		<?php echo $f->submit('Save', ['class' => 'btn btn-primary']); ?>
		<?php echo $this->linkTo('Cancel', $this->admin_users_url(), ['class' => 'btn']); ?>
	</div>
	
<?php }); ?>
