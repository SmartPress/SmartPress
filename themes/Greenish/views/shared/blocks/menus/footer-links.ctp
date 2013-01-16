<div class="footer-links">
	<ul>
		<?php $i = 1; $total = count($collection); ?>
		<?php foreach($collection as $menu): ?>
			<?php 
				if (isset($menu['Menu']['options'])) $options = unserialize($menu['Menu']['options']);
				$class = (isset($options['class'])) ? $options['class'] . " " : "";
				if ($i == 1) $class = $class . "first";
				elseif ($i == $total) $class = $class . "last";
			?>
		<li class="<?php echo $class ?>"><?php echo $this->Html->link(html_entity_decode($menu['Menu']['title']), $menu['Menu']['url']) ?></li>
		<?php $i++; endforeach; ?>
	</ul>
</div>