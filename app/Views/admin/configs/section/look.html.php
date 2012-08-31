<?php /*$this->contentFor('left-sidebar', function() { ?> 
	<?php $this->render('left-menu'); ?>
<?php });*/ ?>
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