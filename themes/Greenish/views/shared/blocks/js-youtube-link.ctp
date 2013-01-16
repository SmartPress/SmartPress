<script type="text/javascript" src="/js/swfobject.js"></script>
<script type="text/javascript">
var ytplayer= null;
var params 	= { allowScriptAccess: "always" };
var atts 	= { id: "you-tube-embed" };

swfobject.embedSWF(
	"http://www.youtube.com/v/QfEtb7hIjWU?enablejsapi=1&version=3&playerapiid=ytplayer",
	"no-flash",
	"480",
	"385", 
	"8",
null,null,params,atts);

$(document).ready(function() {
	$("#header-play").fancybox({
		"onCleanup" : function() {
			if (ytplayer != null && typeof(ytplayer.stopVideo) == "function") {
				ytplayer.stopVideo();
				_gaq.push(['_trackEvent', 'HomepageVideo','Close']);
			}
		},
		"onComplete" : function() {
			if (ytplayer != null && typeof(ytplayer.playVideo) == "function") {
				ytplayer.playVideo();
			}
		}
	});
	$("#header-play").click(function() {
		_gaq.push(['_trackEvent', 'HomepageVideo','Opened']);
	});
});

function onYouTubePlayerReady(playerId) {
	ytplayer = document.getElementById("you-tube-embed");	

	if (typeof(ytplayer.addEventListener) == "function") {
		ytplayer.addEventListener('onStateChange', 'onYouTubePlayerStateChange'); 
	}
}

function onYouTubePlayerStateChange(newState) {
	switch(newState) {
		case 0:
			_gaq.push(['_trackEvent', 'HomepageVideo','Ended']);
			break;

		case 1:
			_gaq.push(['_trackEvent', 'HomepageVideo','Playing']);
			break;

		case 2:
			_gaq.push(['_trackEvent', 'HomepageVideo','Paused','Paused at', ytplayer.getCurrentTime()]);
			break;
	}
}
</script>