<?php $this->formFor(['admin', $this->post], ['class' => 'cms-form'], function($f) { ?>
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
		<fieldset>
			<div class="field">
				<?php $f->label("title"); ?>
				<?php $f->textField("title", ['class' => 'span12', ['data-target' => 'post_slug', 'data-preview' => 'slug_preview', 'data-edit-btn' => 'slug_edit_btn']]); ?>
			</div>
			<div class="field">
				<?php $f->label("slug"); ?>
				<div class="input-append">
					<span><?php $f->textField("slug"); ?></span>
					<span class="span5 uneditable-input" id="slug_preview">
						<?php echo (isset($this->post->slug) && strlen($this->post->slug) > 0) ? $this->post->slug : ''; ?>
					</span>
					<button class="btn" type="button" id="slug_edit_btn">Edit</button>
				</div>
			</div>
		</fieldset>
		
		<fieldset>
			<legend>Content</legend>
			
			<div class="field">
				<?php $f->textArea("content", ['class' => 'span12 ckeditor', 'rows' => '14']); ?>
			</div>
		</fieldset>
	</div>
	<div class="span3 offset1">
		<div class="well">
			<div class="actions">
				<div class="pull-right">
					<?php $f->submit('Save', ['class' => 'btn btn-primary']); ?>
					<?php $this->linkTo('Cancel', $this->admin_posts_url(), ['class' => 'btn']); ?>
				</div>
			</div>
			<div class="field">
				<?php $f->label("status"); ?>
				<?php $f->select("status", \Cms\Models\Post::statuses(), null, ['class' => 'input-medium']); ?>
			</div>
			<div class="field">
				<?php $f->label("layout"); ?>
				<?php $f->select("layout", \Cms\Models\Theme::availableLayouts(), null, ['class' => 'input-medium']); ?>
			</div>
		</div>
		
		<div class="well">
			<?php foreach($this->post_custom_fields as $field): ?>
				<div class="field">
					<?php $f->label("custom_data.{$field->field}", $field->label); ?>
					<?php $this->textFieldTag($f->formatName("custom_data.{$field->field}"), ['class' => 'span12', 'value' => isset($this->post->custom_data[$field->field]) ? $this->post->custom_data[$field->field] : null ]); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php }); ?>