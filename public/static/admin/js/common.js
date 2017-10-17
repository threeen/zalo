function zalo_del(url){
     layer.confirm('确认要删除吗？',function(index){
         window.location.href=url;
     })
}
function zalo_regain(url){
    layer.confirm('确认要重新启用吗？',function(index){
        window.location.href=url;
    })
}
function zalo_edit(title,url){
    var index=layer.open({
        type:2,
        title:title,
        content:url
    });
    layer.full(index);
}