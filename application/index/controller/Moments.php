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
            $username .=$value['username']."@";
        }
        return $username;
    }
    //对已经发过朋友圈的帐号进行存储记录
    public function acc_insert(){
        $data = input('username');
        if(empty($data)){
            return "获取不到已发发朋友圈的帐号";
        }
        $username = $acc = array();
        $username = explode('@',$data);
        foreach($username as $key=>$value){
            $exist_user = model('NewAccounts')->getOneAccounts($value);
            if(!$exist_user){
                echo "该帐号".$exist_user['username']."不存在"."<br>";
                continue;
            }else {
                $circle = model('Circle')->getOneAccounts($value);
                if($circle){
                    $name = $value;
                    $times = $circle['times']+1;
                    $time = date('Y-m-d H:i:s',time());
                    model('Circle')->update(['times'=>$times,'create_time'=>$time],['username'=>$name]);
                    continue;
                }else{
                    $acc[$key]['username'] = $value;
                    $acc[$key]['times'] = 1;
                    $acc[$key]['create_time'] = date('Y-m-d H:i:s',time());
                }
            }
        }
        $result = model('Circle')->allowField(true)->saveAll($acc);
        //echo "更新成功";
    }
}
