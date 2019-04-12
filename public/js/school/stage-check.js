$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

		$('#stage-check-list').bootstrapTable({
	        method: 'post', 
	        search: "true",
	        url: "/school/getStageCheckData",
	        pagination:"true",
	        pageList: [10, 25, 50], 
	        pageSize: 10,
	        pageNumber: 1,
	        toolbar:"#toolbar",
        	queryParams: function(params) {
        		var temp = { 
			        schools_id : 1
			    };
			    return temp;
        	},
        	clickToSelect: true,
        	columns: [{  
                        checkbox: true  
                    },{  
                        title: '序号',
                        formatter: function (value, row, index) {  
                            return index+1;  
                        }  
                    }],
	        responseHandler: function (res) {
	        	console.log(res);
	            return res;
	        },
	    });
	// });

    $("#add-new-btn").click(function(e) {
        // alert($(this).val());
        $("#add-new-stage-check-modal").modal("show");
    });

    $("#confirm-add-new-btn").click(function(e) {
    	// alert($("#add-new-btn").val());
        if("" == $("#check-date").val())
        {
            alert("检查日期不能为空！");
            return;
        }
        data = {
            'check_date' : $("#check-date").val(),
            'writing_types_id' : $("#writing-types-selection").val(),
            'schools_id' : $("#add-new-btn").val(),
        }
        $.ajax({
            type: "post",
            url: '/school/createStageCheck',
            data: data,
            success: function( data ) {
                $("#add-new-stage-check-modal").modal("hide");
                $('#stage-check-list').bootstrapTable('refresh');
            }
        });
        // console.log($("#teacher-name").val());
        // console.log($("#gender").val());
        // console.log($("#add-new-btn").val());
    });
});

function stageCheckActionCol(value, row, index) {
    return [
        ' <a class="btn btn-danger btn-sm edit">编辑</a> ',
        ' <a class="btn btn-danger btn-sm detail">详细</a>'
    ].join('');
}


window.stageCheckActionEvents = {
    'click .edit': function(e, value, row, index) {
        // console.log(row);
        // $.ajax({
        //     type: "POST",
        //     url: '/school/lockOneStudentAccount',
        //     data: {users_id: row.teachersId},
        //     success: function( data ) {
        //         if("true" == data) {
        //             alert(row.username+"已被锁定！")
        //         } else if ("false" == data) {
        //             alert("锁定失败！")
        //         }
        //     }
        // });
    },
    'click .detail': function(e, value, row, index) {
        $("#report-title").text(row.check_date + " " + row.name + " 打卡情况");
        // console.log(row);
        $('#stage-check-report').bootstrapTable('destroy');
        $('#stage-check-report').bootstrapTable({
            method: 'post', 
            search: "true",
            url: "/school/getStageReport",
            pagination:"true",
            pageList: [10, 25, 50], 
            pageSize: 35,
            pageNumber: 1,
            toolbar:"#toolbar",
            queryParams: function(params) {
                var temp = { 
                    writingDate: row.check_date, 
                    'writingTypesId': row.writing_types_id,
                };
                return temp;
            },
            clickToSelect: true,
            columns: [{  
                        checkbox: true  
                    },{  
                        title: '序号',
                        formatter: function (value, row, index) {  
                            return index+1;  
                        }  
                    }],
            responseHandler: function (res) {
                // console.log(res);
                return res;
            },
        });
    },
}
