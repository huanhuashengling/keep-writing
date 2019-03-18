$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
    $("#input-zh").fileinput({
		language: "zh", 
		allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "bmp"], 
		// uploadAsync: true
		overwriteInitial: true,
		initialPreview: [
			$("#posted-path").val(),
	    ],
	    msgPlaceholder: "选择文件或拍照...",
	    initialPreviewShowDelete: false,
	    initialPreviewAsData: true, // 特别重要
	});

	getCurrentWritingDatePostRate();

    $('#writing-type-selection').on('change', function (e) {
		$('#writing_types_id').val($('#writing-type-selection').val());
    });

	$('#writing-date-selection').on('change', function (e) {
		$('#writing_types_id').val($('#writing-type-selection').val());
        $('#writing_date').val($(this).val());
		$.ajax({
            type: "POST",
            url: '/teacher/getOnePost',
            data: {writing_date : $(this).val(), writing_types_id : $('#writing-type-selection').val(),},
            success: function( data ) {
                // console.log(data);
                if ("false" == data) {
                	$('#input-zh').fileinput('destroy');
                	$("#input-zh").fileinput({
						language: "zh", 
						allowedFileExtensions: ["jpg", "png", "gif", "jpeg", "bmp"], 
						// uploadAsync: true
						msgPlaceholder: "选择文件或拍照...",
						overwriteInitial: true,
					    initialPreviewShowDelete: false,
					    initialPreviewAsData: true, // 特别重要
					});
                } else {
                	$('#input-zh').fileinput('destroy');
                	
				    $("#input-zh").fileinput({
						language: "zh", 
						allowedFileExtensions: ["jpg", "png", "gif", "bmp", "jpeg"], 
						// uploadAsync: true
						msgPlaceholder: "选择文件或拍照...",
						overwriteInitial: true,
						initialPreview: [
							data.storage_name,
					    ],
					    initialPreviewShowDelete: false,
					    initialPreviewAsData: true, // 特别重要
					});
                }
            }
        });

        getCurrentWritingDatePostRate();
    });
});

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