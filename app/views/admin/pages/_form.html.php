<?php $this->formFor($this->post, array('action' => $this->action_path), function($f) { ?>
	<?php if ($this->post->errors && $this->post->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->post, 'error')} prohibited this post from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->post->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<div class="field">
		<?php $f->label("title"); ?>
		<?php $f->textField("title"); ?>
	</div>
	<div class="field">
		<?php $f->label("content"); ?>
		<?php $f->textField("content"); ?>
	</div>
	<div class="field">
		<?php $f->label("custom_data"); ?>
		<?php $f->textField("custom_data"); ?>
	</div>
	<div class="field">
		<?php $f->label("type"); ?>
		<?php $f->textField("type"); ?>
	</div>
	<div class="field">
		<?php $f->label("slug"); ?>
		<?php $f->textField("slug"); ?>
	</div>
	<div class="field">
		<?php $f->label("layout"); ?>
		<?php $f->textField("layout"); ?>
	</div>
	<div class="field">
		<?php $f->label("status"); ?>
		<?php $f->textField("status"); ?>
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