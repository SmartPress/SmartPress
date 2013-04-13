<?php echo $this->formFor(['admin', $this->post], ['class' => 'smart_press-form'], function($f) { ?>
	<?php if ($this->post->errors && $this->post->errors->count()): ?>
		<div id="error_explanation">
			<?php echo $this->element('h2', "{$this->pluralize($this->post, 'error')} prohibited this post from beign saved:"); ?>
		</div>
		<ul>
			<?php foreach($this->post->errors as $error): ?>
				<li><?php echo $error; ?></li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	<?php echo $f->hidden("type"); ?>
	
	<div class="span7">
		<fieldset>
			<div class="field">
				<?php echo $f->label("title"); ?>
				<?php echo $f->textField("title", ['class' => 'span12', ['data-target' => 'post_slug', 'data-preview' => 'slug_preview', 'data-edit-btn' => 'slug_edit_btn']]); ?>
			</div>
			<div class="field">
				<?php echo $f->label("slug"); ?>
				<div class="input-append">
					<span><?php echo $f->textField("slug", strlen($this->post->slug) > 0 ? ['data-locked' => 'true'] : []); ?></span>
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
				<?php echo $f->textArea("content", ['class' => 'span12 ckeditor', 'rows' => '14']); ?>
			</div>
		</fieldset>
	</div>
	<div class="span3 offset1">
		<div class="well">
			<div class="actions">
				<div class="pull-right">
					<?php echo $f->submit('Save', ['class' => 'btn btn-primary']); ?>
					<?php echo $this->linkTo('Cancel', $this->admin_posts_url(), ['class' => 'btn']); ?>
				</div>
			</div>
			<div class="field">
				<?php echo $f->label("status"); ?>
				<?php echo $f->select("status", \SmartPress\Models\Post::statuses(), null, ['class' => 'input-medium']); ?>
			</div>
			<div class="field">
				<?php echo $f->label("layout"); ?>
				<?php echo $f->select("layout", \SmartPress\Models\Theme::availableLayouts(), null, ['class' => 'input-medium']); ?>
			</div>
			<div class="field">
				<?php echo $f->label("author_id"); ?>
				<?php echo $f->select('author_id', \SmartPress\Models\User::allForOptions(), null, ['class' => 'input-medium']); ?>
			</div>
		</div>

		<div class="well">
			<h4>Categories</h4>
			<?php $i = 1; ?>
			<?php foreach (\SmartPress\Models\Category::all() as $category): ?>
				<?php $cat = $this->post->findCategory($category->id); ?>
				<?php echo $this->checkBoxTag("post_category.$i", [
						'value' => $category->id,
						'checked'	=> (isset($cat)) ? true : false
					]); $i++; ?>
				<?php echo $category->name; ?>
				<?php unset($cat); ?>
			<?php endforeach; ?>
		</div>
		
		<div class="well">
			<?php foreach($this->post_custom_fields as $field): ?>
				<div class="field">
					<?php echo $f->label("custom_data.{$field->field}", $field->label); ?>
					<?php echo $this->textFieldTag($f->formatName("custom_data.{$field->field}"), ['class' => 'span12', 'value' => isset($this->post->custom_data[$field->field]) ? $this->post->custom_data[$field->field] : null ]); ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php }); ?>
