<?php 
	$heading	= (isset($heading)) ? $heading : 'Find Availability';
	$buttonText	= (isset($buttonText)) ? $buttonText : 'Start Now';
?>
<div class="right-box plain">
	<h2><?php echo $heading; ?></h2>
	<?php if ($this->Block->widgetExists('Reps.reps')): ?>
		<?php 
			echo $this->Block
					->getWidget('Reps.reps')
					->reset()
					->setParams(array(
						'template' => 'plain'
					))
					->setUp()
					->render();
		?>
	<?php endif; ?>
	<form accept-charset="utf-8" action="/leads/add" method="post" id="RightColLead">
		<input type="hidden" name="data[Lead][trackAs]" value="/vpv/lead/submission/right/<?php echo "{$this->params['controller']}/{$this->params['action']}"; ?>" />
		<input type="hidden" name="data[Leads][redirect]" value="<?php echo $this->here ?>" />
		<?php if (isset($subject)): ?>	
			<input type="hidden" name="data[Lead][subject]" value="<?php echo $subject; ?>" />
		<?php endif; ?>
		<input type="hidden" name="data[Leads][code]" value="LocationForm" />
		<input type="hidden" name="data[Lead][from]" value="<?php echo $this->here ?>" />
		<ul>
			<li>
				<label>City:</label>
				<span><input type="text" name="data[Lead][fields][city]" value="<?php echo (isset($location['name'])) ? $location['name'] : ''; ?>" id="RightLeadCity" /></span>
			</li>
			<li>
				<label>First Name:</label>
				<span><input type="text" name="data[Lead][fields][first_name]" /></span>
			</li>
			<li>
				<label>Last Name:</label>
				<span><input type="text" name="data[Lead][fields][last_name]" /></span>
			</li>
			<li>
				<label>Email:</label>
				<span><input type="text" name="data[Lead][fields][email]" /></span>
			</li>
			<li>
				<label>Phone:</label>
				<span><input type="text" name="data[Lead][fields][phone]" /></span>
			</li>
			<!-- 
			<li>
				<label>Enter this below:</label>
				<img src="/img/spammer.php" width="50" height="24" />
			</li>
			<li><label>&nbsp;</label><span><input type="text" name="hashtastic" type="text" /></span></li>
			-->
			<li class="submit" style="text-align: center;">
				<span><input type="submit" 
						value="<?php echo $buttonText; ?>" />
				</span>
			</li>
			<li class="text">
				Care Placement is commited to your privacy. 
				Your information will not be 
				shared with any third party.
				<a href="/privacy-policy" 
					id="right-privacy-policy" 
					onclick="return ajaxModal(this);">Our privacy policy.</a>
			</li>
			<li class="text">
				<table width="135" border="0" cellpadding="2" cellspacing="0" title="Click to Verify - This site chose VeriSign Trust Seal to promote trust online with consumers.">
					<tr>
					<td width="135" align="center" valign="top"><script type="text/javascript" src="https://seal.verisign.com/getseal?host_name=www.careplacement.com&amp;size=S&amp;use_flash=YES&amp;use_transparent=YES&amp;lang=en"></script><br />
					<a href="http://www.verisign.com/verisign-trust-seal" target="_blank"  style="color:#000000; text-decoration:none; font:bold 7px verdana,sans-serif; letter-spacing:.5px; text-align:center; margin:0px; padding:0px;">ABOUT TRUST ONLINE</a></td>
					</tr>
				</table>
			</li>
		</ul>
	</form>
</div>
<div class="usefulbot"></div>
<script type="text/javascript">

<?php if (isset($search) && $search): ?>
var redirectUri;
<?php endif; ?>
$(document).ready(function() {
	$('#RightLeadCity').autocomplete({
		source: '/locations/search/request:json',
		minLength: 1
	});

	$('#RightColLead').VectorFactory('Form', {
		trackAsPage: true,
		debug: false
	});

	<?php /*if (isset($search) && $search): ?>
	redirectUri	= $('#RightColLead').find('input[name="data\\[Leads\\]\\[redirect\\]"]').val();
	$('#RightLeadCity').bind('autocompletechange', function(event, ui) {
		if (!ui.item || !ui.item.uri) {
			alert(redirectUri);
			$('#RightColLead').find('input[name="data\\[Leads\\]\\[redirect\\]"]').val(redirectUri);
			return;
		}

		$('#RightColLead').find('input[name="data\\[Leads\\]\\[redirect\\]"]').val(ui.item.uri);
	});
	<?php endif;*/ ?>
});

var privacyRequest	= null;
function ajaxModal(el) {
	var html, modal,
	self	= $(el);

	$('#tmp-vcmodal-privacy').remove();
	if (privacyRequest) privacyRequest.abort();

	privacyRequest = $.ajax({
		url: "/pages/view" + self.attr('href') + "/request:json",
		dataType: 'json',
		success: function(data, textStatus, jqXHR) {
			if (data.success) {
				$('body').append('<div id="tmp-vcmodal-privacy">' + data.post.Post.content + '</div>');
				
				modal = $('#tmp-vcmodal-privacy').dialog({
					modal: true,
					width: 600,
					height: 600,
					title: 'Privacy Policy'
				});

				modal.dialog('open');
			}
		}
	});

	privacyRequest = null;

	return false;
}
</script>