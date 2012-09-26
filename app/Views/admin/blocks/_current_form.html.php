<ul class="thumbnails">
	<?php 
		$elements	= \Cms\Models\Block\Manager::currentFor($controller, $action);
		$blocks		= \Cms\Models\Theme::availableBlocks();
		$i = 0;
		
		$scopes	= \Cms\Models\Block\Manager::scopes($controller, $action);
	?>
	<?php foreach ($elements as $element): ?>
		<?php 
			if (!in_array($element['block'], $blocks)) continue;
			
			$class	= \Speedy\Loader::instance()->toClass($element['element']);
			if (!class_exists($class)) continue;
				
			$info	= $class::info();
			$params	= $element['params'];
			
			$scope	= 'Global';
			if (strpos($element['path'], '/') !== false) {
				$scope	= 'Action Only';
			} elseif ($element['path'] != 'global') {
				$scope	= 'Controller';
			}
			
			$i++;
		?>
		
		<li class="span3">
			<div class="thumbnail">
				<div class="caption">
					<h4><?php echo isset($info['title']) ? $info['title'] : ''; ?></h4>
					<p>
						<strong>Scope:</strong> <?php echo $scope; ?><br>
						<strong>Block:</strong> <?php echo $element['block']; ?>
					</p>
					
					<button 
						type="button" 
						data-toggle="modal" 
						data-target="#block_options_<?php echo $i; ?>"
						class="btn">Configure</button>
					<div 
						class="block-options modal hide fade" 
						id="block_options_<?php echo $i; ?>" 
						tabindex="-1" 
						role="dialog"
						aria-hidden="true">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3><?php echo isset($info['title']) ? $info['title'] : ''; ?></h3>
						</div>
						<?php $this->formTag($this->admin_block_path($element['id']), ['method' => 'POST'], function() use ($info, $scopes, $element, $params) { ?>
							<?php $this->hiddenFieldTag('_method', 'PUT'); ?>
							<?php $this->hiddenFieldTag('block[id]', $element['id']); ?>
							<div class="modal-body">
								<div class="field">
									<?php $this->labelTag("block[path]", "Scope"); ?>
									<?php $this->selectTag("block[path]", $this->optionsForSelect($scopes, $element['path'])); ?>
								</div>
								
								<div class="field">
									<?php $this->labelTag('block[block]', 'Block'); ?>
									<?php $this->selectTag('block[block]', $this->optionsForSelect(\Cms\Models\Theme::blockOptions(), $element['block'])); ?>
								</div>
							
								<?php $this->render('admin/blocks/_dynamic_fields', ['info' => $info, 'params' => $params]); ?>
								
								<div class="field">
									<?php $this->labelTag('block[priority]', 'Block'); ?>
									<?php $this->textFieldTag('block[priority]', $element['priority']); ?>
								</div>
							</div>
							
							<div class="modal-footer">
								<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
								<?php $this->submit('Save', ['class' => 'btn btn-primary']); ?>
							</div>
						<?php }); ?>
					</div>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
</ul>