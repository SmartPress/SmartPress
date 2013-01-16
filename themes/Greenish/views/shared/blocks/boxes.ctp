<div id="boxes">
	<div class="box-cont">
	  	<div class="content-box">
           	<div class="image"><img src="/theme/greenish/img/box1-graphic.png" width="192" height="88" alt="Fast Answers" /></div>
    	    <h4 class="large">to your questions about local elder care facilities</h4>
            <a href="/contact-us">Click Here</a>
        </div>
        <div class="box-btm">
          	<img src="/theme/greenish/img/box1-image-bw.jpg" width="191" height="88" alt="Get your assisted living questions answered" />
        </div>
    </div>
    <div class="box-cont">
        <div class="content-box">
            <div class="image"><img src="/theme/greenish/img/box2-graphic.png" width="196" height="90" alt="Easy Assessment" /></div>
            <h4>for a personalized list of options</h4>
            <a href="/assess-your-needs">Click Here</a>
        </div>
   		<div class="box-btm">
        	<img src="/theme/greenish/img/box2-image-bw.jpg" width="191" height="88" alt="Expert advice for board and cares" />
    	</div>
    </div>
    <!-- <div class="box-cont">
        <div class="content-box">
            <div class="image"><img src="/theme/greenish/img/box3-graphic.png" width="196" height="90" alt="Free Search" /></div>
            <h4>for Senior Living &amp; Care Options</h4>
            <a href="/assess-your-needs">Click Here</a>
        </div>
   		<div class="box-btm">
        	<img src="/theme/greenish/img/box3-image-bw.jpg" width="191" height="88" alt="Free local nursing home finder" />
    	</div>
    </div>  -->
</div>
<script type="text/javascript">
$('.box-cont').hover(function(e) {
    var target    = $('.box-btm img', this);
    var src    = target.attr('src');
    src    = src.replace(/bw/i, 'color');
    target.attr('src', src);
},function(e) {
    var target    = $('.box-btm img', this);
    var src    = target.attr('src');
    src    = src.replace(/color/i, 'bw');
    target.attr('src', src);
});
</script>