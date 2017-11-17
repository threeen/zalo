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
        return $username;
    }
    //对已经发过朋友圈的帐号进行存储记录
    public function acc_insert(){
        $data = input('username');
        if(empty($data)){
            return "获取不到已发发朋友圈的帐号";
        }

        $username = $name = $acc = array();
        $username = explode('@',$data);
        //print_r($username);exit;
        for($i=0;$i<count($username);$i++){
            $exist_user = model('NewAccounts')->getOneAccounts($username[$i]);
            if(!$exist_user){
                echo "该帐号".$exist_user['username']."不存在"."<br>";
                continue;
            }else {
                $circle = model('Circle')->getOneAccounts($username[$i]);
                if($circle){
                    $name[] = $username[$i];
                    $acc[$i]['times'] = $circle['times']+1;
                    $acc[$i]['create_time'] = date('Y-m-d H:i:s',time());
                }else{
                    $name[]='';
                    $acc[$i]['username'] = $username[$i];
                    $acc[$i]['times'] = 1;
                    $acc[$i]['create_time'] = date('Y-m-d H:i:s',time());
                }
            }
        }
        $result = model('Circle')->allowField(true)->saveAll($acc,$name);
        if($result){
            echo " 发朋友圈帐号入库成功";
        }else{
            echo "发朋友圈帐号入库失败";
        }
    }
}
