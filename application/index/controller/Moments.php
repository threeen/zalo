<?php
namespace app\index\controller;
use think\Controller;
use think\Model;

class Moments extends Controller
{
    //返回需要发朋友圈的帐号
    //随机从在线列表帐号中选择一组帐号
    public function re_acc(){
        $num = input('num',0,'intval');
        if(empty($num)){
            return "请求发朋友圈的数量为空";
        }
        $data = model('Accounts')->getPengyouquanAccounts($num);
        $username  =  '';
        foreach($data as $value){
            $username .=$value['username']."#";
        }
        echo $username;
    }
}
