<?php /*$this->contentFor('left-sidebar', function() { ?> 
	<?php $this->render('left-menu'); ?>
<?php });*/ ?>
<div class="pull-right">
	<p>Upload new theme</p>
	<?php $this->formTag('/admin/themes', ['enctype' => 'multipart/form-data', 'method' => 'POST'], function() { ?>
		<div class="field">
			<?php $this->labelTag('theme'); ?>
			<?php $this->fileFieldTag('theme'); ?>
		</div>
		<div class="actions">
			<?php $this->submit('Upload', array( "class" => 'btn btn-primary' )); ?>
		</div>
	<?php }); ?>	
</div>

<div class="page-header">
	<h2>Theme</h2>
</div>
<ul class="unstyled themes">
	<?php foreach (\Cms\Models\Theme::all() as $theme): ?>
		<li class="span4">
			<?php $this->render('theme-form', ['theme' => $theme]); ?>
		</li>	
	<?php endforeach; ?>
</ul>