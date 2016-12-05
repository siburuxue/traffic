// 定义全局变量
var submit;
var approvalWin;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.confirm(msg.info, {
                    btn: ['继续更新', '关闭窗口']
                }, function(index) {
                    layer.close(index);
                    window.location.reload();
                }, function(index) {
                    layer.close(index);
                    parent.layer.close(win_index);
                });
            } else {
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    // 注册提交字段
    $('.post-gather').each(function() {
        var the = $(this);
        submit.reg({
            group: 'gather',
            name: the.attr('name'),
            get: function(name) {
                return the.val();
            },
            set: function(name, value, data) {
                the.val(value);
            }
        });
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        submit.reset();
    });
    function photoTableInit() {
        var cate = $('#cate').val();
        var case_id = $('#case_id').val();
        var data = {cate:cate,case_id:case_id};
        $('#photoTable').parent().load(url.photoList,data);
    }
    $('#tree').treeview({
        data: [
            {
                text:'案件基本信息',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'受案登记表',
                        tags:{cate:1}
                    }
                ]
            },
            {
                text:'现场勘查信息',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'现场图',
                        tags:{cate:3}
                    },
                    {
                        text:'现场照片',
                        tags:{cate:0},
                        nodes:[
                            {
                                text:'方位照相',
                                tags:{cate:5}
                            },
                            {
                                text:'概览照相',
                                tags:{cate:6}
                            },
                            {
                                text:'局部照相',
                                tags:{cate:7}
                            },
                            {
                                text:'元素照相',
                                tags:{cate:8}
                            }
                        ]
                    },
                    {
                        text:'现场勘查笔录',
                        tags:{cate:9}
                    },
                    {
                        text:'现场遗留物品清单',
                        tags:{cate:10}
                    }
                ]
            },
            {
                text:'当事人信息',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'当事人相关文件',
                        tags:{cate:2}
                    }
                ]
            },
            {
                text:'询问笔录、讯问笔录与谈话记录',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'询问笔录',
                        tags:{cate:61}
                    },
                    {
                        text:'讯问记录',
                        tags:{cate:62}
                    },
                    {
                        text:'谈话记录',
                        tags:{cate:63}
                    }
                ]
            },
            {
                text:'检验鉴定',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'提请重新检验鉴定书面材料',
                        tags:{cate:38}
                    },
                    {
                        text:'检验鉴定委托书',
                        tags:{cate:39}
                    },
                    {
                        text:'检验鉴定-延期',
                        tags:{cate:40}
                    },
                    {
                        text:'检验鉴定-超期',
                        tags:{cate:41}
                    },
                    {
                        text:'检验鉴定-延期并超期',
                        tags:{cate:42}
                    },
                    {
                        text:'检验鉴定-告知',
                        tags:{cate:43}
                    },
                    {
                        text:'检验鉴定-鉴定结果',
                        tags:{cate:44}
                    }
                ]
            },
            {
                text:'事故认定',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'简易事故认定',
                        tags:{cate:14}
                    },
                    {
                        text:'逃逸事故认定书',
                        tags:{cate:15}
                    },
                    {
                        text:'逃逸事故认定-书面申请材料',
                        tags:{cate:16}
                    },
                    {
                        text:'逃逸事故认定-送达回执',
                        tags:{cate:17}
                    },
                    {
                        text:'调查报告',
                        tags:{cate:18}
                    },
                    {
                        text:'一般事故认定-事故认定',
                        tags:{cate:19}
                    },
                    {
                        text:'一般事故认定-事故认定-送达回执',
                        tags:{cate:20}
                    },
                    {
                        text:'呈请事故中止',
                        tags:{cate:21}
                    },
                    {
                        text:'道路交通事故证明',
                        tags:{cate:22}
                    },
                    {
                        text:'当事人责任无法认定-道路交通事故证明-送达回执',
                        tags:{cate:23}
                    }
                ]
            },
            {
                text:'处罚',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'公安交通管理行政处罚决定书',
                        tags:{cate:24}
                    },
                    {
                        text:'行政处罚决定书',
                        tags:{cate:25}
                    },
                    {
                        text:'行政拘留回执',
                        tags:{cate:26}
                    },
                    {
                        text:'刑事拘留文书',
                        tags:{cate:27}
                    },
                    {
                        text:'逮捕文书',
                        tags:{cate:28}
                    },
                    {
                        text:'取保候审文书',
                        tags:{cate:29}
                    },
                    {
                        text:'移送起诉文书',
                        tags:{cate:30}
                    }
                ]
            },
            {
                text:'调解',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'调解申请书',
                        tags:{cate:53}
                    },
                    {
                        text:'不调解通知书',
                        tags:{cate:54}
                    },
                    {
                        text:'调解通知书',
                        tags:{cate:56}
                    },
                    {
                        text:'调解记录',
                        tags:{cate:57}
                    },
                    {
                        text:'道路交通事故损害赔偿调解书',
                        tags:{cate:58}
                    },
                    {
                        text:'道路交通事故损害赔偿调解终结书',
                        tags:{cate:59}
                    }
                ]
            },
            {
                text:'复核记录',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'复核受理通知书',
                        tags:{cate:46}
                    },
                    {
                        text:'复核不予受理通知书',
                        tags:{cate:47}
                    },
                    {
                        text:'复核审批意见',
                        tags:{cate:48}
                    },
                    {
                        text:'复核结论',
                        tags:{cate:49}
                    },
                    {
                        text:'复核结论送达通知',
                        tags:{cate:50}
                    },
                    {
                        text:'复核终止',
                        tags:{cate:51}
                    },
                    {
                        text:'复核答复',
                        tags:{cate:52}
                    }
                ]
            },
            {
                text:'归档信息',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'档案借出',
                        tags:{cate:11}
                    },
                    {
                        text:'档案查阅',
                        tags:{cate:12}
                    },
                    {
                        text:'档案复制',
                        tags:{cate:13}
                    }
                ]
            },
            {
                text:'工作记录',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'工作记录',
                        tags:{cate:60}
                    }
                ]
            },
            {
                text:'集体研究记录',
                backColor:"#F5F5F5",
                tags:{cate:0},
                nodes:[
                    {
                        text:'集体研究记录',
                        tags:{cate:55}
                    }
                ]
            }
        ]
    });
    $('#tree').on('nodeSelected',function (event,data) {
        var cateArray = [];
        (function(data,index){
            if(data.tags.cate != 0){
                cateArray.push(data.tags.cate);
                if(index == -1){
                    $('#upload').attr('href',function(){
                        return $(this).data('href') + "&case_id=" + $('#case_id').val() + '&cate=' + data.tags.cate;
                    }).prop('disabled','false').removeAttr('disabled');
                }
            }else{
                $('#upload').attr('href','javascript:;').prop('disabled','true').attr('disabled','disabled');
            }
            if(data.nodes !== undefined){
                for(var i = 0;i < data.nodes.length;i++){
                    arguments.callee(data.nodes[i],i);
                }
                $('#upload').attr('href','javascript:;').prop('disabled','true').attr('disabled','disabled');
            }
        })(data,-1);
        $('#cate').val(cateArray.join());
        photoTableInit();
    });
    //上传图片
    $('#upload').data('end',function(){
        $('#photoTable .col-xs-2').remove();
        photoTableInit();
    });
    //删除图片
    $('#delete').click(function(){
        if($('#photoTable input:checked').size() == 0){
            layer.alert('未选择图片，不能操作！',function(index){
                layer.close(index);
            });
        }else{
            layer.confirm('确定执行该操作？',function(){
                var ids = $('#photoTable input:checked').map(function(){ return $(this).val(); }).get();
                var case_id = $('#case_id').val();
                $.post(url.delete,{ids:ids,case_id:case_id},function(msg){
                    if(msg.status == 1){
                        layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                            $('#photoTable input:checked').parents('.col-xs-2').remove();
                        });
                    }else{
                        layer.alert(msg.info,function(index){
                            layer.close(index);
                        });
                    }
                });
            },function(){

            });
        }
    });
    //排序
    $(document).on('blur','.number',function(){
        var data = {
            id:$(this).parents('.thumbnail').find('input[type=checkbox]').val(),
            train:$(this).val(),
            case_id:$('#case_id').val()
        };
        $.post(url.changeTrain,data,function (msg) {
            var rows = $('.col-xs-2').get();
            rows.forEach(function(v,k){
                v.train = $(v).find('input[type=text]').val();
            });
            rows.sort(function(a,b){
                return a.train > b.train;
            });
            $.each(rows,function(index,row){
                $('#photoTable').append(row);
            });
        });
    });
    //下载
    $('#download').on('click',function(){
        if($('input:checked').size() == 0){
            layer.msg('未选择图片，不能操作',{shade:0.1,shadeClose:true});
        }else{
            var ids = $('input:checked').map(function(){
                return $(this).val();
            }).get().join();
            window.open(url.download + "&case_id=" + $('#case_id').val() + '&ids=' + ids);
        }
    });
    //制作案卷文书
    $('#create-pdf').on('click',function(){
        $.get(url.check + $('#case_id').val(),function(msg){
            if(msg.status == 1){
                window.open(url.createPDf + "&case_id=" + $('#case_id').val());
            }else{
                layer.alert(msg.info,function(index){
                    layer.close(index);
                })
            }
        });

    });
});
