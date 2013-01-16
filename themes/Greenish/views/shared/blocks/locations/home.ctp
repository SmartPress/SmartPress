<?php if (count($locations)): ?>
<div class="foot-locations">
	<h3><?php echo $parent['Location']['name']; ?>:</h3>
	<ul class="locations-menu">
	<?php foreach ($locations as $location): ?>
		<li><a href="/assisted-living-info/<?php echo $location[0]['path'] ?>"><?php echo $location['Location']['name'] ?></a></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>