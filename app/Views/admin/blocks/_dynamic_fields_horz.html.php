<?php foreach ($info['params'] as $name => $options): ?>
	<?php 
		$requireds	= ['input'];
		$valid	= true;
			
		foreach ($requireds as $required) {
			if (!array_key_exists($required, $options)) {
				$valid = false;
				break;
			}
		}
			
		if (!$valid) continue;
	?>
	<div class="control-group">
		<?php $this->labelTag("block[params][$name]", (isset($options['label'])) ? $options['label'] : ucfirst($name), ['class' => 'control-label']); ?>
		<div class="controls">
			<?php 
			if ($options['input'] == 'selectTag') {
				$this->{$options['input']}(
					"block[params][$name]",
					$this->optionsForSelect($options['options'], isset($params[$name]) ? $params[$name] : null)
				);
			} else {
				$this->{$options['input']}(
					"block[params][$name]", 
					['value' => isset($params[$name]) ? $params[$name] : '']
				);
			}
		 	?>
		</div>
	</div>
<?php endforeach; ?>