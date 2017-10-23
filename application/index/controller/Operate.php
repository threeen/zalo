<?php
namespace app\index\controller;
use think\Controller;
class Operate extends Controller{
    public function index(){
        return $this->fetch('admin/operate/index');
    }
    public function moments(){
        return $this->fetch('admin/operate/moments');
    }
    public function xianshi(){
//        $data=array('data'=>'chenwenzheng');
//        $ch = curl_init ();
//        curl_setopt ($ch, CURLOPT_URL, 'http://192.168.11.131:8383/test.php');
//        curl_setopt ($ch, CURLOPT_POST, true );
//        curl_setopt ($ch, CURLOPT_HEADER, 0 );
//        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1 );
//        curl_setopt ($ch, CURLOPT_POSTFIELDS, $data );
//        $state=curl_exec ($ch );
//        curl_close ($ch );
//        return $state;

        $data = input('post.content');
        echo $data;
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
//    public function returnData(){
//        $data = model('Accounts')->getAccountsData();
//        $arr = array();$str = '';
//        foreach($data as $key=>$value){
//            $arr[]=$value;
//        }
//        foreach($arr as $value){
//            $str .=$value."#";
//        }
//        $str =rtrim($str,'#');
//        echo $str;
//    }

}