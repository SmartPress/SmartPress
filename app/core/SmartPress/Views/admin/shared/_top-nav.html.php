<div class="navbar navbar-fixed-top navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<a class="brand" href="#">SmartPress</a>
			<ul class="nav">
				<li><?php echo $this->linkTo('Pages', $this->admin_pages_url()); ?></li>
				<li><?php echo $this->linkTo('Posts', $this->admin_posts_url()); ?></li>
				<li><?php echo $this->linkTo('Settings', $this->admin_configs_url()); ?></li>
			</ul>
			<ul class="nav pull-right">
				<li><?php echo $this->linkTo('Log Out', $this->admin_sign_out()); ?></li>
			</ul> 
		</div>
	</div>
</div>
