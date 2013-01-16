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

<?php if (isset($attributes['medium_image'])): ?>
<div class="inline-image left">
	<img src="/<?php echo $attributes['medium_image']['value']; ?>" border="0" />
</div>
<?php endif; ?>

<div class="locations quick-facts<?php if (!isset($attributes['medium_image'])): ?> no-margin<?php endif; ?>">
	<h3><?php echo $location['name'] ?> Care Community <span>Quick Facts:</span></h3>
	
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
	<?php echo $location['description'] ?>
</div>
<?php //fprint_r($attributes) ?>
