<?php if (count($results)): ?>
	<h2>Results for Search</h2>
	<?php foreach ($results['result'] as $result): ?>
		<div class="result">
			<a class="title" href="<?php echo $result['url']; ?>"><?php echo $result['title']; ?></a>
			<p class="description"><?php echo $result['description']; ?></p>
		</div>
	<?php endforeach; ?>
	
	<?php if (count($offsets)): ?>
		<div class="pagination">
			<ul>
			<?php if ($current_offset > 0 && count($offsets) > 1): ?>
				<li><a href="/search/query?q=<?php echo $encoded_query; ?>&offset=<?php echo $current_offset - $limit; ?>">Prev</a></li>
			<?php endif; ?>
			<?php foreach ($offsets as $pageNum => $curOffset): ?>
				<li>
				<?php if ($curOffset == $current_offset): ?>
					<span><?php echo $pageNum ?></span>
				<?php else: ?>
					<a href="/search/query?q=<?php echo $encoded_query; ?>&offset=<?php echo $curOffset; ?>"><?php echo $pageNum; ?></a>
				<?php endif; ?>
				</li>
			<?php endforeach; ?>
			<?php if ($current_offset < (count($offsets) * $limit) && count($offsets) > 1): ?>
				<li><a href="/search/query?q=<?php echo $encoded_query; ?>&offset=<?php echo $current_offset + $limit; ?>">Next</a></li>
			<?php endif; ?>
			</ul>
		</div>
	<?php endif; ?>
<?php endif; ?>
