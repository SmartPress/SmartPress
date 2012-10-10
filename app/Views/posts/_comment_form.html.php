<?php $this->formFor([$this->post, $this->comment], null, function($f) { ?>

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
		<?php $f->label("content"); ?>
		<?php $f->textArea("content"); ?>
	</div>
	<div class="actions">
		<?php $f->submit('Save'); ?>
	</div>
	
<?php }); ?>