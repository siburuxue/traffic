// 定义全局变量
var submit;
// 判断key是否存在obj中
var inObj = function(key, obj) {
    for (id in obj) {
        if (key == id) {
            return true;
        }
    }
    return false;
};
$(function() {
    // 是否多选
    var multiple = $.type(parent.userMultiple) === 'boolean' ? parent.userMultiple : true;
    // 标记
    var mark = parent.userMark || '';
    // 服务器返回数据
    var userData = {};
    // 样本数据
    var sampleData = {};
    // 结果数据
    var resultData = $.extend({}, parent.userSelected[mark]);
    // 渲染
    var render = function() {
        var sampleDom = $('#sample')[0];
        sampleDom.options.length = 0;
        $.each(sampleData, function(id, name) {
            sampleDom.options.add(new Option(name, id));
        });
        var resultDom = $('#result')[0];
        resultDom.options.length = 0;
        $.each(resultData, function(id, name) {
            resultDom.options.add(new Option(name, id));
        });
    };
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.getUser, data, function(msg) {
            if (msg.status == 1) {
                // 更新服务器端获取数据
                userData = msg.info;
                // 服务器数据更新，样本数据必然同步更新
                sampleData = {};
                $.each(userData, function(id, name) {
                    if (false === inObj(id, resultData)) {
                        sampleData[id] = name;
                    }
                });
                render();
            } else {
                alert(msg.info);
            }
        });
    };
    // 点击增加
    $('#add').on('click', function() {
        var addIds = $('#sample').val();
        if (!addIds) {
            return false;
        }
        if (false === multiple) {
            sampleData = $.extend({}, userData);
            resultData = {};
        }
        $.each(sampleData, function(id, name) {
            if (($.isArray(addIds) && $.inArray(id, addIds) >= 0) || (id == addIds)) {
                resultData[id] = name;
                delete sampleData[id];
            }
        });
        render();
    });
    // 全部添加
    $('#add-all').on('click', function() {
        resultData = $.extend({}, resultData, sampleData);
        sampleData = {};
        render();
    });
    // 点击删除
    $('#delete').on('click', function() {
        var deleteIds = $('#result').val();
        if (!deleteIds) {
            return false;
        }
        $.each(resultData, function(id, name) {
            if (($.isArray(deleteIds) && $.inArray(id, deleteIds) >= 0) || (deleteIds == id)) {
                delete resultData[id];
                if (inObj(id, userData)) {
                    sampleData[id] = name;
                }
            }
        });
        render();
    });
    // 全部删除
    $('#delete-all').on('click', function() {
        sampleData = $.extend({}, userData);
        resultData = {};
        render();
    });
    // 注册字段
    $('.search-auto').each(function() {
        var the = $(this);
        submit.reg({
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 点击搜索按钮
    $('#search-submit').on('click', function() {
        submit.execute();
    });
    // 点击重置按钮
    $('#search-reset').on('click', function() {
        submit.reset();
    });
    // 点击确定按钮
    $('#submit').on('click', function() {
        parent.userSelected[mark] = $.extend({}, resultData);
        parent.layer.close(win_index);
    });
    // 初始化
    $('#sample,#result').prop('multiple', multiple);
    $('#add-all,#delete-all').prop('disabled', !multiple);
    render();
});
