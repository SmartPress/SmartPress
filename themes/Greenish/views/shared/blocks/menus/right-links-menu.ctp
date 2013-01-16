<ul>
	<?php foreach($collection as $menu): ?>
		<?php 
		if (isset($menu['Menu']['options'])) $options = unserialize($menu['Menu']['options']);
		?>
	<li<?php if (isset($options['class'])): ?> class="<?php echo $options['class'] ?>"<?php endif; ?>><?php echo $this->Html->link(html_entity_decode($menu['Menu']['title']), $menu['Menu']['url']) ?></li>
	<?php endforeach; ?>
</ul>