$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});
    $("#input-zh").fileinput({
		language: "zh", 
		allowedFileExtensions: ["jpg", "png", "gif", "bmp"], 
		// uploadAsync: true
		overwriteInitial: true,
		initialPreview: [
			$("#posted-path").val(),
	    ],
	    initialPreviewShowDelete: false,
	    initialPreviewAsData: true, // 特别重要
	});

    // $('#writing-type-selection').on('click', function (e) {
    //     top.location='/student/classmate?type=my'; 
    // });

	$('#writing-date-selection').on('change', function (e) {
		$.ajax({
            type: "POST",
            url: '/teacher/getOnePost',
            data: {writing_date : $(this).val(), writing_types_id : $('#writing-type-selection').val(),},
            success: function( data ) {
                console.log(data);
                if ("false" == data) {
                	$('#input-zh').fileinput('destroy');
                	$("#input-zh").fileinput({
						language: "zh", 
						allowedFileExtensions: ["jpg", "png", "gif", "bmp"], 
						// uploadAsync: true
						overwriteInitial: true,
					    initialPreviewShowDelete: false,
					    initialPreviewAsData: true, // 特别重要
					});
                } else {
                	$('#input-zh').fileinput('destroy');
				    $("#input-zh").fileinput({
						language: "zh", 
						allowedFileExtensions: ["jpg", "png", "gif", "bmp"], 
						// uploadAsync: true
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
    });
});