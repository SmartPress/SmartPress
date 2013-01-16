<?php if (count($this->posts) > 0): ?>
	<?php foreach($this->posts as $post): ?>
		<article>
			<header>
				<h2><?php $this->linkTo($post->title, $this->post_path($post->slug)); ?></h2>
			</header>
			<?php echo $post->summary; ?>
		</article>
	<?php endforeach; ?>
<?php endif; ?>
