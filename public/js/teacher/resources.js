
$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	$(".penItem").on('click', function (e) {
		var urlPrefix = $("#base-url").val() + "/downloads/";
		$("#video-label").html($(this).attr("filename"));
		var resourceUrl = urlPrefix + $(this).attr("filename") + ".mp4";
		videojs("resources-player",{}).ready(function(){
		    var myPlayer = this;

		    myPlayer.src(resourceUrl);

		    myPlayer.play();
		});
	});

	$(".chalkItem").on('click', function (e) {
		var urlPrefix = $("#base-url").val() + "/downloads/";
		$("#video-label").html($(this).attr("filename"));
		var resourceUrl = urlPrefix + $(this).attr("filename") + ".mp4";
		videojs("resources-player",{}).ready(function(){
		    var myPlayer = this;

		    myPlayer.src(resourceUrl);

		    myPlayer.play();
		});
	});

	$(".brushItem").on('click', function (e) {
		var urlPrefix = $("#base-url").val() + "/downloads/";
		$("#video-label").html($(this).attr("filename"));
		var resourceUrl = urlPrefix + $(this).attr("filename") + ".mp4";
		videojs("resources-player",{}).ready(function(){
		    var myPlayer = this;

		    myPlayer.src(resourceUrl);

		    myPlayer.play();
		});
	});
});