<?php foreach($testimonials as $testimonial): ?>
<div id="testimonials" style="margin-top: 10px;">
	<?php echo $this->Html->image('hd-testimonials.gif', array(
		'alt' => 'Testimonials',
		'width' => '158',
		'height' => '33'
	)) ?>
	<div class="img">
		<div class="sprite sprite-testi-sidebar-top"></div>
		<img src="/img/testimonials/<?php echo $testimonial['Testimonial']['image'] ?>" width="258" class="testi-client" alt="" />
		<div class="sprite sprite-testi-sidebar-bottom"></div>
	</div>
	<p><?php echo $testimonial['Testimonial']['testimonial'] ?> 
		<span>&#126; <?php echo $testimonial['Testimonial']['name'] ?> &#126;</span></p>
</div>
<div id="readmore">
	<p><a href="/testimonials">Read More Testimonials</a></p>
</div>
<?php endforeach; ?>