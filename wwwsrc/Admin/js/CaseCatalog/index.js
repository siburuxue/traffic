// 网页加载完毕
$(function() {
    if(json != ''){
        $('#tree').treeview({
            data: JSON.parse(json),
            showTags:true,
        });
    }
});
