<?php if (count($breadcrumb)): $i = 0; ?>
<ul class="breadcrumb">
	<?php foreach($breadcrumb as $crumb): ?>
	<li>
		<?php if($crumb['Location']['parent_id'] == '0' || strlen($crumb['Location']['parent_id']) < 1): ?>
	<?php echo $crumb['Location']['name']; ?>
		<?php else: ?>
	<a href="/assisted-living-info/<?php echo $crumb[0]['path']; ?>"><?php echo $crumb['Location']['name']; ?></a>
		<?php endif; ?>
		<?php if ($i < count($breadcrumb) - 1) echo " &raquo; "; ?>
	</li>
	<?php $i++; endforeach; ?>
</ul>
<?php endif; ?>

<?php 
	if (count($breadcrumb) == 3) {
		$tagline = $location['name'] . ' ' . $breadcrumb[1]['Location']['name'];
	} else {
		$tagline = $location['name'] . ', ' . $parent['name'];
	}
?>

<h1>CarePlacement Specializes in</h1>
<h2>Assisted Living Facilities in <?php echo $tagline; ?></h2>

<div class="heading-image">
	<?php if (isset($attributes['thumbnail'])): ?>
	<img src="/<?php echo $attributes['thumbnail']['value']; ?>" border="0" width="170" />
	<?php else: ?>
	&nbsp;
	<?php endif; ?>
</div>


<?php if (isset($attributes['medium_image'])): ?>
<div class="inline-image left">
	<img src="/<?php echo $attributes['medium_image']['value']; ?>" border="0" />
</div>
<?php endif; ?>

<div class="locations quick-facts<?php if (!isset($attributes['medium_image'])): ?> no-margin<?php endif; ?>">
	<h3 style="width: 270px;"><?php echo $location['name'] ?> Care Community <span>Quick Facts:</span></h3>
	
	<ul class="no-style">
	<?php if(isset($attributes['total_providers'])): ?>
		<li><strong><?php echo $attributes['total_providers']['prompt']; ?></strong><span><?php echo sprintf('Over %s in a 5 mile radius', $attributes['total_providers']['value']); ?></span></li>
	<?php endif; ?>
	<?php if (count($attributes['price_range'])): ?>
		<!-- <li><strong>Price Range:</strong>
			<ul>  -->
		<?php foreach($attributes['price_range'] as $priceRange): ?>	
			<?php if (strlen($priceRange['value']) > 1): ?>
				<li><strong><?php echo $priceRange['prompt']?> Pricing:</strong><span><?php echo $priceRange['value'] ?></span></li>
			<?php endif; ?>
		<?php endforeach; ?>
		<!-- 	</ul>
		</li> -->
	<?php endif; ?>
	
	<?php foreach($attributes as $key => $attr): ?>
		<?php if (isset($attr['type']) 
					&& $attr['type'] != 'image' 
					&& strlen($attr['value']) > 0
					&& $key != 'lowprice'
					&& $key != 'highprice'
					&& $key != 'total_providers'): ?>
		<li><strong><?php echo $attr['prompt']; ?>:</strong><span><?php echo $attr['value'] ?></span></li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div>
<div class="locations description">
	<p>Assisted Living communities in and around <?php echo $location['name']; ?> vary greatly in size and in the services 
		and amenities they offer. You will find residential care facilities for the elderly (RCFE) from as few as six-bed 
		Board and Care homes to the large 400-bed Assisted Living communities. The price range for an Assisted Living facility 
		can be as low as <?php echo $attributes['lowprice']['value']; ?> for a modest shared room for someone with little care needs 
		to upwards of <?php echo $attributes['highprice']['value'] ?> for a private room in a specialized memory care or Alzheimer's community.</p>
	<p>With the many care options available in the <?php echo $location['name']; ?> and surrounding areas, we advise you 
		speak with an eldercare placement specialists who knows each facility and can walk you through the entire process. 
		Our services are provided to families free of charge so please call us at (619) 660-8814.</p> 
	<?php //echo $location['description'] ?>
</div>
