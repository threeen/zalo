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
        $data = input('post.username');
        if(empty($data)){
            return "获取不到已发发朋友圈的帐号";
        }

        $username = $acc = array();
        $username = explode('#',$data);
        print_r($username);exit;
        for($i=0;$i<count($username);$i++){
            $exist_user = model('Pengyouquan')->getOneAccounts($username[$i]);
            if(!$exist_user){
                echo "该帐号".$exist_user['username']."不存在"."<br>";
                continue;
            }else {
                $acc[$i]['username'] = $username[$i];
                $acc[$i]['times'] = $exist_user['times']+1;
                $acc[$i]['create_time'] = date(time());
            }
        }
        $result = model('Pengyouquan')->allowField(true)->saveAll($data);
        if($result){
            echo " 发朋友圈帐号入库成功";
        }
    }
}
