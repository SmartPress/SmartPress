<div class="pagination">
	<ul>
		<?php if (empty($this->pagination['prevPage'])): ?>
			<li class="disabled"><span>Prev</span></li>
		<?php else: ?>
			<li><?php echo $this->linkTo('First', $this->admin_pages_url([
				'page'	=> 1,
				'sort'	=> $this->param('sort'),
				'sort_type' => $this->param('sort_type')
			])); ?></li>
			<li><?php echo $this->linkTo('Prev', $this->admin_pages_url([
				'page'	=> $this->pagination['prevPage'],
				'sort'	=> $this->param('sort'),
				'sort_type' => $this->param('sort_type')
			])); ?></li>
		<?php endif; ?>

		<?php for ($i = $this->pagination['lower']; $i <= $this->pagination['upper']; $i++): ?>
			<li<?php echo ($i == $this->pagination['currentPage']) ? ' class="active"' : ''; ?>>
				<?php echo $this->linkTo($i, $this->admin_pages_url([
						'page' => $i,
						'sort'	=> $this->param('sort'),
						'sort_type' => $this->param('sort_type')
					])); ?>
			</li>
		<?php endfor; ?>

		<?php if (empty($this->pagination['nextPage'])): ?>
			<li class="disabled"><span>Next</span></li>
		<?php else: ?>
			<li><?php echo $this->linkTo('Next', $this->admin_pages_url([
				'page'	=> $this->pagination['nextPage'],
				'sort'	=> $this->param('sort'),
				'sort_type' => $this->param('sort_type')
			])); ?></li>
			<li><?php echo $this->linkTo('Last', $this->admin_pages_url([
				'page'	=> $this->pagination['maxPages'],
				'sort'	=> $this->param('sort'),
				'sort_type' => $this->param('sort_type')
			])); ?></li>
		<?php endif; ?>
	</ul>
</div>
