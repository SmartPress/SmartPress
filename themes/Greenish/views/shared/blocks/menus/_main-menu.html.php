<ul class="menu-top-nav">
	<?php $i = 1; $total = count($items); ?>
	<?php foreach($items as $item): ?>
		<?php 
			/*if (isset($menu['Menu']['options'])) $options = unserialize($menu['Menu']['options']);
			$class = (isset($options['class'])) ? $options['class'] . " " : "";
			if ($i == 1) $class = $class . "first";
			elseif ($i == $total) $class = $class . "last";*/
		?>
	<li class="<?php //echo $class ?>"><?php $this->linkTo($item['title'], $item['url']); ?></li>
	<?php if ($i < $total): ?>
	<li class="seperator"></li>
	<?php endif; ?>
	<?php $i++; endforeach; ?>
</ul>
