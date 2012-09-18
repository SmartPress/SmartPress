<?php $this->formTag($this->admin_blocks_url(), ['method' => 'POST'], function() use ($scopes, $info) { ?>
	<div class="field">
		<?php $this->labelTag("block[path]", "Scope"); ?>
		<?php $this->selectTag("block[path]", $this->optionsForSelect($scopes)); ?>
	</div>
	
	<div class="field">
		<?php $this->labelTag('block[block]', 'Location'); ?>
		<?php $this->selectTag('block[block]', $this->optionsForSelect(\Cms\Models\Theme::blockOptions())); ?>
	</div>
							
	<?php foreach ($info['params'] as $method => $name): ?>
		<div class="field">
			<?php $this->labelTag("block[params][$name]", ucfirst($name)); ?>
			<?php $this->{$method}("block[params][$name]", ['value' => isset($params[$name]) ? $params[$name] : '']); ?>
		</div>
	<?php endforeach; ?>
<?php }); ?>