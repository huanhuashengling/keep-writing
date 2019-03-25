
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
		addResourceLog($(this).attr("resourcesId"), $(this).attr("writingTypesId"));
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
		addResourceLog($(this).attr("resourcesId"), $(this).attr("writingTypesId"));
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
		addResourceLog($(this).attr("resourcesId"), $(this).attr("writingTypesId"));
	});
});

function addResourceLog(resourcesId, writingTypesId)
{
	$.ajax({
	        type: "POST",
	        url: '/teacher/addResourceLog',
	        data: {resourcesId: resourcesId, writingTypesId: writingTypesId},
	        success: function( data ) {
	            console.log(data);
	            if ("false" == data) {

	            } else {
	            }
	        }
	    });
}

