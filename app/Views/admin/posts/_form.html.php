<?php $this->formFor(['admin', $this->post], ['class' => ''], function($f) { ?>
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
	<?php $f->hidden("type"); ?>
	
	<div class="span7">
		<div class="field">
			<?php $f->label("title"); ?>
			<?php $f->textField("title", ['class' => 'span12']); ?>
		</div>
		<div class="field">
			<?php $f->label("content"); ?>
			<?php $f->textArea("content", ['class' => 'span12', 'rows' => '14']); ?>
		</div>
		<div class="field">
			<?php $f->label("custom_data"); ?>
			<?php $f->textField("custom_data"); ?>
		</div>
		<div class="field">
			<?php $f->label("slug"); ?>
			<?php $f->textField("slug", ['class' => 'span12']); ?>
		</div>
	</div>
	<div class="span4">
		<div class="actions pull-right">
			<?php $f->submit('Save', ['class' => 'btn btn-primary']); ?>
			<?php $this->linkTo('Cancel', $this->admin_posts_url(), ['class' => 'btn']); ?>
		</div>
		<div class="field">
			<?php $f->label("status"); ?>
			<?php $f->select("status", \Cms\Models\Post::statuses(), null, ['class' => 'input-medium']); ?>
		</div>
		<div class="field">
			<?php $f->label("layout"); ?>
			<?php $f->textField("layout"); ?>
		</div>
	</div>
<?php }); ?>