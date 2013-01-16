<?php 
	$default_base	= 'img/homes/defaults/';
	$default_imgs	= array(
		'86492748_8-med.jpg',
		'88295786_8-med.jpg',
		'88295794_8-med.jpg',
		'88461057_8-med.jpg',
		'couple-waiting-for-sunset-med.jpg',
		'gen-of-happy-women-med.jpg',
		'grandma-and-me-med.jpg',
		'happyoldercouple-med.jpg',
		'happy-older-couple-med.jpg',
		'happy-senior-man-med.jpg',
		'mom-daug-photo-med.jpg',
		'old-and-young-med.jpg',
		'older_couple-med.jpg',
		'outdoor-portrait-med.jpg'
	);
	if (!isset($attributes['gallery']['image1']['value'])) {
		$attributes['gallery']	= array(
			'image1'	=> array(
				'value'	=> $default_base . $default_imgs[array_rand($default_imgs)]
			)
		);
	} elseif (!strlen($attributes['gallery']['image1']['value'])) {
		$attributes['gallery']['image1']['value']	= $default_base . $default_imgs[array_rand($default_imgs)];
	}
?>
<h1><?php echo $home['facility_name']; ?> - <?php echo $location['name']; ?>, CA</h1>
<div class="tool-bar">
	<div>
		<a href="/email_friend/home/<?php echo $home['id']; ?>"><img src="/theme/greenish/img/email-forward.jpg" border="0" /></a><a href="/email_friend/home/<?php echo $home['id']; ?>">Email To A Friend</a>
	</div>
	<div><a href="javascript:void(0);" id="d_clip_button">Copy Url</a></div>
</div>
<div class="compare-button ui-widget-header ui-corner-all">
	<a href="javascript:void(0)">Compare Homes</a>
</div>
<?php if (isset($attributes['gallery']) && count($attributes['gallery'])): ?>
	<?php if (isset($attributes['gallery']['image1']['value']) && strlen($attributes['gallery']['image1']['value'])): ?>
<div class="homes-gallery">
	<strong><?php echo $home['facility_name']; ?><br /><span><?php echo $location['name']; ?>, CA</span></strong>
	<img src="/<?php echo $attributes['gallery']['image1']['value']; ?>" width="225" alt="" />
</div>
	<?php endif; ?>
<?php endif; ?>
<?php if (( isset($home['lat']) && strlen($home['lat']) ) && ( isset($home['lng']) && strlen($home['lng']) )): ?>
<div class="home-map-cont">
	<div id="home-map">
	</div>
</div>

<script type="text/javascript"
   	src="http://maps.google.com/maps/api/js?sensor=false">
</script>
<script type="text/javascript" src="/js/ZeroClipboard.js"></script>
<script type="text/javascript">
function initGoogleMap() {
	var latlng	= new google.maps.LatLng(<?php echo $home['lat']; ?>, <?php echo $home['lng']; ?>);
	var myOptions = {
		      zoom: 9,
		      center: latlng,
		      mapTypeId: google.maps.MapTypeId.ROADMAP,
		      disableDefaultUI: true,
		      zoomControl: false,
		      panControl: false,
		      scaleControl: false,
		      streetViewControl: false,
		      scrollwheel: false
		    };
	var map = new google.maps.Map(document.getElementById("home-map"),
	        myOptions);

	var marker = new google.maps.Marker({
	      position: latlng, 
	      map: map, 
	      title:"<?php echo $home['facility_name']; ?>"
	  });
}

var clip = null;
$(document).ready(function() {
	initGoogleMap();
	
	$('div.compare-button a').button().click(function() {
		$( '#comparision-form' ).dialog( 'open' );
	});

	$("#comparision-form").dialog({
		autoOpen: false,
		height: 300,
		width: 350,
		modal: true,
		buttons: {
			"Get Comparision": function() {
				$( "#ComparisionForm" ).submit();
				$( this ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});

	$('#ComparisionForm').jForm({
		trackAsPage: true,
		debug: false,
		addFormId: true
	});

	ZeroClipboard.setMoviePath('/img/flash/ZeroClipboard.swf');
	
	clip	= new ZeroClipboard.Client();
	clip.setText("http://<?php echo $currentSite['domain'] . $this->here; ?>?utm_source=direct&utm_medium=copied_link");
	clip.setHandCursor(true);

	clip.addEventListener( 'complete', function(client, text) { 
		alert("Copied url to clipboard"); 
		_gaq.push([ '_trackPageview', '/vpv<?php echo $this->here; ?>/copy_text' ])
	});

	clip.glue('d_clip_button');
});
</script>
<?php endif; ?>
<div class="header-cont">
	<h2 class="home-title"><?php echo $home['facility_name'] ?> <span class="phone-number"><?php echo $phone_number; ?></span></h2>
	<span class="title-tag">An Assisted Living, Independent Living, &amp; Skilled Nursing Community</span>
</div>
<div class="home-content">
	<?php if (isset($home['special_detail']) && strlen($home['special_detail'])): ?>
		<?php echo $home['special_detail']; ?>
	<?php endif; ?>
	
	<?php if (isset($home['remarks']) && strlen($home['remarks'])): ?>
		<h3>Description</h3>
		<p><?php echo $home['remarks']; ?></p>
	<?php endif; ?>
</div>
<div id="comparision-form" title="Compare <?php echo $home['facility_name']; ?> to Other Options">
	<form action="/leads/add" method="post" id="ComparisionForm">
		<input type="hidden" name="data[Lead][trackAs]" value="/vpv/lead/submission/home/comparison">
		<input type="hidden" name="data[Leads][redirect]" value="<?php echo $this->here; ?>">
		<input type="hidden" name="data[Leads][code]" value="LocationForm">
		<input type="hidden" name="data[Lead][from]" value="<?php echo $this->here; ?>">
		<input type="hidden" name="data[Lead][subject]" value="New Lead: Comparison Request">
		
		<fieldset>
			<label for="CompareHome">Home:</label>
			<input type="text" id="CompareHome" value="<?php echo $home['facility_name']; ?>" name="data[Lead][fields][home]" class="text ui-widget-content ui-corner-all">
		
			<label>First Name:</label>
			<input type="text" name="data[Lead][fields][first_name]" class="text ui-widget-content ui-corner-all">
			
			<label>Last Name:</label>
			<input type="text" name="data[Lead][fields][last_name]" class="text ui-widget-content ui-corner-all">
			
			<label>Email:</label>
			<input type="text" name="data[Lead][fields][email]" class="text ui-widget-content ui-corner-all">
			
			<label>Phone:</label>
			<input type="text" name="data[Lead][fields][phone]" class="text ui-widget-content ui-corner-all">
				
			<label>Enter this below:</label>
			<img height="24" width="50" src="/img/spammer.php" class="text ui-widget-content ui-corner-all">

			<label>&nbsp;</label><input type="text" name="hashtastic" class="text ui-widget-content ui-corner-all">
		
		</fieldset>
	</form>
</div>
