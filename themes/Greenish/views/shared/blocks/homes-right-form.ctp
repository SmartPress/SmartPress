<?php 
	$heading	= (isset($heading)) ? $heading : 'Find Availability';
?>
<div class="right-box plain">
	<h2><?php echo $heading; ?></h2>
	<form accept-charset="utf-8" action="/leads/add" method="post" id="RightColLead">
		<input type="hidden" name="data[Lead][trackAs]" value="/vpv/lead/submission/right/<?php echo "{$this->params['controller']}/{$this->params['action']}"; ?>" />
		<input type="hidden" name="data[Leads][redirect]" value="<?php echo $this->here ?>" />
		<?php if (isset($subject)): ?>	
			<input type="hidden" name="data[Lead][subject]" value="<?php echo $subject; ?>" />
		<?php endif; ?>
		<input type="hidden" name="data[Lead][code]" value="LocationForm" />
		<input type="hidden" name="data[Lead][from]" value="<?php echo $this->here ?>" />
		<ul>
			<li>
				<label>Home:</label>
				<span>
					<input 
						type="text" 
						name="data[Lead][fields][home]" 
						value="<?php echo (isset($home->FacilityName)) ? $home->FacilityName : ''; ?>" 
						id="RightLeadHome" />
				</span>
			</li>
			<li>
				<label>First Name:</label>
				<span><input type="text" name="data[Lead][fields][first_name]" class="not-empty" /></span>
			</li>
			<li>
				<label>Last Name:</label>
				<span><input type="text" name="data[Lead][fields][last_name]" class="not-empty" /></span>
			</li>
			<li>
				<label>Email:</label>
				<span><input type="text" name="data[Lead][fields][email]" class="not-empty" /></span>
			</li>
			<li>
				<label>Phone:</label>
				<span><input type="text" name="data[Lead][fields][phone]" class="not-empty" /></span>
			</li>
			<!-- <li>
				<label>Repeat this:</label>
				<img src="/img/spammer.php" width="50" height="24" />
			</li>
			<li><label>&nbsp;</label><span><input type="text" name="hashtastic" type="text" /></span></li>
			-->
			<li class="text">CarePlacement is commited to your privacy.<br /><a href="/privacy-policy" id="right-privacy-policy" onclick="return ajaxModal(this);">Our privacy policy.</a></li>
			<li class="submit"><span><input type="submit" value="START NOW" /></span></li>
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
		debug: false,
		validate: true,
		inputs: {
			onBlurValidate: true
		}
	});

	<?php if (isset($search) && $search): ?>
	redirectUri	= $('#RightColLead').find('input[name="data\\[Leads\\]\\[redirect\\]"]').val();
	$('#RightLeadCity').bind('autocompletechange', function(event, ui) {
		if (!ui.item || !ui.item.uri) {
			alert(redirectUri);
			$('#RightColLead').find('input[name="data\\[Leads\\]\\[redirect\\]"]').val(redirectUri);
			return;
		}

		$('#RightColLead').find('input[name="data\\[Leads\\]\\[redirect\\]"]').val(ui.item.uri);
	});
	<?php endif; ?>
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