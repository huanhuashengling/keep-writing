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

    initPostComment();

    $("#again-btn").click(function (e) {
        location.reload();
    });

    $("#start-again-btn").click(function (e) {
        location.reload();
    });

    $('#input-1').rating({
        step: 0.1,
        min: 0, 
        max: 5, 
        stars: 5,
        language: 'zh',
        // starCaptions: {0.5: '0.5星', 1: '1星', 1.5: '1.5星', 2: '2星', 2.5: '2.5星', 3: '3星', 3.5: '3.5星', 4: '4星', 4.5: '4.5星', 5: '5星'},
        // starCaptionClasses: {1: 'text-danger', 2: 'text-warning', 3: 'text-info', 4: 'text-primary', 5: 'text-success'}
    });

    // $("#input-1").rating().on("rating:change", function(event, value, caption) {
    //     $("#success-alert").addClass("hidden");
    //     $.ajax({
    //         type: "POST",
    //         url: '/teacher/rateOnePost',
    //         data: {rate: value, postsId:  $("#selected-posts-id").val()},
    //         success: function( data ) {
    //             // console.log(data);
    //             if ("false" == data) {
    //                 $("#msg-title").html("提交评价成功！");
    //                 $("#msgModal").modal();
    //             } else {
    //                 $("#success-alert").removeClass("hidden");
    //             }
    //         }
    //     });
    // });

    $(".good-detail-btn").on("click", function (e) {
        e.preventDefault();
        $("#success-alert").addClass("hidden");
        if ($(this).hasClass("btn-primary")) {
            $(this).removeClass("btn-primary");
            $(this).addClass("btn-default");

            $.ajax({
                type: "POST",
                url: '/teacher/addRuleComment',
                data: {details_id: $(this).val(), posts_id: $("#selected-posts-id").val(), state_flag: "good"},
                success: function( data ) {
                    // console.log(data);
                    // countRateScore();
                    $('#input-1').rating('update', countRateScore());
                    $("#success-alert").removeClass("hidden");
                }
            });
        } else {
            $(this).removeClass("btn-default");
            $(this).addClass("btn-primary");

            $.ajax({
                type: "POST",
                url: '/teacher/deleteRuleComment',
                data: {details_id: $(this).val(), posts_id: $("#selected-posts-id").val(), state_flag: "good"},
                success: function( data ) {
                    // console.log(data);
                    
                    $('#input-1').rating('update', countRateScore());
                    $("#success-alert").removeClass("hidden");
                }
            });
        }
    });

    $("#submit-comment").on("click", function (e) {
        e.preventDefault();
        $("#success-alert").addClass("hidden");
        if ("" == $("#good-word").val() || "" == $("#bad-word").val()) {
            $("#msg-title").html("字评不能为空！");
            $("#msgModal").modal();
            return;
        }
        if ("" == $('#input-1').rating().val()) {
            $("#msg-title").html("书写评价不能为空！");
            $("#msgModal").modal();
            return;
        }
        var data = {
            posts_id: $("#selected-posts-id").val(),
            good_word: $("#good-word").val(),
            bad_word: $("#bad-word").val(),
        };
        $.ajax({
            type: "POST",
            url: '/teacher/addWordComment',
            data: data,
            success: function( data ) {
            }
        });

        var data = {
            posts_id: $("#selected-posts-id").val(),
            mutual_rate: $('#input-1').rating().val(),
        };
        $.ajax({
            type: "POST",
            url: '/teacher/addMutualRate',
            data: data,
            success: function( data ) {
                $("#againModal").modal();
            }
        });
    });
});

function countRateScore() {
    var score = 0;
    $(".good-detail-btn").filter(".btn-default").each(function (e) {
        score += parseFloat($(this).attr("score"));
    });
    return score;
}

function initPostComment() {
    var data = {
            posts_id: $("#selected-posts-id").val(),
        };
        $.ajax({
            type: "POST",
            url: '/teacher/getMutualRate',
            data: data,
            success: function( data ) {
                // console.log(data);
                $('#input-1').rating('update', data);
            }
        });
        $.ajax({
            type: "POST",
            url: '/teacher/getWordComment',
            data: data,
            success: function( data ) {
                $("#good-word").val(data.good_word);
                $("#bad-word").val(data.bad_word);
                // console.log(data.good_word);
                // console.log(data.bad_word);
            }
        });

        $.ajax({
            type: "POST",
            url: '/teacher/getRuleComment',
            data: data,
            success: function( data ) {
                // console.log(data);
                var goodDetailIds = data.split(",");
                for (var i = 0; i < goodDetailIds.length; i++) {
                    if ("" != goodDetailIds[i]) {
                        $("#good-detail-"+goodDetailIds[i]).removeClass("btn-primary");
                        $("#good-detail-"+goodDetailIds[i]).addClass("btn-default");
                    }
                }
            }
        });
}


