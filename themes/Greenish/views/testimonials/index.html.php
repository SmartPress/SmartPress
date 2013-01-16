<div class="testimonials index">
	<h2><?php __('Testimonials');?></h2>
	<!-- <table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name');?></th>
			<th><?php echo $this->Paginator->sort('testimonial');?></th>
	</tr>  -->
	<?php
	$i = 0;
	foreach ($testimonials as $testimonial):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = 'altrow';
		}
	?>
	<div class="item <?php echo $class;?>">
		<p><?php echo $testimonial['Testimonial']['testimonial'] ?>
		<span>&#126; <?php echo $testimonial['Testimonial']['name']; ?> &#126;</span></p>
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
	 | 	<?php echo $this->Paginator->numbers();?>
 |
		<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
	</div>
</div>
