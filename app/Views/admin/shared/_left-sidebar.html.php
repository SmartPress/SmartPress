<div class="accordion-menu" id="content-menu">
	<ul class="nav nav-accordion">
		<li>
			<div class="btn-group accordion-heading">
				<?php $this->linkTo('<i class="icon-file"></i> Posts', $this->admin_posts_url(), array( 'class' => 'btn btn-head' )); ?>
				<button class="btn" data-toggle="collapse" data-target="#posts-menu"><span class="caret"></span></button>
			</div>
			<ul id="posts-menu" class="collapse in nav nav-list">
				<li><?php $this->linkTo('New', $this->new_admin_post_path()); ?></li>
				<li><?php $this->linkTo('Posts', $this->admin_posts_url()); ?></li>
				<li class="divider"></li>
				<li><?php $this->linkTo('<i class="icon-tags"></i> Categories', $this->admin_categories_url()); ?></li>
				<li><?php $this->linkTo('<i class="icon-th-list"></i> Custom Fields', $this->admin_post_custom_fields_url()); ?></li>
			</ul>
		</li>
		<li>
			<div class="btn-group accordion-heading">
				<?php $this->linkTo('<i class="icon-book"></i> Pages', $this->admin_pages_url(), array( 'class' => 'btn btn-head' )); ?>
				<button class="btn" data-toggle="collapse" data-target="#pages-menu"><span class="caret"></span></button>
			</div>
			<ul id="pages-menu" class="collapse in nav nav-list">
				<li><?php $this->linkTo('New', $this->new_admin_page_path()); ?></li>
				<li><?php $this->linkTo('Pages', $this->admin_pages_url()); ?></li>
			</ul>
		</li>
	</ul>
	
	<ul class="nav nav-accordion">
		<li>
			<div class="btn-group accordion-heading">
				<?php $this->linkTo('<i class="icon-cog"></i> Settings', $this->admin_configs_url(), array( 'class' => 'btn btn-head' )); ?>
				<button class="btn" data-toggle="collapse" data-target="#settings-menu"><span class="caret"></span></button>
			</div>
			<ul id="settings-menu" class="collapse in nav nav-list">
				<li><?php $this->linkTo('New', $this->new_admin_config_path()); ?></li>
				<li><?php $this->linkTo('Settings', $this->admin_configs_url()); ?></li>
				<li class="divider"></li>
				<li><?php $this->linkTo("Users", $this->admin_users_url()); ?></li>
				<li><?php $this->linkTo("Groups", $this->admin_groups_url()); ?></li>
				<li class="divider"></li>
				<li><?php $this->linkTo('Theme', $this->edit_admin_config_path('look')); ?></li>
				<li><?php $this->linkTo('Blog', $this->edit_admin_config_path('blog')); ?></li>
				<?php if (!empty($this->menus['settings'])): ?>
				<li class="divider"></li>
					<?php foreach ($this->menus['settings'] as $item): ?>
					<li><?php $this->linkTo($item['label'], $item['url']); ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</li>
		<li>
			<div class="btn-group accordion-heading">
				<?php $this->linkTo('<i class="icon-th-list"></i> Modules', $this->admin_modules_url(), array( 'class' => 'btn btn-head' )); ?>
				<button class="btn" data-toggle="collapse" data-target="#modules-menu"><span class="caret"></span></button>
			</div>
			<ul id="modules-menu" class="collapse in nav nav-list">
				<li><?php $this->linkTo('Upload', $this->new_admin_module_path()); ?></li>
				<li><?php $this->linkTo('Modules', $this->admin_modules_url()); ?></li>
				<?php if (!empty($this->menus['modules'])): ?>
				<li class="divider"></li>
					<?php foreach ($this->menus['modules'] as $item): ?>
					<li><?php $this->linkTo($item['label'], $item['url']); ?></li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</li>
	</ul>
</div>

