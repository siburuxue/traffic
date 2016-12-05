// 定义全局变量
var submit;
var backWin;
var the;
var status;
var backTime;
// 页面加载完毕
$(function() {
    //当事人取数据
    $('.client-list-head').on('click',function(){
        var $this = $(this);
        var id = $(this).attr('id');
        var caseId = $('#case_id').val();
        $.post(url.getClientInfo,{id:id,case_id:caseId},function(data){
            //清空画面所有的数据
            $('input[type=checkbox]').prop('checked',false);
            $('input[type=text]').val('');
            $('select option:eq(0)').prop('selected',true);
            $('input[type=checkbox]').prop('checked',false);
            $('#info1 tr,#info2 tr,#info3 tr').remove();
            $('#punish_money,#punish_score,#punish_seize_time,#punish_revoke_time,#punish_detain_time').prop('disabled',true);
            $('#detain_name').prop('disabled',true);
            $('#case-photo-content .col-xs-2').remove();
            //展开当前当事人列表
            $this.find('.collapse').collapse('show');
            //当事人信息
            var info = data['info'];
            //违法行为及条款
            var lawList = data['lawList'];
            //相关人员信息
            var relationList = data['relationList'];
            //扣押物品列表
            var detainList = data['detainList'];
            //采血管
            var bloodList = data['bloodList'];
            //图片
            var photoList=  data['photoList'];
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
            //当事人是否呼吸检验
            $('input[name=is_checked_breath]').prop('checked',info['is_checked_breath'] == 1);
            //当事人呼吸检测数值
            $('#breath_val').val(info['breath_val']);
            //当事人是否提取血样
            $('input[name=is_checked_blood]').prop('checked',info['is_checked_blood'] == 1);
            //当事人提取血样时间
            $('#blood_time').val(info['blood_time']);
            //采血管编号
            if(bloodList.length > 0){
                $('#code1').val(bloodList[0]['code']);
                $('#code2').val(bloodList[1]['code']);
            }
            //当事人是否检测尿样
            $('input[name=is_checked_urine]').prop('checked',info['is_checked_urine'] == 1);
            //当事人尿样结论
            $('#urine_result').val(info['urine_result']);
            //当事人扣留时间
            $('#detain_time').val(info['detain_time']);
            //当事人扣留 停车场
            if(info['detain_parking'] != ""){
                $('#detain_parking').val(info['detain_parking']);
            }
            //当事人强制措施凭证编号
            $('#detain_force_id').val(info['detain_force_id']);
            //当事人是否同时扣留行驶证
            $('input[name=detain_driver_licence]').prop('checked',info['detain_driver_licence'] == 1);
            //当事人返还时间
            $('#detain_return_time').val(info['detain_return_time']);
            //当事人是否警告
            $('input[name=punish_is_warning]').prop('checked',info['punish_is_warning'] == 1);
            //当事人是否罚款
            $('input[name=punish_is_fine]').prop('checked',info['punish_is_fine'] == 1);
            //当事人罚款金额
            $('#punish_money').val(info['punish_money']);
            //当事人扣分
            $('#punish_score').val(info['punish_score']);
            //当事人是否暂扣
            $('input[name=punish_is_seize]').prop('checked',info['punish_is_seize'] == 1);
            //当事人暂扣时长
            $('#punish_seize_time').val(info['punish_seize_time']);
            //当事人是否吊销
            $('input[name=punish_is_revoke]').prop('checked',info['punish_is_revoke'] == 1);
            //当事人吊销时长
            if(info['punish_revoke_time'] != ""){
                $('#punish_revoke_time').val(info['punish_revoke_time']);
            }
            //当事人是否拘留
            $('input[name=punish_is_detain]').prop('checked',info['punish_is_detain'] == 1);
            //当事人拘留天数
            $('#punish_detain_time').val(info['punish_detain_time']);
            //当事人案件类别
            if(info['criminal_case_type'] != ""){
                $('#criminal_case_type').val(info['criminal_case_type']);
            }
            //当事人刑事强制措施
            if(info['criminal_measure'] != ""){
                $('#criminal_measure').val(info['criminal_measure']);
            }

            //画面赋值 违法行为及条款
            lawList.forEach(function(x){
                $('#info1').append($('#tempalte1').html());
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
            //画面赋值 相关人员信息
            relationList.forEach(function(x){
                $('#info2').append($('#template2').html());
                $('#info2 tr:last').find('input[name=relater_name]').val(x['name']);
                $('#info2 tr:last').find('select[name=relater_sex]').val(x['sex']);
                $('#info2 tr:last').find('input[name=relater_age]').val(x['age']);
                $('#info2 tr:last').find('input[name=relater_idno]').val(x['idno']);
                $('#info2 tr:last').find('input[name=relater_tel]').val(x['tel']);
                $('#info2 tr:last').find('input[name=relater_address]').val(x['address']);
                $('#info2 tr:last').find('select[name=relater_relation]').val(x['relation']);
                $('#info2 tr:last').find('input[class=relater_id]').val(x['id']);
            });
            //扣押物品列表
            $('#info3').append(function(){
                var str = '';
                detainList.forEach(function(x){
                    str += '<tr>';
                    str += '<td>' + x['code'] + '</td>';
                    str += '<td>' + x['name'] + '</td>';
                    str += '<td>' + x['format'] + '</td>';
                    str += '<td>' + x['feature'] + '</td>';
                    str += '<td>' + x['num'] + '</td>';
                    str += '<td>' + x['owner'] + '</td>';
                    str += '<td>' + x['contractor'] + '</td>';
                    str += '<td>' + x['detain_time'] + '</td>';
                    str += '<td>' + x['return_time'] + '</td>';
                    str += '<td>' + (x['status'] == 0 ? '已扣押' : '已返还') + '</td>';
                    str += '</tr>';
                });
                return str;
            });
            //图片
            $('#case-photo-content').prepend(function(){
                var str = '';
                photoList.forEach(function(v){
                    str += '        <div class="col-xs-2">';
                    str += '            <a href="' + v['image_path'] + '" class="thumbnail" target="_blank">';
                    str += '                <img src="' + v['thumb_path'] + '">';
                    str += '                <label style="display: block;text-align: center;margin-top: 9px;"><input type="checkbox" value="' + v['id'] + '"></label>';
                    str += '            </a>';
                    str += '        </div>';
                });
                return str;
            });
            $('#case-photo-box').data('ida',id);
        },'json');
    });
    //画面初始化
    $('.client-list-head[id=' + $('#id').val() + ']').click();
    $('#case-photo-upload,#case-photo-delete,#case-photo-close').remove();
    /**************************************************************************************************/
});
