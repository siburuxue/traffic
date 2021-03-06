var linkedSubmit
    // 分页
var linkedPage = {
    totalrows: 0,
    totalpage: 1,
    nowpage: 1
};
$(function() {
    // 区域dom
    var linkedBox = $('#linked-box');
    // 创建提交对象
    linkedSubmit = $.vmcSubmit();
    // 发送POST请求
    linkedSubmit.success = function(data) {
        linkedBox.find('#linked-table-content').load(url.linkedTable, data, function(response, status, xhr) {
            var $table = $(this).find('#table');
            // 注册刷新事件
            $table.find('.js-end-refresh').data('end', function() {
                var the = $(this);
                if (the.data('js-end-target') == 'search') {} else if (the.data('js-end-target') == 'linked') {
                    linkedSubmit.execute('page');
                } else {
                    linkedSubmit.execute('page');
                }
            });
            // 更新分页参数
            linkedPage.totalrows = $table.data('totalrows');
            linkedPage.totalpage = $table.data('totalpage');
            linkedPage.nowpage = $table.data('nowpage');
            // 渲染分页信息
            linkedBox.find('.page-first,.page-prev').prop('disabled', linkedPage.nowpage <= 1);
            linkedBox.find('.page-next,.page-last').prop('disabled', linkedPage.nowpage >= linkedPage.totalpage);
            linkedBox.find('#search-jump').prop('disabled', linkedPage.totalpage <= 1);
            linkedBox.find('#page-nowpage').text(linkedPage.nowpage);
            linkedBox.find('#page-totalpage').text(linkedPage.totalpage);
            linkedBox.find('#page-totalrows').text(linkedPage.totalrows);
            linkedBox.find('#search-page').val(linkedPage.nowpage).attr('max', linkedPage.totalpage);
        });
    };
    // 注册提交字段
    linkedBox.find('.search-auto').each(function() {
        var the = $(this);
        linkedSubmit.reg({
            group: 'condition',
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 注册案件编号字段
    linkedSubmit.reg({
        group: 'page',
        name: 'case_id',
        get: function(name) {
            return $('input[name="case_id"]').val();
        },
        set: function(name, value, data) {}
    });
    // 注册分页输入框
    linkedSubmit.reg({
        group: 'page',
        name: 'page',
        get: function(name) {
            return linkedBox.find('#search-page').val();
        },
        set: function(name, value, data) {
            linkedBox.find('#search-page').val(value);
        },
        rule: function(name, value) {
            var pageTip;
            if (value == '' || value > linkedPage.totalpage || value < 1) {
                linkedBox.find('#search-page').val('').focus();
                pageTip = layer.tips('请输入有效值', linkedBox.find('#search-page'), {
                    tips: [1, '#337AB7'],
                    time: 2500
                });
                return false;
            } else {
                layer.close(pageTip);
                return true
            }
        }
    });
    // 第一页
    linkedBox.find('.page-first').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        linkedBox.find('#search-page').val(1);
        linkedSubmit.execute('page');
    });
    // 最后一页
    linkedBox.find('.page-last').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        linkedBox.find('#search-page').val(linkedPage.totalpage);
        linkedSubmit.execute('page');
    });
    // 上一页    
    linkedBox.find('.page-prev').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        linkedBox.find('#search-page').val(linkedPage.nowpage - 1);
        linkedSubmit.execute('page');
    });
    // 下一页
    linkedBox.find('.page-next').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        linkedBox.find('#search-page').val(linkedPage.nowpage + 1);
        linkedSubmit.execute('page');
    });
    // 当点击跳转按钮
    linkedBox.find('#search-jump').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        linkedSubmit.execute('page');
    });
    // 点击搜索按钮
    linkedBox.find('#search-submit').on('click', function() {
        linkedSubmit.reset('page', function() {
            linkedSubmit.execute('page', 'condition');
        });
    });
    // 点击重置按钮
    linkedBox.find('#search-reset').on('click', function() {
        linkedSubmit.reset('page', 'condition', function() {
            linkedSubmit.execute('page', 'condition');
        });
    });
    // 跳转分页输入框只允许输入数字以及在分页输入框按回车键执行跳转
    linkedBox.find('#search-page').on('keyup paste', function() {
        $(this).val($(this).val().replace(/\D|^0/g, ''));
    }).on('keypress', function(e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == 13 && linkedBox.find('#search-jump').prop('disabled') === false) {
            linkedSubmit.execute('page');
        }
    });
});
$(function() {
    var $table = $('#search-table-content');
    $table.on('click', 'input:checkbox[name="selectall"]', function() {
        var checked = $(this).prop('checked');
        $table.find('input:checkbox[name="selectone"]:enabled').prop('checked', checked).closest('tr').toggleClass('warning', checked);
    }).on('click', 'input:checkbox[name="selectone"]:enabled', function() {
        var totallength = $table.find('input:checkbox[name="selectone"]:enabled').length;
        var checkedlength = $table.find('input:checkbox[name="selectone"]:enabled:checked').length;
        $table.find('input:checkbox[name="selectall"]').prop('checked', totallength <= checkedlength);
        $(this).closest('tr').toggleClass('warning', $(this).prop('checked'));
    });
});
$(function() {
    var $table = $('#linked-table-content');
    $table.on('click', 'input:checkbox[name="selectall"]', function() {
        var checked = $(this).prop('checked');
        $table.find('input:checkbox[name="selectone"]:enabled').prop('checked', checked).closest('tr').toggleClass('warning', checked);
    }).on('click', 'input:checkbox[name="selectone"]:enabled', function() {
        var totallength = $table.find('input:checkbox[name="selectone"]:enabled').length;
        var checkedlength = $table.find('input:checkbox[name="selectone"]:enabled:checked').length;
        $table.find('input:checkbox[name="selectall"]').prop('checked', totallength <= checkedlength);
        $(this).closest('tr').toggleClass('warning', $(this).prop('checked'));
    });
});
$(function() {
    // 关联
    $('#link-submit').on('click', function() {
        var ids = new Array();
        $('#search-table-content').find('input[name="selectone"]:checked').each(function() {
            var the = $(this);
            ids.push(the.attr('value'));
        });
        $.post(url.link, {
            ids: ids,
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            layer.alert(msg.info, function(index) {
                layer.close(index);
                if (msg.status == 1) {
                    linkedSubmit.execute('page');
                }
            });
        });
    });
    // 取消关联
    $('#unlink-submit').on('click', function() {
        var ids = new Array();
        $('#linked-table-content').find('input[name="selectone"]:checked').each(function() {
            var the = $(this);
            ids.push(the.attr('value'));
        });
        $.post(url.unlink, {
            ids: ids,
            case_id: $('input[name="case_id"]').val()
        }, function(msg) {
            layer.alert(msg.info, function(index) {
                layer.close(index);
                if (msg.status == 1) {
                    linkedSubmit.execute('page');
                }
            });
        });
    });
    // 刷新事件
    $('.js-end-refresh').data('end', function() {
        var the = $(this);
        if (the.data('js-end-target') == 'search') {} else if (the.data('js-end-target') == 'linked') {
            linkedSubmit.execute('page');
        } else {
            linkedSubmit.execute('page');
        }
    });
    // 初始化
    linkedSubmit.execute('page');
});
