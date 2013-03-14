<?php echo $this->formTag($this->admin_blocks_url(), ['method' => 'POST'], function() use ($scopes, $info) { ?>
	<?php echo $this->hiddenFieldTag('block[element]', ''); ?>

	<div class="field">
		<?php echo $this->labelTag("block[path]", "Scope"); ?>
		<?php echo $this->selectTag("block[path]", $this->optionsForSelect($scopes)); ?>
	</div>
	
	<div class="field">
		<?php echo $this->labelTag('block[block]', 'Location'); ?>
		<?php echo $this->selectTag('block[block]', $this->optionsForSelect(\SmartPress\Models\Theme::blockOptions())); ?>
	</div>
							
	<?php echo $this->render('dynamic_fields', ['info' => $info, 'params' => []]); ?>
	
	<div class="field">
		<?php echo $this->labelTag('block[params][except]', 'Excluding'); ?>
		<?php echo $this->textFieldTag('block[params][except]'); ?>
		<span class="help-inline">Leave blank if you don't want exclusions.</span>
	</div>
	
	<div class="field">
		<?php echo $this->labelTag('block[params][only]', 'Only On'); ?>
		<?php echo $this->textFieldTag('block[params][only]'); ?>
		<span class="help-inline">Leave blank if you don't want to limit.</span>
	</div>
	
	<div class="field">
		<?php echo $this->labelTag('block[priority]', 'Priority'); ?>
		<?php echo $this->textFieldTag('block[priority]'); ?>
	</div>
<?php }); ?>
