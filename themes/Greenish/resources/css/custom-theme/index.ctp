<div class="posts index">
	<h1><?php __(($category) ? $category['name'] : 'Articles');?></h1>
	<?php
	$i = 0;
	foreach ($posts as $post):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<div <?php echo $class;?>>
		<h2><?php echo $post['Post']['title']; ?>&nbsp;</h2>
		<div class="summary">
			<?php //echo $this->AppText->pluralize($post['Post']['type']); ?>
			<?php echo $this->AppText->summary($post['Post']['content'], 500); ?>&nbsp;
			<div class="readmore"><?php echo $this->Html->link("Read More", array('controller' => $this->AppText->pluralize($post['Post']['type']), 'action' => 'view', $post['Post']['slug'])); ?></div>
		</div>
	</div>
	<?php endforeach; ?>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>	</p>

	<div class="paging">
		<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
		<?php echo $this->Paginator->numbers();?>
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>