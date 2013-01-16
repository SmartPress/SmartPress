<div class="right-box-home search-form">
	<div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false" data-action="recommend" data-font="verdana"></div>
	<!-- Place this tag where you want the +1 button to render -->
	<div class="plus-one">
		<!-- Place this tag where you want the +1 button to render -->
		<g:plusone size="medium" annotation="inline" width="300"></g:plusone>
		
		<!-- Place this render call where appropriate -->
		<script type="text/javascript">
		  (function() {
		    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		    po.src = 'https://apis.google.com/js/plusone.js';
		    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
	</div>
	
	<h2>City, Zip, or Facilty Search</h2>
	<form method="get" action="/homes/search">
		<div class="input text">
			<input type="text" name="q" id="q" value="" />
		</div>
		<div class="input submit">
			<input type="submit" value="GO" />
		</div>
	</form>
</div>