<?php if (count($locations)): ?>
<div id="locations-cont" class="foot-content">
	<h1>Start Your Search:</h1>
	<ul class="locations-menu">
	<?php foreach ($locations as $location): ?>
		<li><a href="/assisted-living-info/<?php echo $location[0]['path'] ?>"><?php echo $location['Location']['name'] ?></a></li>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>