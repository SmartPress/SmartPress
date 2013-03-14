<?php echo $this->formTag($this->admin_configs_url(), ['method' => 'POST'], function() use ($theme) { ?>
	<?php echo $this->hiddenFieldTag('config[0][name]', 'theme'); ?>
	<?php echo $this->hiddenFieldTag('config[0][value]', $theme['path']); ?>
	<?php echo $this->hiddenFieldTag('config_redirect', $this->edit_admin_config_path('look')); ?>
	
	<div class="snapshot">
		<img src="<?php echo ($theme['snapshot']) ? $theme['snapshot'] : '/images/default-theme.png'; ?>" class="img-rounded">
	</div>
	
	<div>
		<dl class="dl-horizontal">
			<dt>Name</dt>
			<dd><?php echo $theme['name']; ?></dd>
		
			<dt>Author</dt>
			<dd><?php echo $theme['author']; ?></dd>
			
			<dt>Version</dt>
			<dd><?php echo $theme['version']; ?></dd>
		</dl>
	</div>
	
	<div class="actions">
		<?php if ($theme['path'] == \SmartPress\Models\Config\Manager::get('theme')): ?>
			<span class="label label-success">In Use</span>
		<?php else: ?>
			<?php echo $this->submit('Choose', ['class' => 'btn btn-primary']); ?>
			<?php echo $this->linkTo('<i class="icon-trash icon-white"></i> Remove', '/admin/themes/' . $theme['filename'], ['confirm' => 'Are you sure?', 'method' => 'delete', 'class' => 'btn btn-danger']); ?>
		<?php endif; ?>
	</div>
<?php }); ?>
