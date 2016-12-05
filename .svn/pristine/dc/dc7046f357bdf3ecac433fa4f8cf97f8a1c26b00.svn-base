$(function(){
    var largeMonth = [1,3,5,7,8,10,12];
    var smallMonth = [4,6,9,11];
    /*获取当前年月日*/
    var date = new Date();
    var currendMonth = date.getMonth() + 1;
    var currentYear = date.getFullYear();
    var currentDay = date.getDate();
    //获取当前月份的天数
    function getMonthDays(year,month){
        if(month == 2){
            return year % 4 == 0 ? 29 : 28;
        }else{
            return largeMonth.indexOf(month) != -1 ? 31 : 30;
        }
    }
    //获取当前日期是星期几
    function getWeek(year,month,day){
        var date = new Date(year + '-' + month + '-' + day);
        return date.getDay();
    }
    //生成日期控件
    function createCalendar(year,month,day){
        $('.rows .cell').remove();
        var days = getMonthDays(year,month);
        var weekend = getWeek(year,month,1);
        $.post(url.getDays,{year:year,month:month},function(data){
            var holidays = data.days.split(',');
            $('.rows').append(function(){
                var str = '';
                for(var i = 0;i < weekend;i++){
                    str += '<div class="cell"></div>';
                }
                for(var j = 1;j <= days;j++){
                    if(j == currentDay && year == currentYear && month == currendMonth){
                        if(holidays.indexOf(j.toString()) !== -1){
                            str += '<div class="cell active red">' + j +'</div>';
                        }else{
                            str += '<div class="cell active">' + j +'</div>';
                        }
                    }else{
                        if(holidays.indexOf(j.toString()) !== -1){
                            str += '<div class="cell red">' + j +'</div>';
                        }else{
                            str += '<div class="cell">' + j +'</div>';
                        }
                    }
                }
                var lastDays = 7 - (days + weekend) % 7;
                if(lastDays == 7){
                    lastDays = 0;
                }
                for(var k = 0;k < lastDays;k++){
                    str += '<div class="cell"></div>';
                }
                return str;
            });
        },'json');
    }
    //年份，月份change事件
    $('#year,#month').on('change',function(){
        var year = parseInt($('#year').val());
        var month = parseInt($('#month').val());
        createCalendar(year,month,null);
    });
    //画面加载初始化日历
    createCalendar(currentYear,currendMonth,currentDay);
    //画面加载设置年、月
    $('#year').val(currentYear);
    $('#month').val(currendMonth);
    //点击画面提示信息
    $(document).on('click','.cell:not(:empty)',function(){
        var $this = $(this);
        var blColor = $(this).css('color') == 'rgb(255, 0, 0)';
        var day = $(this).text();
        var year = $('#year').val();
        var month = $('#month').val();
        var currentDateObj = new Date(currentYear + '-' + currendMonth + '-' + currentDay);
        var selectDateObj = new Date(year + '-' + month + '-' + day);
        var blDate = Date.parse(selectDateObj) < Date.parse(currentDateObj);
        if(blDate){
            layer.alert('当前日期之前的日期不能改变节假日状态',function(index){
                layer.close(index);
            });
        }else{
            var blHasClass = $this.hasClass('red');
            var msg = blHasClass?'是否取消节假日':'是否设置成节假日';
            layer.confirm(msg,function(index){
                layer.close(index);
                var data = {
                    year:$('#year').val(),
                    month:$('#month').val(),
                    day:$this.text()
                };
                if(blColor){
                    data['is_holidays'] = 0;
                    $this.removeClass('red');
                }else{
                    data['is_holidays'] = 1;
                    $this.addClass('red');
                }
                $.post(url.update,data);
            },function(index){
                layer.close(index);
            });
        }
    });
});