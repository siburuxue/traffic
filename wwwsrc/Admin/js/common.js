// 链接地址对象
var url = {};
// 当前弹窗的索引
var win_index = parent.layer.getFrameIndex(window.name);
//--------------------------------------------------------------------------------
// 加载过程动画
//--------------------------------------------------------------------------------
$(function() {
    // 加载
    var loading;
    var ajaxflag = 0;
    var state = 0;
    var loadingStatus = function(status) {
        if (true === status) {
            ajaxflag++;
        } else {
            ajaxflag--;
        }
        if (ajaxflag > 0) {
            if (state == 0) {
                loading = layer.load(0, {
                    shade: [0.1, '#fff'] //0.1透明度的白色背景
                });
                state = 1;
            }
        } else {
            layer.close(loading);
            state = 0;
        }
    };
    $(document).ajaxSend(function() {
        loadingStatus(true);
    }).ajaxComplete(function() {
        loadingStatus(false);
    });
});
//--------------------------------------------------------------------------------
// 全局事件委派
//--------------------------------------------------------------------------------
$(function() {
    // 将非弹窗页的关闭按钮隐藏
    if ($.type(win_index) === 'undefined') {
        $('.js-close').hide();
    }
    // 点击
    $(document).on('click', '.js-open', function(e) {
        e.preventDefault();
        var url, the = $(this),
            that = this,
            closeBtn = the.data('close-btn'),
            height = the.data('height'),
            width = the.data('width'),
            title = the.data('title');
        closeBtn = $.type(closeBtn) == 'number' ? closeBtn : 0;
        height = height ? height : '100%';
        width = width ? width : '100%';
        title = title ? title : false;
        if (the.hasClass('disabled')) {
            return false;
        }
        if ((url = the.attr('href')) || (url = the.data('url'))) {
            layer.open({
                type: 2,
                title: title,
                closeBtn: 0,
                shadeClose: false,
                shade: 0.8,
                scrollbar: false,
                move: false,
                area: [width, height],
                content: url,
                end: function() {
                    var endfunc = the.data('end');
                    if ($.type(endfunc) == 'function') {
                        endfunc.call(that);
                    }
                }
            });
        } else {
            layer.alert('未指定窗口链接！');
        }
        return false;
    }).on('click', '.js-close', function() {
        if (win_index) {
            parent.layer.close(win_index);
        }
    }).on('click', '.js-ajax', function(e) {
        e.preventDefault();
        var url, the = $(this),
            that = this;
        var success = function(msg, status, xhr) {
            if (msg.url) {
                window.location.href = msg.url;
            } else {
                var endfunc = the.data('end');
                if ($.type(endfunc) == 'function') {
                    endfunc.call(that, msg, status, xhr);
                }
            }
        };
        var request = function(url) {
            $.get(url, function(msg, status, xhr) {
                if (msg.status == 1) {
                    if (the.hasClass('js-no-alert')) {
                        success(msg, status, xhr);
                    } else {
                        layer.msg(msg.info, {
                            shade: 0.1,
                            shadeClose: true
                        }, function(index) {
                            layer.close(index);
                            success(msg, status, xhr);
                        });
                    }
                } else {
                    layer.alert(msg.info, function(index) {
                        layer.close(index);
                        if (msg.url) {
                            window.location.href = msg.url;
                        }
                    });
                }
            });
        };
        if ((url = the.attr('href')) || (url = the.data('url'))) {
            if (the.hasClass('js-confirm')) {
                layer.confirm('确定执行该操作？', function(index) {
                    layer.close(index);
                    request(url);
                });
            } else {
                request(url);
            }
        };
    }).on('click', '.js-refresh', function(e) {
        e.preventDefault();
        window.location.reload();
    }).on('keyup paste', '.js-only-number', function() {
        var value = $(this).val();
        value = value.replace(/\D/g, '');
        value = value == '' ? '' : parseInt(value, 10);
        $(this).val(value);
    });
});
//--------------------------------------------------------------------------------
// 全选/全不选/行效果
//--------------------------------------------------------------------------------
$(function() {
    $('.form-datetime').datetimepicker({
        format: 'yyyy-mm-dd hh:ii',
        clearBtn: true,
        todayBtn: true,
        autoclose: true,
        minuteStep: 1,
        minView: 0,
        language: 'zh-CN'
    });
    $('.form-date').datetimepicker({
        format: 'yyyy-mm-dd',
        clearBtn: true,
        todayBtn: true,
        autoclose: true,
        minuteStep: 1,
        minView: 2,
        language: 'zh-CN'
    });
});
//--------------------------------------------------------------------------------
// 全选/全不选/行效果
//--------------------------------------------------------------------------------
/*
$(function() {
    var $table = $('#table-content');
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
*/
$(function(){
     $('#table-content').on('click', '.sort', function() {     
        submit.setData('field',$(this).data('field'));
        var sort = 1;
        if($(this).closest('table').data('orderfield') == $(this).data('field')) {
            var sort=$(this).closest('table').data('ordersort');
            sort++;
            if(sort>=3){
                sort=1;
            }
        }
        submit.setData('sort',sort);
        submit.execute();
    });
 });

var commonSort = function($table) {
    $table.find('.sort').each(function(){
        var the = $(this);
        if(the.data('field')==$table.data('orderfield')){
            the.find('.glyphicon').show()
            .toggleClass('glyphicon-arrow-up',$table.data('ordersort')==1)
            .toggleClass('glyphicon-arrow-down',$table.data('ordersort')==2);
        }else{
            the.find('.glyphicon').hide();
        }
    });
};
//--------------------------------------------------------------------------------
// 通用分页事件
//--------------------------------------------------------------------------------
var commonPageEvent = function(submit) {
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
        submit.reset('page', 'condition', function() {
            submit.execute('page', 'condition');
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
};
//文本域长度控制
$(function() {
    $('textarea').on('change', function() {
        var $this = $(this);
        if ($this.val().length > 10000) {
            layer.msg('输入字数过多，截取前一万个字', { shade: 0.1, shadeClose: true }, function() {
                $this.val($this.val().substring(0, 10000));
            });
        }
    });
});
//--------------------------------------------------------------------------------
// 插入到光标处
//--------------------------------------------------------------------------------
/* 在textarea处插入文本--Start */
(function($) {
    $.fn.extend({
        insertContent: function(myValue, t) {
            var $t = $(this)[0];
            if (document.selection) { // ie
                this.focus();
                var sel = document.selection.createRange();
                sel.text = myValue;
                this.focus();
                sel.moveStart('character', -l);
                var wee = sel.text.length;
                if (arguments.length == 2) {
                    var l = $t.value.length;
                    sel.moveEnd("character", wee + t);
                    t <= 0 ? sel.moveStart("character", wee - 2 * t - myValue.length) : sel.moveStart("character", wee - t - myValue.length);
                    sel.select();
                }
            } else if ($t.selectionStart || $t.selectionStart == '0') {
                var startPos = $t.selectionStart;
                var endPos = $t.selectionEnd;
                var scrollTop = $t.scrollTop;
                $t.value = $t.value.substring(0, startPos) + myValue + $t.value.substring(endPos, $t.value.length);
                this.focus();
                $t.selectionStart = startPos + myValue.length;
                $t.selectionEnd = startPos + myValue.length;
                $t.scrollTop = scrollTop;
                if (arguments.length == 2) {
                    $t.setSelectionRange(startPos - t, $t.selectionEnd + t);
                    this.focus();
                }
            } else {
                this.value += myValue;
                this.focus();
            }
        }
    })
})(jQuery);
/* 在textarea处插入文本--Ending */
