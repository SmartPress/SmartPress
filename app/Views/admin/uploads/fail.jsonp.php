<script type="text/javascript">
	window.parent.CKEDITOR.tools.callFunction(
		<?php echo $this->funcNumber; ?>,
		"",
		{
			success	: false,
			message	: "<?php echo $this->message; ?>",
			errors	: "<?php echo $this->errors; ?>"
		});
</script>