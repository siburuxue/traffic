$(function() {
    $('.js-end-refresh').data('end', function() {
        window.location.reload();
    });
    $('.js-end-refresh1').data('end', function() {
        var the = $(this);
        layer.open({
            type: 2,
            title: '',
            closeBtn: 0,
            shadeClose: false,
            shade: 0.8,
            scrollbar: false,
            move: false,
            area: ['100%', '100%'],
            content: the.data('address'),
            end: function() {
                window.location.reload();
            }
        });
    });
    $(document).on('click', '.js-ajax-open', function(e) {
        e.preventDefault();
        var url, the = $(this),
            that = this;
        var success = function(msg, status, xhr) {
            layer.open({
                type: 2,
                title: '',
                closeBtn: 0,
                shadeClose: false,
                shade: 0.8,
                scrollbar: false,
                move: false,
                area: ['100%', '100%'],
                content: msg.url,
                end: function() {
                    var endfunc = the.data('end');
                    if ($.type(endfunc) == 'function') {
                        endfunc.call(that, msg, status, xhr);
                    }
                }
            });
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
    });
});
