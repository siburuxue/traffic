// 定义全局变量
var submit;
// 网页加载完毕
$(function() {
    // 创建提交对象
    submit = $.vmcSubmit();
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.check, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    window.location.href = msg.url;
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


});


$(function(){
    if(type==1){
        $('.read_only').attr("disabled","disabled");
    }
   //审批状态改变
    $('#status').on('change',function(){
        if(level != "2"){
            if($(this).val() == '1'){
                $('#submit').prop('disabled',true);
                $('#approval-sure').prop('disabled',false);
            }else{
                $('#submit').prop('disabled',false);
                $('#approval-sure').prop('disabled',true);
            }
        }
    });


       //提审按钮
    $('#approval-sure').on('click', function() {
        var the = $(this);
        var id = the.data('id');
        approvalWin = layer.open({
            type: 1,
            closeBtn: 0,
            area: ['100%', '100%'],
            content: $('#approval-box'),
            zIndex: 1,
            title: false,
            end: function(){
                parent.layer.close(index);
                window.location.reload();
            }
        });
    });
    //提审提交按钮
    $('#approval-submit').on('click', function(){
        var data = {
            id:$('#id').val(),
            origin_id:$('#origin_id').val(),
            check_user_id:$('input[type=radio]:checked').val(),
            remark:$('#remark').val()
        };

        if($('input[type=radio]:checked').val()){
         $.post(url.checkApprove,data,function(msg){
            layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index){
                layer.close(index);
                if(msg.status == 1){
                    parent.layer.close(index);
                    $('.js-close').trigger("click");
                    //window.location.reload();
                }
            });
        });           
     }else{
        layer.alert('请选择审核人', function(index) {
            layer.close(index);
        });
     }

    });
    //提审关闭按钮
    $('#approval-close').on('click', function() {
        layer.close(approvalWin);
        var dialog = $('#approval-box');
        dialog.find('input[type=radio]').prop('checked',false).filter(':eq(0)').prop('checked',true);
    });

});


// // 鉴定对象联动 自动加载
// $(function() {
//     //获取部门id
//     var type_id = $('#car-people-other').children('option:selected').val();
//     var case_id = $('#case_id').val();  
//     if (type_id == 3) {
//         // $('#car-people-other-child').removeClass('post-gather');
//         $('#car-people-other-child').hide(100);
//         // $('#type_other').addClass('post-gather');
//         $('#type_other').show();
//     } else {
//         // $('#car-people-other-child').addClass('post-gather');
//         $('#car-people-other-child').show();
//         // $('#type_other').removeClass('post-gather');
//         $('#type_other').hide();
//     }

//     $.post(url.getTypeChild, { 'type_id': type_id, 'case_id': case_id }, function(msg) {
        
//         if (msg.status == 1) {
//             //鉴定对象子选项
//             var obj = $('#car-people-other-child')[0];
//             var allChildren = msg.info.objc;
//             obj.options.length = 1;
//             for (var i = 0; i < allChildren.length; i++){
//                 if (type_id == 1) {
//                     obj.options.add(new Option(allChildren[i].case_client_name, allChildren[i].case_client_id));

//                 }
//                 if (type_id == 2) {
//                     if (allChildren[i].case_client_car_no == "") {
//                         allChildren[i].case_client_car_no = "未填写";
//                     }
//                     obj.options.add(new Option(allChildren[i].case_client_car_no, allChildren[i].case_client_id));

//                 }

//             }
//             //自动选择当前人员 或车辆
//             var showLabel = document.getElementById("car-people-other-child");
//             for (var i = 0; i < showLabel.length; i++) {
//                 if (showLabel[i].value == $('#target_case_client_id').val()) {
//                     //showLabel[i].selected = "selected";
//                     $("select[name='target_case_client_id'] option[value=" + $('#target_case_client_id').val() + "]").attr("selected", true);

//                 }
//             }


//             //鉴定类型
//             var obj_type = $('#checktpye_child')[0];
//             var checktpyeChild = msg.info.checktype;
//             obj_type.options.length = 1;

//             for (var n = 0; n < checktpyeChild.length; n++) {

//                 if (checktpyeChild[n].name == "") {
//                     checktpyeChild[n].name = "未填写";
//                 }
//                 obj_type.options.add(new Option(checktpyeChild[n].name, checktpyeChild[n].id));

//             }

//             //自动选择当前鉴定类型
//             var showLabelAgain = document.getElementById("checktpye_child");
//             for (var i = 0; i < showLabelAgain.length; i++) {
//                 if (showLabelAgain[i].value == $('#checkup_org_item_id').val()) {
//                     //showLabelAgain[i].selected = "selected";
//                     $("select[name='checkup_org_item_id'] option[value=" + $('#checkup_org_item_id').val() + "]").attr("selected", true);


//                 }
//             }
//             // 鉴定机构名称联动 自动加载
//             var org_type = $("select[name='checkup_org_item_id']").val();
//             $.post(url.getOrgChild, { 'org_type': org_type }, function(msg) {
//                 if (msg.status == 1) {
//                     //鉴定对象子选项
//                     var obj = $('#org_type')[0];
//                     var allChildren = msg.info;
//                     obj.options.length = 1;
//                     for (var m = 0; m < allChildren.length; m++) {
//                         obj.options.add(new Option(allChildren[m].checkuporg_name, allChildren[m].checkuporg_id));
//                     }

//                     //自动选择当前鉴定机构名称
//                     var showLabelOrg_type = document.getElementById("org_type");
//                     for (var i = 0; i < showLabelOrg_type.length; i++) {
//                         if (showLabelOrg_type[i].value == $('#checkup_org_id').val()) {
//                             //showLabelAgain[i].selected = "selected";
//                             $("select[name='checkup_org_id'] option[value=" + $('#checkup_org_id').val() + "]").attr("selected", true);
//                         }
//                     }
//                 }
//             });
//         }
//     });


// });


// // 鉴定机构名称联动
// $(function() {
//     $('#checktpye_child').on('change', function() {

//         var org_type = $('#checktpye_child').val();
//         $.post(url.getOrgChild, { 'org_type': org_type }, function(msg) {
//             if (msg.status == 1) {
//                 //鉴定对象子选项
//                 var obj = $('#org_type')[0];
//                 var allChildren = msg.info;
//                 obj.options.length = 1;
//                 for (var m = 0; m < allChildren.length; m++) {
//                     obj.options.add(new Option(allChildren[m].checkuporg_name, allChildren[m].checkuporg_id));
//                 }
//             }
//         });
//     });
// });

