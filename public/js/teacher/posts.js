var ViewUrlMask = "http:\u002f\u002f10.63.7.189\u002fop\u002fview.aspx?src=WACFILEURL";
var EmbedCodeMask = "\u003ciframe src=\u0027http:\u002f\u002f10.63.7.189\u002fop\u002fembed.aspx?src=WACFILEURL\u0027 width=\u0027800px\u0027 height=\u0027600px\u0027 frameborder=\u00270\u0027\u003eThis is an embedded \u003ca target=\u0027_blank\u0027 href=\u0027http:\u002f\u002foffice.com\u0027\u003eMicrosoft Office\u003c\u002fa\u003e document, powered by \u003ca target=\u0027_blank\u0027 href=\u0027http:\u002f\u002foffice.com\u002fwebapps\u0027\u003eOffice Web Apps\u003c\u002fa\u003e.\u003c\u002fiframe\u003e";
var UrlPlaceholder = "WACFILEURL";
var OriginalUrlElementId = "OriginalUrl";
var GeneratedViewUrlElementId = "GeneratedViewUrl";
var GeneratedEmbedCodeElementId = "GeneratedEmbedCode";
var CopyViewUrlLinkId = "CopyViewUrl";
var CopyEmbedCodeLinkId = "CopyEmbedCode";
$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

    $('.writing-type-btn').on('click', function (e) {
        $("#selected-writing-types-id").val($(this).val());
        $(".writing-type-btn").removeClass("btn-success");
        $(this).addClass("btn-success");
        refreshPostList();
    });

    refreshPostList();
    $(document)
	   .on('click', '.post-btn', function (e) {
        // alert($(this).attr("value"));
            if ($(this).attr("value")) {

                $.ajax({
                    type: "POST",
                    url: '/teacher/getOnePostById',
                    data: {posts_id: $(this).attr("value")},
                    success: function( data ) {
                        //console.log(data);
                        if ("false" == data) {

                        } else {
                            $('#post-show').attr("src", data.storage_name);
                            $("#myModalLabel").html("您"+data.writingDate+" "+data.writingType+" 打卡作品");
                            $('#post-download').attr("href", data.filePath);
                        }
                    }
                });
                $("#myPostModal").modal();
                // var postsId = $(this).attr("value").split(',')[0]; 
                // var filePath = $(this).attr("value").split(',')[1]; 
                // var filetype = $(this).attr("value").split(',')[2]; 
                // var previewPath = $(this).attr("value").split(',')[3]; 

                // e.preventDefault();
                // $.ajax({
                //     type: "POST",
                //     url: '/teacher/getPostRate',
                //     data: {posts_id : postsId},
                //     success: function( data ) {
                //         //console.log(data);
                //         var rateStr = "暂无等第";
                //         if ("false" != data) {
                //            rateStr = "等第：" + data;
                //         }
                //         $('#rate-label-'+postsId).text(rateStr);
                //     }
                // });

                // $.ajax({
                //     type: "POST",
                //     url: '/teacher/getCommentByPostsId',
                //     data: {posts_id : postsId},
                //     success: function( data ) {
                //         var conmmentStr = "暂无评语";
                //         if ("false" != data) {
                //             conmmentStr = "老师评语：" + JSON.parse(data)['content'];
                //         // console.log(JSON.parse(data));
                //         }
                //         $('#post-comment-'+postsId).text(conmmentStr);
                //     }
                // });

                $('#posts-id').val($(this).attr("value"));
                // if ("doc" == filetype) {
                    // console.log(OnCreateUrl(previewPath));
                    // console.log((previewPath));
                    // $('#doc-preview-'+postsId).html(OnCreateUrl(previewPath));
                // } else if ("img" == filetype) {
                    // $('#post-show-'+postsId).attr("src", filePath);
                // }
                // $('#post-show-'+postsId).attr("src", filePath);
                // $('#post-show-'+postsId).attr("href", filePath);
                //$('#myModal').modal();
            }
            
        });
});

function refreshPostList() {
    // alert($("#term-selection").val());
    $.ajax({
        type: "POST",
        url: '/teacher/getPosts',
        data: {writingTypesId: $("#selected-writing-types-id").val()},
        success: function( data ) {
            $("#posts-list").html(data);
            // console.log(data);
        }
    });
}

function OnCreateUrl(data)
{
    // var originalUrl = document.getElementById(OriginalUrlElementId).value;
    var originalUrl = data;

    // var generatedViewUrl = ViewUrlMask.replace(UrlPlaceholder, encodeURIComponent(originalUrl));
    var generatedEmbedCode = EmbedCodeMask.replace(UrlPlaceholder, encodeURIComponent(originalUrl));
    return generatedEmbedCode;
    // document.getElementById(GeneratedViewUrlElementId).value = generatedViewUrl;
    // document.getElementById(GeneratedEmbedCodeElementId).value = generatedEmbedCode;
}