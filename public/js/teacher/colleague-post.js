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

    // $('.writing-type-btn').on('click', function (e) {
    //     // $("#selected-writing-types-id").val($(this).val());
    //     // storeWritingTypesId();
    //     top.location='/teacher/colleague?wtId='+$(this).val();
    //     // $(".writing-type-btn").removeClass("btn-success");
    //     // $(this).addClass("btn-success");
        
    // });

    $("[name='likeCheckBox']").bootstrapSwitch({
        onText: '点赞',
        offText: '点赞',
        onColor: 'danger',
        offColor: 'default',
    });
    // $("#is-init").val("true");
    $("[name='likeCheckBox']").on('switchChange.bootstrapSwitch', function (e, data) {
        // console.log("change state event init value "+$("#is-init").val() +" data "+data);
        // var $el = $(data.el)
        // , value = data.value;
        // console.log("init" + $("#is-init").val());
        if ("true" == $("#is-init").val()) {
            var currentMarkNum = parseInt($("#mark-num").val());
            // console.log(e, data, currentMarkNum);

            var markNum = 0;
            $.ajax({
                type: "POST",
                url: '/teacher/updateMarkState',
                data: {postsId: $("#posts-id").val(), stateCode:((true == data)?1:0)},
                success: function( returnData ) {
                    // console.log(data);
                    if ("false" == returnData) {

                    } else {
                        if (true == data) {
                            markNum = currentMarkNum+1;
                        } else {
                            markNum = (0 == currentMarkNum)?0:(currentMarkNum-1);
                        }
                        $("#mark-num").val(markNum);
                        $("[name='likeCheckBox']").siblings(".bootstrap-switch-label").text(markNum);
                    }
                }
            });
        }
        $("#is-init").val("true");
    });

    $('#my-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=my'; 
    });

    $('#all-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=all'; 
    });

    $('#most-star-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=most-star'; 
    });

    $('#same-grade-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=same-grade'; 
    });

    $('#my-marked-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=my-marked'; 
    });

    $('#most-marked-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=most-marked'; 
    });

    $('#has-comment-posts-btn').on('click', function (e) {
        top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=has-comment'; 
    });

    $('#name-search-btn').on('click', function (e) {
        if("" == $("#search-name").val()) {
            alert("姓名不能为空！");
        } else {
            top.location='/teacher/colleague?wtId=' + $("#wtId").val() + '&type=search-name=' + $("#search-name").val(); 
        }
    });

    $("#prev-btn").on("click", function(e) {
        $("#img"+$(this).attr("value")).trigger("click");
    });

    $("#next-btn").on("click", function(e) {
        $("#img"+$(this).attr("value")).trigger("click");
    });

    $('.thumb-img').on('click', function (e) {
        e.preventDefault();
        // $("[name='likeCheckBox']").bootstrapSwitch('state', "false");
        // refreshModalView($(this).attr("value"), $(this).attr("prevId"), $(this).attr("nextId"));
        if ($(".rate-btn").hasClass('active')) {
          setTimeout(function() {
            $(".rate-btn").removeClass('active').find('input').prop('checked', false);
          }.bind(this), 10);
        }
        $('#post-comment').val("");
        // console.log($(this).attr("value"));
        // var postsId = (e.target.value).split(',')[0]; 
        var postsId = $(this).attr("value");
        $('#classmate-post-show').attr("src", "");
        // $.ajax({
        //     type: "POST",
        //     url: '/student/getPostRate',
        //     data: {posts_id: postsId},
        //     success: function( data ) {
        //         if ("false" != data && "待完" != data) {
        //             $("#switch-box").removeClass('hidden'); 
        //         }
        //     }
        // });

        $.ajax({
            type: "POST",
            url: '/teacher/getMarkNumByPostsId',
            data: {postsId: postsId},
            success: function( data ) {
                $("[name='likeCheckBox']").siblings(".bootstrap-switch-label").text(data);
                $("#mark-num").val(data);
            }
        });

        $.ajax({
            type: "POST",
            url: '/teacher/getIsMarkedByMyself',
            data: {postsId: postsId},
            success: function( data ) {
                // console.log("getIsMarkedByMyself " + data);

                if ("true" == data) {
                    $("#is-init").val("false");
                    // $("#is-marked-by-myself").val(data);
                    $("[name='likeCheckBox']").bootstrapSwitch('state', true);
                    // $("[name='likeCheckBox']").prop('checked', true);
                } else {
                    $("[name='likeCheckBox']").bootstrapSwitch('state', false);
                }
                
            }
        });

        $.ajax({
            type: "POST",
            url: '/teacher/getOnePostById',
            data: {posts_id: postsId},
            success: function( data ) {
                // console.log(data);
                if ("false" == data) {

                } else {
                        $('#doc-preview').addClass("hidden");
                        $('#classmate-post-show').removeClass("hidden");
                        $('#flashContent').addClass("hidden");
                        // $('#classmate-post-show').attr("src", data.filePath);
                        $('#classmate-post-show').attr("src", data.filePath);
                    // $('#classmate-post-show').attr("src", data.storage_name);
                    $("#classmate-post-modal-label").html(data.username+"老师 "+data.writingDate+""+data.writingType+"作品");
                }
            }
        });

        // $.ajax({
        //     type: "POST",
        //     url: '/student/getCommentByPostsId',
        //     data: {posts_id: postsId},
        //     success: function( data ) {
        //         // console.log(data);
        //         if ("false" == data) {
        //             $("#edit-post-comment-btn").addClass("hidden");
        //             $("#add-post-comment-btn").removeClass("hidden");
        //         } else {
        //             var comment = JSON.parse(data);
        //             $("#edit-post-comment-btn").val(comment['id']);
        //             $('#post-comment').text("老师评语：" + comment['content']);
        //             $("#edit-post-comment-btn").removeClass("hidden");
        //             $("#add-post-comment-btn").addClass("hidden");
        //         }
        //     }
        // });
        if ($(this).attr("prevId")) {
            $("#prev-btn").removeClass("hidden");
            $("#prev-btn").val($(this).attr("prevId"));
        } else {
            $("#prev-btn").addClass("hidden");
        }
        if ($(this).attr("nextId")) {
            $("#next-btn").removeClass("hidden");
            $("#next-btn").val($(this).attr("nextId"));
        } else {
            $("#next-btn").addClass("hidden");
        }
        $('#posts-id').val(postsId);
        // $('#post-download').attr("href", filePath);
        $('#colleague-post-modal').modal();
    });
});


function storeWritingTypesId() {
    $.ajax({
        type: "POST",
        url: '/teacher/storeWritingTypesId',
        data: {writingTypesId: $("#selected-writing-types-id").val()},
        success: function( data ) {
            $("#post-list").html(data);
        }
    });
}
