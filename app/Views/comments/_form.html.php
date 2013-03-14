<?php echo $this->formFor($this->comment, null, function($f) { ?>

	<?php if ($this->comment->errors && $this->comment->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->comment, 'error')} prohibited this comment from beign saved:"); ?>
		</div>
		<ul>
			<?php $this->comment->errors->each(function($error) { ?>
				<li><?php echo $error; ?></li>
			<?php }); ?>
		</ul>
	<?php endif; ?>
	<div class="field">
		<?php echo $f->label("post_id"); ?>
		<?php echo $f->textField("post_id"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("user_id"); ?>
		<?php echo $f->textField("user_id"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("content"); ?>
		<?php echo $f->textField("content"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("author"); ?>
		<?php echo $f->textField("author"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("author_email"); ?>
		<?php echo $f->textField("author_email"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("author_url"); ?>
		<?php echo $f->textField("author_url"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("author_ip"); ?>
		<?php echo $f->textField("author_ip"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("status"); ?>
		<?php echo $f->textField("status"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("created_at"); ?>
		<?php echo $f->textField("created_at"); ?>
	</div>
	<div class="field">
		<?php echo $f->label("updated_at"); ?>
		<?php echo $f->textField("updated_at"); ?>
	</div>
	<div class="actions">
		<?php echo $f->submit('Save'); ?>
	</div>
	
<?php }); ?>
