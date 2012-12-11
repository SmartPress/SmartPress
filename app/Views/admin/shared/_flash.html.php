<?php if ($this->session->has('flash')): ?>
	<?php foreach ($this->session->get('flash') as $type => $message): ?>
		<div class="alert alert-<?php echo $type; ?> fade in">
			<button type="button" class="close" data-dismiss="alert">x</button>
			<strong><?php echo ucwords($type); ?></strong> - <?php echo $message; ?>
		</div>
	<?php endforeach; ?>
<?php endif; ?>