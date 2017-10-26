<?php
namespace app\index\controller;
use think\Controller;
class Operate extends Controller{
    public function index(){
        $data = model('Accounts')->getLiveAccounts();
//        if(!empty($data)){
//
//            foreach($data as $value){
//                if(time()>(strtotime($value['create_time'])+60)){
//                    model('Accounts')->save(['login_status'=>0,'create_time'=>date('Y-m-d H:i:s',time())],['id'=>$value['id']]);
//                }
//            }
//        }
        return $this->fetch('admin/operate/index',[
            'data' => $data
        ]);
    }
    public function moments(){
        $username = input('get.username');
        return $this->fetch('admin/operate/moments',[
            'username' => $username
        ]);
    }
    public function in_moments(){
        $data = array();
        $content = input('post.content');
        $username = input('get.username');
        if(empty($content)){
            return "朋友圈内容为空";
        }else{
            $data = [
                'username' => $username,
                'content' => $content,
            ];
            $result = model('Moments')->add($data);
            if($result){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
        }

    }
    public function group(){
        $data = model('Accounts')->getAccounts();
        $count = model('Accounts')->getCounts();
        $simulator = ceil($count/80)+1;
        $acc = model('Accounts')->getValueArea(0,80);
        return $this->fetch('admin/group/index',[
            'data' => $data,
            'count' => $count,
            'simulator' => $simulator,
            'acc' => $acc
        ]);
    }
    //模拟器数据分组
    public function groupAccounts(){
        $value = input('post.data',1,'intval');
        $start = ($value-1)*80;
        $end = 80;
        $data = model('Accounts')->getValueArea($start,$end);
        echo json_encode($data);
    }
    //数据回传到模拟器
    public function returnData(){
        $id = input('post.id');
        $data = model('NewAccounts')->getNewAccountsData();
        if(!empty($data)){
            $str = '';
            for($i=0;$i<count($data);$i++){
                $str .= $data[$i]['username'] . "#" . $data[$i]['password'] . "#" . $data[$i]['latitude'] . "#" . $data[$i]['longitude']. "#" . $data[$i]['device_num'] . "<br>";
            }
            echo $str;
        }else{
            return ;
        }

    }

}