<?php /*$this->contentFor('left-sidebar', function() { ?> 
	<?php $this->render('left-menu'); ?>
<?php });*/ ?>
<div class="pull-right well well-small">
	<strong>Upload new theme</strong>
	<?php $this->formTag('/admin/themes', ['enctype' => 'multipart/form-data', 'method' => 'POST', 'class' => 'form-inline'], function() { ?>
		<!-- <div class="field"> -->
			<?php $this->labelTag('theme'); ?>
			<?php $this->fileFieldTag('theme'); ?>
		<!-- </div>
		<div class="actions"> -->
			<?php $this->submit('Upload', array( "class" => 'btn btn-primary' )); ?>
		<!-- </div> -->
	<?php }); ?>	
</div>
<div class="row-fluid">
	<div class="span12">
		<?php if ($this->session->has('flash.error')): ?>
			<div class="alert alert-error fade in">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Error!</strong> <?php echo $this->session->read('flash.error'); ?>
			</div>
		<?php endif; ?>
		
		<div class="page-header">
			<h2>Theme</h2>
		</div>
		<ul class="unstyled themes">
			<?php foreach (\SmartPress\Models\Theme::all() as $theme): ?>
				<li class="span4">
					<?php $this->render('theme-form', ['theme' => $theme]); ?>
				</li>	
			<?php endforeach; ?>
		</ul>
	</div>
</div>
