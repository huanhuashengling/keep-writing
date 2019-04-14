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
        refreshWritingDateList();
        refreshPostList();
        $("#success-alert").addClass("hidden");
    });

    $('#input-1').rating({
        step: 1,
        starCaptions: {1: '1星', 2: '2星', 3: '3星', 4: '4星', 5: '5星'},
        starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'}
    });

    $("#input-1").rating().on("rating:change", function(event, value, caption) {
        $("#success-alert").addClass("hidden");
        $.ajax({
            type: "POST",
            url: '/mentor/rateOnePost',
            data: {rate: value, postsId:  $("#selected-posts-id").val()},
            success: function( data ) {
                // console.log(data);
                if ("false" == data) {
                    alert("提交评价失败！")
                } else {
                    refreshWritingDateList();
                    refreshPostList();
                    $("#success-alert").removeClass("hidden");
                }
            }
        });
    });

    $(".good-detail-btn").on("click", function (e) {
        e.preventDefault();
        $("#success-alert").addClass("hidden");
        if ($(this).hasClass("btn-primary")) {
            $("#bad-detail-" + $(this).val()).attr("disabled", "disabled");
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-default");

            $.ajax({
                type: "POST",
                url: '/mentor/addRuleComment',
                data: {details_id: $(this).val(), posts_id: $("#selected-posts-id").val(), state_flag: "good"},
                success: function( data ) {
                    // console.log(data);
                    $("#success-alert").removeClass("hidden");
                }
            });
        } else {
            $("#bad-detail-" + $(this).val()).removeAttr("disabled");
            $(this).removeClass("btn-default");
            $(this).addClass("btn-primary");

            $.ajax({
                type: "POST",
                url: '/mentor/deleteRuleComment',
                data: {details_id: $(this).val(), posts_id: $("#selected-posts-id").val(), state_flag: "good"},
                success: function( data ) {
                    // console.log(data);
                    $("#success-alert").removeClass("hidden");
                }
            });
        }
    });

    $(".detail-btn").on("click", function (e) {
        e.preventDefault();
        $("#success-alert").addClass("hidden");
        if ($(this).hasClass("btn-primary")) {
            $("#good-detail-" + $(this).val()).attr("disabled", "disabled");
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-default");

            $.ajax({
                type: "POST",
                url: '/mentor/addRuleComment',
                data: {details_id: $(this).val(), posts_id: $("#selected-posts-id").val(), state_flag: "bad"},
                success: function( data ) {
                    // console.log(data);
                    $("#success-alert").removeClass("hidden");
                }
            });
        } else {
            $("#good-detail-" + $(this).val()).removeAttr("disabled");
            $(this).removeClass("btn-default");
            $(this).addClass("btn-primary");

            $.ajax({
                type: "POST",
                url: '/mentor/deleteRuleComment',
                data: {details_id: $(this).val(), posts_id: $("#selected-posts-id").val(), state_flag: "bad"},
                success: function( data ) {
                    // console.log(data);
                    $("#success-alert").removeClass("hidden");
                }
            });
        }
    });

    $("#submit-other-comment-content").on("click", function (e) {
        e.preventDefault();
        $("#success-alert").addClass("hidden");
        if ("" == $("#other-comment-content").val()) {
            alert("内容不能为空！");
            return;
        }
        var data = {
            posts_id: $("#selected-posts-id").val(),
            other_comment_content: $("#other-comment-content").val(),
        };
        $.ajax({
                type: "POST",
                url: '/mentor/addOtherComment',
                data: data,
                success: function( data ) {
                    $("#success-alert").removeClass("hidden");

                    if ("false" == data) {

                    } else {

                    }
                }
            });
    });

    $(document)
       .on('click', '.writing-date-btn', function (e) {
            $("#selected-writing-date").val($(this).val());
            $(".writing-date-btn").removeClass("btn-info");
            $(this).addClass("btn-info");
            refreshPostList();
        })
        .on('click', '.post-btn', function (e) {
            var postsId = $(this).attr("value");
            var prevPostsId = $(this).attr("prevPostsId");
            var nextPostsId = $(this).attr("nextPostsId");

            $("#selected-posts-id").val($(this).attr("value"));
            $('#post-show').attr("src", $(this).attr("thumbnail"));
            $('#input-1').rating('update', 0);
            $.ajax({
                type: "POST",
                url: '/mentor/getPostRate',
                data: {posts_id: postsId},
                success: function( data ) {
                    if ("false" == data) {

                    } else {
                        $('#input-1').rating('update', data);
                    }
                }
            });

            $.ajax({
                type: "POST",
                url: '/mentor/getRuleComment',
                data: {posts_id: postsId},
                success: function( data ) {
                    var goodDetailIds = data.split(" ")[0].split(",");
                    var badDetailIds = data.split(" ")[1].split(",");
                    for (var i = 0; i < goodDetailIds.length; i++) {
                        if ("" != goodDetailIds[i]) {
                            $("#good-detail-"+goodDetailIds[i]).removeClass("btn-primary");
                            $("#good-detail-"+goodDetailIds[i]).addClass("btn-default");
                            $("#bad-detail-"+goodDetailIds[i]).attr("disabled", "disabled");
                        }
                    }

                    for (var i = 0; i < badDetailIds.length; i++) {
                        if ("" != badDetailIds[i]) {
                            $("#bad-detail-"+badDetailIds[i]).removeClass("btn-primary");
                            $("#bad-detail-"+badDetailIds[i]).addClass("btn-default");
                            $("#good-detail-"+badDetailIds[i]).attr("disabled", "disabled");
                        }
                    }
                }
            });

            $.ajax({
                type: "POST",
                url: '/mentor/getOtherComment',
                data: {posts_id: postsId},
                success: function( data ) {
                    $("#other-comment-content").val(data);
                }
            });
            $('#rateModal').modal();
        });
});

function refreshPostList() {
    $.ajax({
        type: "POST",
        url: '/mentor/getPostsByWritingTypeAndDate',
        data: {writingTypesId: $("#selected-writing-types-id").val(), writingDate: $("#selected-writing-date").val()},
        success: function( data ) {
            $("#post-list").html(data);
        }
    });
}

function refreshWritingDateList() {
    $.ajax({
            type: "POST",
            url: '/mentor/getWritingDateByWritingType',
            data: {writingTypesId: $("#selected-writing-types-id").val()},
            success: function( data ) {
                $("#writing-date-list").html(data);
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


