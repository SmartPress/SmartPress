<?php $this->formTag($this->admin_configs_url(), ['method' => 'POST'], function() use ($theme) { ?>
	<?php $this->hiddenFieldTag('config[0][name]', 'theme'); ?>
	<?php $this->hiddenFieldTag('config[0][value]', $theme['path']); ?>
	
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
		<?php if ($theme['path'] == \Cms\Models\ConfigManager::instance()->get('theme')): ?>
			<span class="label label-success">In Use</span>
		<?php else: ?>
			<?php $this->submit('Choose', ['class' => 'btn btn-primary']); ?>
		<?php endif; ?>
	</div>
<?php }); ?>