<div id="bottom-form">
	<?php echo $this->Form->create('Lead', array('action' => 'add')) ?>
	<ul id="innerform">
		<li><?php echo $this->Form->input('Lead.fields.name', array('div' => false, 'class' => 'inner-textfield', 'id' => 'textfield')) ?></li>
		<li><?php echo $this->Form->input('Lead.fields.email', array('div' => false, 'class' => 'inner-textfield', 'id' => 'textfield')) ?></li>
		<li class="inner-comments"><?php echo $this->Form->input('Lead.fields.comments', array('div' => false, 'class' => 'inner-comments', 'type' => 'textarea', 'cols' => '30', 'rows' => '3')) ?></li>
		<li class="inner-submit"><?php echo $this->Form->submit("/theme/greenish/img/inner-submit.gif", array('div' => false, 'width' => '152', 'height' => '32'))?></li>
		<?php echo $this->Form->hidden("Leads.redirect", array('default' => $this->here)) ?>
		<?php echo $this->Form->hidden("Leads.code", array('default' => "BottomForm")) ?>
		<input type="hidden" name="data[Lead][trackAs]" value="/vpv/lead/submission/bottom/<?php echo "{$this->params['controller']}/{$this->params['action']}"; ?>" />
	</ul>
	<?php echo $this->Form->end() ?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#LeadAddForm").jForm({
			trackAsPage: true,
			debug: false
		});
	});
	</script>
</div>