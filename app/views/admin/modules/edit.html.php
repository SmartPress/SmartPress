<h1>Editing module</h1>

<?php $this->render("form"); ?>

<?php $this->linkTo('<i class="icon-chevron-left"></i> Back', $this->admin_modules_url(), [ 'class' => 'btn' ]); ?>

<?php $this->linkTo('Show', $this->admin_module_path($this->module->id), [ 'class' => 'btn' ]); ?> 