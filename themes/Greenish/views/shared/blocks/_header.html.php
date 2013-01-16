<a href="/">
	<img src="/resources/img/care-placement-logo.jpg" alt="Care Placement" class="logo" width="260" height="60" border="0" />
</a>
<div id="call-us">
	<p>Have Questions? Call Us<br />
		<span>(800) 821-7598</span>
	</p>
	<div>
		<img src="/resources/img/yng-wman-w-phone.gif" alt="Call Us with Assisted Living Questions" width="76" height="80" />
	</div>
	<table width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose VeriSign Trust Seal to promote trust online with consumers.">
		<tr>
		<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=www.careplacement.com&amp;size=S&amp;use_flash=YES&amp;use_transparent=YES&amp;lang=en"></script><br />
		<a href="http://www.verisign.com/verisign-trust-seal" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">ABOUT TRUST ONLINE</a></td>
		</tr>
	</table>
</div>
<!-- Header Menu -->
<?php //echo $this->Block->block('header-menu') ?>
<!-- End Header Menu -->
<!-- Main Header Section -->
<div id="header-main">
	<a href="#you-tube-video" id="header-play"></a>
	<img src="/resources/img/test/start-your.gif" alt="Get Free Help Now" width="354" height="22" class="compare">
	
	<?php //$this->Form->create('Lead', array('action' => 'add')) ?>
	<input type="hidden" name="data[Lead][from]" value="<?php //echo $this->here ?>" />
	<input type="hidden" name="data[Lead][trackAs]" value="/vpv/lead/submission/pages/home" />
	<ul>
		<li><?php //echo $this->Form->input('Lead.fields.city', array('div' => false, 'class' => 'textfield')) ?></li>
		<li><?php //echo $this->Form->input('Lead.fields.name', array('div' => false, 'class' => 'textfield')) ?></li>
		<li><?php //echo $this->Form->input('Lead.fields.email', array('div' => false, 'class' => 'textfield')) ?></li>
		<li><?php //echo $this->Form->input('Lead.fields.phone', array('div' => false, 'class' => 'textfield')) ?></li>
		<!-- <li class="comments"><?php //echo $this->Form->input('Lead.fields.comments', array('div' => false, 'class' => 'textarea', 'type' => 'textarea')) ?></li> -->
		<!-- <li class="submit"><label for="hashtastic">Enter this below</label><img src="/img/spammer.php" width="50" height="24" /></li>
		<li><label>&nbsp;</label><input name="hashtastic" id="hashtastic" type="text" class="textfield" /></li>
		-->
		<li class="submit"><?php //echo $this->Form->submit("/theme/greenish/img/start-search.gif", array('div' => false))?></li>
		<?php //echo $this->Form->hidden("Leads.redirect", array('default' => $this->here)) ?>
		<?php //echo $this->Form->hidden("Leads.code", array('default' => "HeaderForm")) ?>
	</ul>
	<?php //echo $this->Form->end() ?>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#LeadAddForm").VectorFactory('Form', {
			trackAsPage: true,
			debug: false
		});

		$('#LeadFieldsCity').autocomplete({
			source: '/locations/search/request:json',
			minLength: 1
		});
	});
	</script>
</div>
<!-- End Main Header Section -->
