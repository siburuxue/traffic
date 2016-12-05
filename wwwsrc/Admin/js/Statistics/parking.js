// 定义全局变量
var submit;
var page = {
    totalrows: 0,
    totalpage: 1,
    nowpage: 1
};
$(function() {
    // 通过部门编号读取办案人信息
    var loadHandle = function(endfunc) {
        $('#select-handle-content').load(url.getHandle, {
            did: $('input[name="condition_department"]').vselect('value')
        }, function() {
            if ($.type(endfunc) === 'function') {
                endfunc();
            }
        });
    };
    // 初始化下拉菜单
    var initVSelect = function(endfunc) {
        $('input[name="condition_department"]').vselect('value', $.trim($('input[name="condition_department"]').data('default')));
        loadHandle(function() {
            $('input[name="condition_handle"]').vselect('value', $.trim($('input[name="condition_handle"]').data('default')));
            if ($.type(endfunc) === 'function') {
                endfunc();
            }
        });
    };
    // 实例化下拉菜单
    $('input[name="condition_department"],input[name="condition_handle"]').vselect();
    // 联动
    $('input[name="condition_department"]').on('vsChange', function() {
        loadHandle(function() {
            $('input[name="condition_handle"]').vselect('value', '');
        });
    });
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        data.condition_department = $('input[name="condition_department"]').vselect('value').join(',');
        data.condition_handle = $('input[name="condition_handle"]').vselect('value').join(',');
        // console.log(data);
        $('#table-content').load(url.table, data, function(response, status, xhr) {
            var $table = $(this).find('#table');
            $table.find('.js-end-refresh').data('end', function() {
                submit.execute('page');
            });
            page.totalrows = $table.data('totalrows');
            page.totalpage = $table.data('totalpage');
            page.nowpage = $table.data('nowpage');
            // 更新分页信息
            $('.page-first,.page-prev').prop('disabled', page.nowpage <= 1);
            $('.page-next,.page-last').prop('disabled', page.nowpage >= page.totalpage);
            $('#page-nowpage').text(page.nowpage);
            $('#page-totalpage').text(page.totalpage);
            $('#page-totalrows').text(page.totalrows);
            $('#search-page').val(page.nowpage).attr('max', page.totalpage);
            commonSort($table);            
        });
    };
    // 注册通用分页事件
    // 注册提交字段
    $('.search-auto').each(function() {
        var the = $(this);
        submit.reg({
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
    var pageTip;
    // 注册分页输入框
    submit.reg({
        group: 'page',
        name: 'page',
        get: function(name) {
            return $('#search-page').val();
        },
        set: function(name, value, data) {
            $('#search-page').val(value);
        },
        rule: function(name, value) {
            if (value == '' || value > page.totalpage || value < 1) {
                $('#search-page').val('').focus();
                pageTip = layer.tips('请输入有效值', '#search-page', {
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
    $('.page-first').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        $('#search-page').val(1);
        submit.execute('page');
    });
    // 最后一页
    $('.page-last').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        $('#search-page').val(page.totalpage);
        submit.execute('page');
    });
    // 上一页    
    $('.page-prev').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        $('#search-page').val(page.nowpage - 1);
        submit.execute('page');
    });
    // 下一页
    $('.page-next').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        $('#search-page').val(page.nowpage + 1);
        submit.execute('page');
    });
    // 当点击跳转按钮
    $('#search-jump').on('click', function() {
        if ($(this).prop('disabled')) {
            return;
        }
        submit.execute('page');
    });
    // 点击搜索按钮
    $('#search-submit').on('click', function() {
        submit.reset('page', function() {
            submit.execute('page', 'condition');
        });
    });
    // 点击重置按钮
    $('#search-reset').on('click', function() {
        initVSelect(function() {
            submit.reset('page', 'condition', function() {
                submit.execute('page', 'condition');
            });
        });
    });
    // 跳转分页输入框只允许输入数字以及在分页输入框按回车键执行跳转
    $('#search-page').on('keyup paste', function() {
        $(this).val($(this).val().replace(/\D|^0/g, ''));
    }).on('keypress', function(e) {
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if (keycode == 13 && $('#search-jump').prop('disabled') === false) {
            submit.execute('page');
        }
    });
    // 刷新事件
    $('.js-end-refresh').data('end', function() {
        submit.execute('page');
    });
    // 初始化
    initVSelect(function() {
        submit.execute('page', 'condition');
    });
    $('#search-jump').prop('disabled', false);
});
