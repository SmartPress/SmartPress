<script type="text/javascript">
	window.parent.CKEDITOR.tools.callFunction(
		<?php echo $this->funcNumber; ?>,
		"<?php echo $this->images[0]; ?>",
		{
			success	: true,
			message	: "<?php echo $this->message; ?>"
		});
</script>
