$(document).ready(function() {
	$.ajaxSetup({
	  headers: {
	    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	  }
	});

	$('#writing-rule-list').bootstrapTable({
        method: 'post', 
        search: "true",
        url: "/school/getWritingRule",
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

    $("#rule-btn").click(function(e) {
        // alert($(this).val());
        $("#add-rule-btn").text("增加");
        $("#rule-title").text("增加评价标准");

        $("#rule-desc").val("");
        $("#selected-writing-rules-id").val("");
        $("#weight-ratio").val("25");
        $("#add-writing-rule-modal").modal("show");
    });

    $("#add-rule-btn").click(function(e) {
    	// alert($("#add-btn").val());
        if("" == $("#rule-desc").val()) {
            alert("评价标准不能为空！");
            return;
        }
        data = {
            'id' : $("#selected-writing-rules-id").val(),
            'rule_desc' : $("#rule-desc").val(),
            'weight_ratio' : $("#weight-ratio").val(),
            'writing_types_id' : $("#writing-types-selection").val(),
        }
        $.ajax({
            type: "post",
            url: '/school/createWritingRule',
            data: data,
            success: function( data ) {
                $("#add-writing-rule-modal").modal("hide");
                $('#writing-rule-list').bootstrapTable('refresh');
            }
        });
    });

    $("#update-rule-btn").click(function(e) {
        // alert($("#add-btn").val());
        if("" == $("#rule-desc").val()) {
            alert("评价标准不能为空！");
            return;
        }
        data = {
            'id' : $("#selected-writing-rules-id").val(),
            'rule_desc' : $("#rule-desc").val(),
            'writing_types_id' : $("#writing-types-selection").val(),
        }
        $.ajax({
            type: "post",
            url: '/school/createWritingRule',
            data: data,
            success: function( data ) {
                $("#add-writing-rule-modal").modal("hide");
                $('#writing-rule-list').bootstrapTable('refresh');
            }
        });
    });

    $("#detail-btn").click(function(e) {
        $("#add-detail-btn").text("增加");
        // $("#detail-title").text("增加评价细则");
        $("#detail-desc").val("");
        $("#selected-writing-details-id").val("");
        $("#add-writing-detail-modal").modal("show");
    });

    $("#add-detail-btn").click(function(e) {
        // alert($("#detail-desc").val());
        if("" == $("#detail-desc").val())
        {
            alert("评价细则不能为空！");
            return;
        }

        if("" == $("#detail-score").val())
        {
            alert("评价分值不能为空！");
            return;
        }

        if("" == $("#selected-writing-rules-id").val())
        {
            alert("评价标准不能为空！");
            return;
        }
        data = {
            'id' : $("#selected-writing-details-id").val(),
            'detail_desc' : $("#detail-desc").val(),
            'detail_score' : $("#detail-score").val(),
            'writing_rules_id' : $("#selected-writing-rules-id").val(),
        }
        $.ajax({
            type: "post",
            url: '/school/createWritingDetail',
            data: data,
            success: function( data ) {
                $("#add-writing-detail-modal").modal("hide");
                $('#writing-detail-list').bootstrapTable('refresh');
            }
        });
    });
});

function writingRuleActionCol(value, row, index) {
    return [
        ' <a class="btn btn-success btn-sm edit">编辑</a> ',
        ' <a class="btn btn-info btn-sm detail">详细</a>'
    ].join('');
}

function weightRatioCol(value, row, index) {
    var weightRatioStr = "-";
    if (row.weight_ratio)
    {
        weightRatioStr = row.weight_ratio + "%";
    }
    return [
        weightRatioStr
    ].join('');
}

window.writingRuleActionEvents = {
    'click .edit': function(e, value, row, index) {
        $("#add-rule-btn").text("更新");
        $("#rule-title").text("更新评价标准");

        $("#selected-writing-rules-id").val(row.id);
        $("#writing-types-selection").val(row.writing_types_id);
        $("#rule-desc").val(row.rule_desc);
        $("#weight-ratio").val(row.weight_ratio);
        $("#add-writing-rule-modal").modal("show");
    },
    'click .detail': function(e, value, row, index) {
        $("#detail-btn").removeClass("hidden");
        $("#selected-writing-rules-id").val(row.id);
        $("#detail-title").text(row.name + "评价标准 " + row.rule_desc + " 细则");
        // console.log(row);
        $('#writing-detail-list').bootstrapTable('destroy');
        $('#writing-detail-list').bootstrapTable({
            method: 'post', 
            search: "true",
            url: "/school/getWritingDetail",
            pagination:"true",
            pageList: [10, 25, 50], 
            pageSize: 35,
            pageNumber: 1,
            toolbar:"#toolbar",
            queryParams: function(params) {
                var temp = { 
                    writingRulesId: row.id, 
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

function writingDetailActionCol(value, row, index) {
    return [
        ' <a class="btn btn-success btn-sm edit">编辑</a> ',
        ' <a class="btn btn-info btn-sm detail">锁定</a>'
    ].join('');
}

window.writingDetailActionEvents = {
    'click .edit': function(e, value, row, index) {
        $("#add-detail-btn").text("更新");
        // $("#detail-title").text("更新评价细则");
        $("#detail-desc").val(row.detail_desc);
        $("#selected-writing-details-id").val(row.id);
        $("#add-writing-detail-modal").modal("show");
    },
}


