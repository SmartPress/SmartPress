<?php $this->formFor($this->comment, null, function($f) { ?>

	<?php if ($this->comment->errors && $this->comment->errors->count()): ?>
		<div id="error_explanation">
			<?php element('h2', "{$this->pluralize($this->comment, 'error')} prohibited this comment from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->comment->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>
	<div class="field">
		<?php $f->label("post_id"); ?>
		<?php $f->textField("post_id"); ?>
	</div>
	<div class="field">
		<?php $f->label("user_id"); ?>
		<?php $f->textField("user_id"); ?>
	</div>
	<div class="field">
		<?php $f->label("content"); ?>
		<?php $f->textField("content"); ?>
	</div>
	<div class="field">
		<?php $f->label("author"); ?>
		<?php $f->textField("author"); ?>
	</div>
	<div class="field">
		<?php $f->label("author_email"); ?>
		<?php $f->textField("author_email"); ?>
	</div>
	<div class="field">
		<?php $f->label("author_url"); ?>
		<?php $f->textField("author_url"); ?>
	</div>
	<div class="field">
		<?php $f->label("author_ip"); ?>
		<?php $f->textField("author_ip"); ?>
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
