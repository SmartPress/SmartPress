<?php $this->formTag('/admin/sessions', ['method' => 'POST', 'class' => 'form-horizontal'], function() { ?>
	<?php if ($this->session->has('flash.error')): ?>
		<div class="alert alert-error fade in">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong>Error!</strong> <?php echo $this->session->read('flash.error'); ?>
		</div>
	<?php endif; ?>
	<legend>Sign In</legend>
	<div class="control-group">
		<label class="control-label" for="username">Email/Username</label>
		<div class="controls">
			<?php $this->textFieldTag('username', ['placeholder' => 'Email/Username']); ?>
		</div>
	</div>
	
	<div class="control-group">
		<label class="control-label" for="password">Password</label>
		<div class="controls">
			<?php $this->passwordFieldTag('password', ['placeholder' => 'Password']); ?>
		</div>
	</div>
	
	<div class="control-group">
		<div class="controls">
			<?php $this->submit('Sign In', ['class' => 'btn']); ?>
		</div>
	</div>
<?php }); ?>