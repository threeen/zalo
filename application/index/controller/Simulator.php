<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Simulator extends Controller{
    public function index(){
        $sql = "select count(*) from zl_new_accounts";
        $count = Db::query($sql);
        $live_acc = array();//单个模拟器同时在线帐号（异常）
        $time=strtotime(date('Y-m-d',time()));
        $data = array();
        $j=0;
        for($i=0;$i<$count[0]['count(*)'];$i=$i+80){
        $sql_day_count = "select COUNT(new.username) as day_acc,sum(acc.new_friends) as new_fri from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1 and unix_timestamp(acc.create_time)>$time";
        $data_day_count = Db::query($sql_day_count);
        $data[$j]['new_fri'] = $data_day_count[0]['new_fri']; $data[$j]['day_acc'] = $data_day_count[0]['day_acc'];$data[$j]['status'] = 1;

            $sql = "select acc.create_time as cr_time,acc.login_status from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1 and unix_timestamp(acc.create_time)>$time";
            $data_status = Db::query($sql);
            foreach($data_status as $key => $value){
                if($value['login_status']==1 && time()>(strtotime($value['cr_time'])+3600)){
                    $data[$j]['status'] = -1;
                    $live_acc [$j]['username']= $value['username'];
                    $live_acc [$j]['create_time']=$value['create_time'];
                }
            }
            $times = array();
            if(count($live_acc)>1){
                    foreach($live_acc as $key => $value){
                        $times []= strtotime($value['create_time']);
                    }
            }
            $j++;
        }

        return $this->fetch('admin/simulator/simulator',[
            'data' => $data,
        ]);
    }
    public function simulator(){
        $id = input('id');
        $this->redirect(url('Operate/group',['id'=>$id]));
    }
}