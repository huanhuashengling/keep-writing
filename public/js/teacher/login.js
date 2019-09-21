$(document).ready(function() {
	var objects = {};
	$('.typeahead').typeahead('destroy')
    $("#phone_number").typeahead({
        source: function(query, process) { //query是输入框输入的文本内容, process是一个回调函数
            $.get("/get-phone-number", {}, function(data) {
                
                if (data == "" || data.length == 0) { console.log("没有查询到相关结果"); };
                var results = [];
                for (var i = 0; i < data.length; i++) {
                    objects[i] = data[i].phone_number;
                    results.push(data[i].phone_number);
                }
                // console.log(results);
                process(results);
            });
        },
        afterSelect: function (item) {       //选择项之后的事件，item是当前选中的选项
            $("#hidden").val(objects[item]); //为隐藏输入框赋值
        },
    });

});