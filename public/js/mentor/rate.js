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
        $(".writing-type-btn").removeClass("btn-info");
        $(this).addClass("btn-info");
        refreshTeacherList();
    });

    $('#input-1').rating({
        step: 1,
        starCaptions: {1: '1星', 2: '2星', 3: '3星', 4: '4星', 5: '5星'},
        starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'}
    });

    $("#input-1").rating().on("rating:change", function(event, value, caption) {
        $.ajax({
            type: "POST",
            url: '/mentor/rateOnePost',
            data: {rate: value, postsId:  $("#selected-posts-id").val()},
            success: function( data ) {
                // console.log(data);
                if ("false" == data) {
                    alert("提交评价失败！")
                } else {
                    refreshTeacherList();
                    refreshTeacherPostList();
                }
            }
        });
    });

    $(document)
       .on('click', '.teacher-btn', function (e) {
            $("#selected-teachers-id").val($(this).val());
            $(".teacher-btn").removeClass("btn-info");
            $(this).addClass("btn-info");
            refreshTeacherPostList();
        })
        .on('click', '.post-btn', function (e) {
            var postsId = $(this).attr("value");
            $("#selected-posts-id").val($(this).attr("value"));
            $('#post-show').attr("src", $(this).attr("thumbnail"));
            $('#input-1').rating('update', $(this).attr("rate"));
            $.ajax({
                type: "POST",
                url: '/mentor/getPostRate',
                data: {posts_id: postsId},
                success: function( data ) {
                    if ("false" == data) {

                    } else {
                        $("div[name='level-btn-group'] label").each(function(){
                        if (data == $(this).children().attr("value")) {
                            $(this).addClass("active");
                            }
                        });
                    }
                }
            });
            $('#rateModal').modal();
        });
});

function refreshTeacherPostList() {
    $.ajax({
        type: "POST",
        url: '/mentor/getPostsByWritingTypeAndTeachersId',
        data: {writingTypesId: $("#selected-writing-types-id").val(), teachersId: $("#selected-teachers-id").val()},
        success: function( data ) {
            $("#post-list").html(data);
        }
    });
}

function refreshTeacherList() {
    $.ajax({
            type: "POST",
            url: '/mentor/getPostsCountByWritingType',
            data: {writingTypesId: $("#selected-writing-types-id").val()},
            success: function( data ) {
                $("#teacher-list").html(data);
            }
        });
}


