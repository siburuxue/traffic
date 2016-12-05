$(function() {
    // 案件编号
    var caseId = $('input[name="case_id"]').val();
    // 默认案件当事人编号
    var nowCaseClientId = $('input[name="now_case_client_id"]').val();
    nowCaseClientId = nowCaseClientId == 0 ? $('.client-item:first').data('id') : nowCaseClientId;
    console.log(nowCaseClientId);
    // 获取当事人相关人员信息
    var getCaseClientRelater = function(clientId) {
        // 更新当前当事人编号
        nowCaseClientId = clientId;
        // 更新当事人列表样式
        $('.client-item').each(function() {
            var the = $(this);
            if (the.data('id') == clientId) {
                the.find('.collapse').collapse('show');
            }
        });
        // 获取服务器数据
        $.get(url.getCaseClientRelater, {
            'case_client_id': clientId
        }, function(msg) {
            if (msg.status == 1) {
                $('#table-content').empty();
                $.each(msg.info, function(i, item) {
                    addCaseClientRelaterItem(item);
                });
            } else {
                layer.alert(msg.info);
            }
        });
    };
    // 向案件当事人相关人员列表添加一条记录
    var addCaseClientRelaterItem = function(item) {
        var sample = $($('#table-sample').html());
        if (item) {
            sample.find('input[name="selectone"]').val(item.id).prop('disabled', item.add_type == 0);
            sample.find('input[name="name"]').val(item.name).prop('disabled', item.add_type == 0);
            sample.find('select[name="sex"]').val(item.sex).prop('disabled', item.add_type == 0);
            sample.find('input[name="age"]').val(item.age).prop('disabled', item.add_type == 0);
            sample.find('input[name="idno"]').val(item.idno).prop('disabled', item.add_type == 0);
            sample.find('input[name="tel"]').val(item.tel).prop('disabled', item.add_type == 0);
            sample.find('input[name="address"]').val(item.address).prop('disabled', item.add_type == 0);
            sample.find('select[name="relation"]').val(item.relation).prop('disabled', item.add_type == 0);
        }
        sample.appendTo('#table-content');
    };
    // 点击当事人事件
    $('.client-item').on('click', function() {
        getCaseClientRelater($(this).data('id'));
    });
    // 点击添加按钮
    $('#add').on('click', function() {
        addCaseClientRelaterItem();
    });
    // 删除选中
    $('#delete').on('click', function() {
        $('#table-content').find('input[name="selectone"]:enabled:checked').each(function() {
            $(this).closest('.relater-item').remove();
        });
    });
    // 重置
    $('#reset').on('click', function() {
        getCaseClientRelater(nowCaseClientId);
    });
    // 提交
    $('#submit').on('click', function() {
        var data = new Array();
        $('#table-content').find('.relater-item').each(function() {
            var the = $(this);
            var item = {
                'id': the.find('input[name="selectone"]').val(),
                'name': the.find('input[name="name"]').val(),
                'sex': the.find('select[name="sex"]').val(),
                'age': the.find('input[name="age"]').val(),
                'idno': the.find('input[name="idno"]').val(),
                'tel': the.find('input[name="tel"]').val(),
                'address': the.find('input[name="address"]').val(),
                'relation': the.find('select[name="relation"]').val()
            };
            data.push(item);
        });
        $.post(url.update, {
            case_client_id: nowCaseClientId,
            item: data
        }, function(msg) {
            if (msg.status == 1) {
                layer.msg(msg.info, {
                    shade: 0.1,
                    shadeClose: true
                }, function(index) {
                    layer.close(index);
                    getCaseClientRelater(nowCaseClientId);
                });
            } else {
                layer.alert(msg.info);
            }
        });
    });
    // 初始化
    getCaseClientRelater(nowCaseClientId);
});
