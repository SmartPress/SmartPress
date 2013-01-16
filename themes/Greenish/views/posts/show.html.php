<?php echo $this->post->html; ?>

<?php if ($this->post->comments && $this->post->comments->count() > 0): ?>
	<section>
	<?php // debug($this->post->comments); ?>
	<?php $this->post->comments->each(function($comment) { ?>
		<article>
			<?php echo $comment->author; ?>
			<?php echo $comment->created_at; ?>
			<div class="thumbnail">
				<?php echo $comment->avatar; ?>
			</div>
			<div role="comment">
				<?php echo $comment->content; ?>
			</div>
		</article>
	<?php }); ?>
	</section>
<?php endif; ?>

<?php $this->render('comment_form'); ?>
<?php //echo $this->Care->testimonial(); ?>
