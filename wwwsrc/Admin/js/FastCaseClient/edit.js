// 定义全局变量
var submit;
var backWin;
var the;
var status;
var backTime;
// 页面加载完毕
$(function() {
    /**************************************************************************************************/
    // 创建提交对象
    submit = $.vmcSubmit();
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
    // 发送POST请求
    submit.success = function(data) {
        $.post(url.update, data, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info,{shade:0.1,shadeClose:true},function(){
                    window.location.href = msg.url;
                });
            }else{
                layer.alert(msg.info, function(index) {
                    layer.close(index);
                });
            }
        });
    };
    //注册违法行为及条款
    submit.reg({
        group: 'gather',
        name: "law",
        get: function(name) {
            return $('#info1 tr').map(function(){
                return {
                    "law_pid":$(this).find('select[name=law_pid]').val(),
                    "law_id":$(this).find('select[name=law_id]').val()
                };
            }).get();
        },
        set: function(name, value, data) {

        }
    });
    // 提交
    $('#submit').on('click', function() {
        submit.execute('gather');
    });
    // 重置
    $('#reset').on('click', function() {
        $('.client-list-head[id=' + client_id + ']').click();
        submit.reset();
    });
    //违法行为及条款 添加
    $('#add1').on('click',function(){
        $('#info1').append($('#tempalte1').html());
    });
    //违法行为及条款 删除
    $(document).on('click','.del1',function(){
        $(this).parents('tr').remove();
    });
    /**************************************************************************************************/
    //大类联动小类
    $(document).on('change','select[name="law_pid"]',function(){
        var $this = $(this);
        var pid = $(this).val();
        if(pid == ""){
            $(this).parent().next().find('select option:gt(0)').remove();
        }else{
            $.post(url.getInfo,{pid:pid,case_id:$('#case_id').val()},function(data){
                $this.parent().next().find('select option:gt(0)').remove();
                $this.parent().next().find('select').append(function(){
                    var str = '';
                    for(var i = 0;i < data.length;i++){
                        str += "<option value=" + data[i]['id'] + ">" + data[i]['title'] + "</option>";
                    }
                    return str;
                });
            },"json");
        }
    });
    //当事人取数据
    $('.client-list-head').on('click',function(){
        var $this = $(this);
        var id = $(this).attr('id');
        var caseId = $('#case_id').val();
        $.post(url.getClientInfo,{id:id,case_id:caseId},function(data){
            //清空画面所有的数据
            $('input[type=text]').val('');
            $('select option:eq(0)').prop('selected',true);
            $('#info1 tr').remove();
            //展开当前当事人列表
            $this.find('.collapse').collapse('show');
            //当事人信息
            var info = data['info'];
            //违法行为及条款
            var lawList = data['lawList'];
            //画面赋值(当事人信息部分)
            //当事人主键
            $('#id').val(info['id']);
            //当事人姓名
            $('#name').val(info['name']);
            //当事人性别
            $('select[name=sex]').val(info['sex']);
            //当事人证件类型
            $('#id_type').val(info['id_type']);
            //当事人证件号码
            $('#idno').val(info['idno']);
            //当事人年龄
            $('#age').val(info['age']);
            //当事人联系方式
            $('#tel').val(info['tel']);
            //当事人驾驶证种类
            $('#driver_licence_type').val(info['driver_licence_type']);
            //当事人初次领证日期
            $('#first_driver_licence_time').val(info['first_driver_licence_time']);
            //当事人交通方式
            $('#traffic_type').val(info['traffic_type']);
            //当事人牌号种类
            if(info['grade_type'] != ""){
                $('#grade_type').val(info['grade_type']);
            }
            //当事人车牌号码
            $('#car_no').val(info['car_no']);
            //当事人事故责任类型
            $('#blame_type').val(info['blame_type']);
            //当事人伤害程度
            $('#hurt_type').val(info['hurt_type']);
            //当事人死亡时间
            $('#death_time').val(info['death_time']);
            //当事人是否逃逸
            $('select[name=is_escape]').val(info['is_escape']);
            //当事人查获逃逸人时间
            $('#escape_catch_man_time').val(info['escape_catch_man_time']);
            //当事人查获逃逸车辆时间
            $('#escape_catch_car_time').val(info['escape_catch_car_time']);
            //当事人现住址
            $('#address').val(info['address']);
            //画面赋值 违法行为及条款
            lawList.forEach(function(x){
                $('#add1').click();
                $('#info1 tr:last').find('select[name=law_pid]').val(x['law_pid']);

                $('#info1 tr:last').find('select[name=law_id]')
                                    .append(function(){
                                        var str = '';
                                        x['law_id_list'].forEach(function(item,key){
                                            str += '<option value="' + item['id'] + '">' + item['title'] + '</option>';
                                        });
                                        return str;
                                    })
                                    .val(x['law_id']);
            });
        },'json');
    });
    //画面初始化
    $('.client-list-head[id=' + $('#id').val() + ']').click();
});
