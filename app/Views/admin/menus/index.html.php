<?php $this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Menus</h1>
	</div>
	
	<ul class="nav nav-tabs" id="menu-tabs">
		<?php
			$i = 0;
			 
			$this->menus->each(function($menu) {
			$i++; 
				?>
			<li><?php $this->linkTo($menu->title, "#menu-$i", ['data' => ['toggle' => 'tab']]); ?></li>
		<?php }); ?>	
	</ul>
	
	<div class="tab-content"> 
		<?php 
			$i = 0;
			
			$this->menus->each(function($menu) { 
			$i++;
				?>
			<div class="tab-pane<? if ($i == 1) echo ' active'; ?>" id="<?php echo "menu-$i"?>">
				<?php if ($menu->children->count() < 1) continue; ?>
				
				<ul class="sortable">
					<?php $this->render('sortable', ['menus' => $menu->children, 'level' => 1]); ?>
				</ul>
			</div>
		<?php }); ?>
	</div>
</section>