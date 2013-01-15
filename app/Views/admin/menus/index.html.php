<?php //$this->render('top-nav'); ?>

<section id="grid">
	<div class="page-header">
		<h1>Menus</h1>
	</div>
	
	<div class="row-fluid">
		<div class="span7">
			<ul class="nav nav-tabs" id="menu-tabs">
				<?php
					$i = 0;
					 
					$this->menus->each(function($menu) use (&$i) {
					$i++; 
						?>
					<li><?php $this->linkTo($menu->title, "#menu-$i", ['data' => ['toggle' => 'tab']]); ?></li>
				<?php }); ?>	
			</ul>
			
			<div class="tab-content"> 
				<?php 
					$i = 0;
					
					$this->menus->each(function($menu) use (&$i) { 
					$i++;
						?>
					<div class="tab-pane<? if ($i == 1) echo ' active'; ?>" id="<?php echo "menu-$i"?>">
						<?php if (!isset($menu->children) || $menu->children->count() < 1) return; ?>
						
						<ul class="sortable">
							<?php $this->render('sortable', ['menus' => $menu->children, 'level' => 1]); ?>
						</ul>
					</div>
				<?php }); ?>
			</div>
		</div>
		<div class="span4 offset1">
			<div class="well">
				<?php $this->render('form'); ?>
			</div>
		</div>
	</div>
	
</section>
