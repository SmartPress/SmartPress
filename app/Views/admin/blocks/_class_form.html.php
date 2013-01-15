<?php $this->formTag($this->admin_blocks_url(), ['method' => 'POST'], function() use ($scopes, $info) { ?>
	<?php $this->hiddenFieldTag('block[element]', ''); ?>

	<div class="field">
		<?php $this->labelTag("block[path]", "Scope"); ?>
		<?php $this->selectTag("block[path]", $this->optionsForSelect($scopes)); ?>
	</div>
	
	<div class="field">
		<?php $this->labelTag('block[block]', 'Location'); ?>
		<?php $this->selectTag('block[block]', $this->optionsForSelect(\SmartPress\Models\Theme::blockOptions())); ?>
	</div>
							
	<?php $this->render('dynamic_fields', ['info' => $info, 'params' => []]); ?>
	
	<div class="field">
		<?php $this->labelTag('block[params][except]', 'Excluding'); ?>
		<?php $this->textFieldTag('block[params][except]'); ?>
		<span class="help-inline">Leave blank if you don't want exclusions.</span>
	</div>
	
	<div class="field">
		<?php $this->labelTag('block[params][only]', 'Only On'); ?>
		<?php $this->textFieldTag('block[params][only]'); ?>
		<span class="help-inline">Leave blank if you don't want to limit.</span>
	</div>
	
	<div class="field">
		<?php $this->labelTag('block[priority]', 'Priority'); ?>
		<?php $this->textFieldTag('block[priority]'); ?>
	</div>
<?php }); ?>
