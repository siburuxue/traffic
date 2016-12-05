;
(function($, undefined) {
    $.fn.vselect = function(settings) {
        var run = $.type(settings) === 'string',
            args = [].slice.call(arguments, 1);
        if (!this.length) return;
        if (run && settings === 'value') {
            if (args.length) {
                return this.each(function() {
                    var instance = $(this).data('mimic');
                    instance && vselect.prototype.setValue.apply(instance, args);
                });
            } else {
                var instance = $(this[0]).data('mimic');
                return instance ? vselect.prototype.getValue.apply(instance) : undefined;
            }
        }
        return this.each(function() {
            var $element = $(this);
            var instance = $element.data('mimic');
            if (run && settings.charAt(0) !== '_' && instance) {
                vselect.prototype[settings] && vselect.prototype[settings].apply(instance, args);
            } else if (!run && !instance) {
                var instance = new vselect($element, settings);
                instance._init();
                $element.data('mimic', instance);
            }
        });
    };
    //**************************************************************************************************************
    // 构造函数
    var vselect = function($element, settings) {
        this.options = $.extend({}, this.options, settings);
        this.elem = {
            win: $(window),
            doc: $(document),
            body: $(document.body),
            handle: $element,
            target: $($element.data('target'))
        };
        this.data = {
            ids: [],
            names: []
        };
        // 下拉菜单显示状态
        this.state = 0;
    };
    //**************************************************************************************************************
    // 配置参数
    vselect.prototype.options = {};
    //**************************************************************************************************************
    // 更新选项
    vselect.prototype._init = function() {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        $elem.handle.on('click', function() {
            if ($this.state == 1) {
                $this.close();
            } else {
                $this.open();
            }
        });
        $elem.target.on('click', '.list-group-item', function() {
            var the = $(this);
            if (the.hasClass('disabled')) {
                return false;
            }
            var status = Math.abs(the.data('checked') - 1);
            $this._renderOption(the, status);
        }).on('click', '.sd-submit', function() {
            $this._getData();
            $this.close();
            $elem.handle.trigger('vsChange');
        }).on('click', '.sd-close', function() {
            $this.close();
        });
        $elem.win.on('resize', function() {
            $this._setSize();
        });
        $elem.doc.on('click', function(e) {
            if (!$(e.target).is($elem.handle) && !$(e.target).closest('.panel').is($elem.target)) {
                $this.close();
            }
        });
        $this._setData();
        $this._setSize();
    };
    // 打开下拉菜单
    vselect.prototype.open = function() {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        if ($elem.handle.hasClass('disabled')) {
            return;
        }
        $this.state = 1;
        $elem.target.show();
    };
    // 关闭下拉菜单动作
    vselect.prototype.close = function() {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        $this.state = 0;
        $this._setData();
        $elem.handle.val($this.data.names.toString());
        $elem.target.hide();
    };
    // 取值
    vselect.prototype.getValue = function(value) {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        return $this.data.ids;
    };
    vselect.prototype.setValue = function(value) {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        if ($.type(value) === 'string' && $.trim(value) !== '') {
            value = value.split(',');
        } else if ($.type(value) === 'array') {
            //
        } else {
            value = [];
        }
        var newVal = [];
        for (var i = 0; i < value.length; i++) {
            newVal.push(parseInt(value[i], 10));
        }
        $this.data.ids = newVal;
        $this._setData();
        $this._getData();
        $elem.handle.val($this.data.names.toString());
    };
    // 从界面同步到data
    vselect.prototype._getData = function() {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        var ids = new Array;
        var names = new Array;
        $elem.target.find('.list-group-item').each(function() {
            var the = $(this);
            if (the.data('checked') == 1) {
                ids.push(the.data('id'));
                names.push(the.data('name'));
            }
        });
        $this.data = {
            ids: ids,
            names: names
        };
        return $this.data;
    };
    // 将data数据同步到界面
    vselect.prototype._setData = function() {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        var data = $this.data;
        $elem.target.find('.list-group-item').each(function() {
            var the = $(this);
            if ($.inArray(the.data('id'), data.ids) >= 0) {
                $this._renderOption(the, 1);
            } else {
                $this._renderOption(the, 0);
            }
        });
    };
    // 渲染选中状态
    vselect.prototype._renderOption = function(the, status) {
        the.data('checked', status).toggleClass('list-group-item-info', status ? true : false);
        the.find('.sd-icon').toggleClass('glyphicon-check', status ? true : false).toggleClass('glyphicon-unchecked', status ? false : true);
    };
    // 设置尺寸
    vselect.prototype._setSize = function() {
        var $this = this,
            $elem = $this.elem,
            opts = $this.options;
        var top = $elem.handle.offset().top + $elem.handle.outerHeight();
        var left = $elem.handle.offset().left;
        var width = $elem.handle.outerWidth();
        $elem.target.css({
            width: width,
            top: top,
            left: left
        });
    };
})(jQuery);
