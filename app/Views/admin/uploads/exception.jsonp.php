<script type="text/javascript">
	window.parent.CKEDITOR.tools.callFunction(
		<?php echo $this->funcNumber; ?>,
		"",
		{
			success	: false,
			message	: "<?php echo $this->exception->getMessage(); ?>",
			errors	: <?php echo json_encode($this->errors); ?>
		});
</script>