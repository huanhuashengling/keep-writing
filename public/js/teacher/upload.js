$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
    $("#input-zh").fileinput({
		language: "zh", 
		allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "bmp", "m4a", "mp3", "amr"], 
    uploadUrl: "/teacher/upload",
		// uploadAsync: true
		overwriteInitial: true,
		initialPreview: [
			$("#posted-path").val(),
	    ],
	    msgPlaceholder: "选择文件或拍照...",
	    initialPreviewShowDelete: false,
	    initialPreviewAsData: true, // 特别重要
      uploadExtraData: {writing_types_id: $("#writing_types_id").val(), writing_date: $("#writing_date").val()},
	});

	getCurrentWritingDatePostRate();

    $('#writing-type-selection').on('change', function (e) {
		$('#writing_types_id').val($(this).val());
		$("#colleague-post").attr("href", "teacher/colleague?wtId=" + $(this).val() + "&type=all");
		refreshFileinputState();
    });

	$('#writing-date-selection').on('change', function (e) {
        $('#writing_date').val($(this).val());
        $.ajax({
	        type: "POST",
	        url: '/teacher/getCurrentWritingDatePostRate',
	        data: {writing_date : $('#writing_date').val(), writing_types_id : $('#writing-type-selection').val(),},
	        success: function( data ) {
	        	$("#current-date-type-post-progress").attr("style", "width: "+data+"%")
	            // console.log(data);
	            if ("false" == data) {
	            } else {
	            }
	        }
	    });
		refreshFileinputState();
    });
});

function refreshFileinputState() {
	// console.log($('#writing-date-selection').val());
	$.ajax({
            type: "POST",
            url: '/teacher/getOnePost',
            data: {writing_date : $('#writing-date-selection').val(), writing_types_id : $('#writing-type-selection').val(),},
            success: function( data ) {
                // console.log(data);
                if ("false" == data) {
                	$('#input-zh').fileinput('destroy');
                	$("#input-zh").fileinput({
						language: "zh", 
						allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "bmp", "m4a", "mp3", "amr"], 
						// uploadAsync: true
						msgPlaceholder: "选择文件或拍照...",
						overwriteInitial: true,
					    initialPreviewShowDelete: false,
					    initialPreviewAsData: true, // 特别重要
              uploadExtraData: {writing_types_id: $("#writing_types_id").val(), writing_date: $("#writing_date").val()},
					});
                } else {
                	$('#input-zh').fileinput('destroy');
                	
				    $("#input-zh").fileinput({
						language: "zh", 
						allowedFileExtensions: ["jpg", "png", "gif", "bmp", "jpeg", "m4a", "mp3", "amr"], 
						// uploadAsync: true
						msgPlaceholder: "选择文件或拍照...",
						overwriteInitial: true,
						initialPreview: [
							data.storage_name,
					    ],
					    initialPreviewShowDelete: false,
					    initialPreviewAsData: true, // 特别重要
              uploadExtraData: {writing_types_id: $("#writing_types_id").val(), writing_date: $("#writing_date").val()},
					});
                }
            }
        });

        getCurrentWritingDatePostRate();
}

function getCurrentWritingDatePostRate(){
	$.ajax({
        type: "POST",
        url: '/teacher/getCurrentWritingDatePostRate',
        data: {writing_date : $('#writing_date').val(), writing_types_id : $('#writing-type-selection').val(),},
        success: function( data ) {
        	$("#current-date-type-post-progress").attr("style", "width: "+data+"%")
            // console.log(data);
            if ("false" == data) {
            } else {
            }
        }
    });
}