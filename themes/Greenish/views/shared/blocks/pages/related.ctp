<div class="related-links">
	<h4>Related Reading</h4>
	<?php $i = 0; ?>
	<?php foreach($categories as $ctgy): ?>
	<div<?php if ($i % 2 == 0) echo ' style="margin-right: 15px;"'; ?>>
		<h3><?php echo $ctgy['Category']['name']; ?></h3>
		<ul>
			<?php foreach($ctgy['posts'] as $post): ?>
				<?php 
					$baseLink	= '/';
					switch($post['Post']['type']) {
						case 'post':
							$baseLink .= 'articles/';
					}
				?>
			<li><a href="<?php echo $baseLink . $post['Post']['slug'] ?>"><?php echo $post['Post']['title']; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php $i++; endforeach; ?>
</div>