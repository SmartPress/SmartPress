<?php 
$default_base	= 'img/homes/defaults/';
$default_imgs	= array(
	'86492748_8-thumb.jpg',
	'88295786_8-thumb.jpg',
	'88295794_8-thumb.jpg',
	'88461057_8-thumb.jpg',
	'couple-waiting-for-sunset-thumb.jpg',
	'gen-of-happy-women-thumb.jpg',
	'grandma-and-me-thumb.jpg',
	'happyoldercouple-thumb.jpg',
	'happy-older-couple-thumb.jpg',
	'happy-senior-man-thumb.jpg',
	'mom-daug-photo-thumb.jpg',
	'old-and-young-thumb.jpg',
	'older_couple-thumb.jpg',
	'outdoor-portrait-thumb.jpg'
);
shuffle($default_imgs);
?>
<?php if (count($homes)): ?>
<div id="homes-cont">
	<div class="home-pagi-header ui-widget-header ui-corner-all">
		<div>Page <?php echo $Pagination['current_page']; ?> of <?php echo $Pagination['total_pages']?>. <?php echo $Pagination['total']; ?> total listings.</div>
	</div>
	<div class="homes-list">
		<?php $n = 1; ?>
		<?php foreach($homes as $home): ?>
			<?php 
				$class = null;
				if ($n++ % 2 == 0) {
					$class = ' class="alt"';
				}
			?>
		<div<?php echo $class; ?>>
			<?php 
				$default_img	= array_pop($default_imgs);
				if (!isset($home['HomeAttributeValue']['thumbnail']['value'])) {
					$home['HomeAttributeValue']['thumbnail']	= array( 'value' => $default_base . $default_img );
				} elseif (!strlen($home['HomeAttributeValue']['thumbnail']['value'])) {
					$home['HomeAttributeValue']['thumbnail']['value']	= $default_base . $default_img;
				}
			
				$attr =& $home['HomeAttributeValue']; 
				$location	=& $home['Location'];
			
				if (isset($attr['type']) && is_array($attr['type'])) { 
					foreach ($attr['type'] as $key => $current) {
						if (!isset($current['value']) || strlen($current['value']) < 1)
							unset($attr['type'][$key]);
					}	
				}
			?>	
			<?php if (isset($attr['thumbnail'])): ?>
			<div class="thumbnail"><img src="/<?php echo $attr['thumbnail']['value']; ?>" alt="" width="150" /></div>
			<?php endif; ?>
	
			<!-- <h2><?php echo $home['Home']['facility_name']; ?></h2> 
			<div class="city"><?php echo $location['name']; ?></div> -->
			<h2><?php echo $location['name']?>, CA</h2>
			<div class="buttons">
				<?php if (isset($home['Home']['special']) && strlen($home['Home']['special']) > 0): ?>
					<span><a href="/homes-list/<?php echo $home['Home']['slug']?>" class="button">Special!</a></span>
				<?php else: ?>
					<span><a href="/homes-list/<?php echo $home['Home']['slug']; ?>" class="button">Details</a></span> 
				<?php endif; ?>
			</div>
			<ul>	
				<li><span>City: </span><?php echo $location['name']; ?></li>
				<?php if(isset($attr['beds']['value']) && strlen($attr['beds']['value'])): ?>
				<li><span># Beds: </span><?php echo $attr['beds']['value']; ?></li>
				<?php endif; ?>
			
				<?php if ((isset($attr['price_range']['low']['value']) && strlen($attr['price_range']['low']['value']) > 0) &&
							(isset($attr['price_range']['high']['value']) && strlen($attr['price_range']['high']['value']) > 0)): ?>
				<li><span>Price Range: </span>$<?php echo number_format(intval($attr['price_range']['low']['value']), 2) ?> - $<?php echo number_format(intval($attr['price_range']['high']['value']), 2) ?></li>
				<?php endif; ?>
			
				<?php if (isset($att['type']) && count($attr['type'])): ?>
				<li><span>Care: </span><?php $i = 0; foreach($attr['type'] as $type){ echo (count($attr['type'])-1 > $i) ? $type['prompt'] . ", " : $type['prompt']; $i++; } ?></li>
				<?php endif; ?>
			</ul>
		</div>
	<?php endforeach; ?>
	</div>
	
	<div class="home-pagi-cont">
		<span class="home-pagination ui-widget-header ui-corner-all">
			<?php
				$base_uri	= "/assisted-living-info/" . $breadcrumb[count($breadcrumb) - 1][0]['path'] . "/"; 
				if (isset($Pagination['prev_page'])): 
			?>
			<a href="<?php echo $base_uri . "page:{$Pagination['prev_page']}"; ?>">&laquo;</a>
			<?php endif; ?>
			
			<?php foreach ($Pagination['pages'] as $page): ?>
				<?php if ($Pagination['current_page'] != $page): ?>
			<a href="<?php echo $base_uri . "page:$page"; ?>"><?php echo $page; ?></a>
				<?php else: ?>
			<a class="active"><?php echo $page; ?></a>
				<?php endif; ?>
			<?php endforeach; ?>
			
			<?php if (isset($Pagination['next_page'])): ?>
			<a href="<?php echo $base_uri . "page:{$Pagination['next_page']}"; ?>">&raquo;</a>
			<?php endif; ?>
		</span>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$('a.button').button();
	$('span.home-pagination').buttonset();
	$('span.home-pagination a.active').button({ disabled: true });
});
</script>
<?php endif; ?>