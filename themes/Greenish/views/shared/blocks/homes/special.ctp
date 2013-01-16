<?php if (count($specials)): 
	$heading	= (isset($heading)) ? $heading : 'Specials';
?>
<div class="right-box-home">
	<h1><?php echo $heading; ?></h1>
	<ul>
	<?php foreach ($specials as $special): ?>
		<?php if ($special['Home']['special'] == 'va'): ?>
		<li>Veterans Special at an Assisted Living facility in <?php echo $special['Location']['name']; ?>, CA. View the <a href="/homes/view/<?php echo $special['Home']['id']; ?>">details</a>.</li>
		<?php endif; ?>
	<?php endforeach; ?>
	</ul>
</div>
<?php endif; ?>