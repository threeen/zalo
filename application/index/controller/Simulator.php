<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Simulator extends Controller{
    public function index(){

        $time1 = strtotime(date("Y-m-d 00:00:00",strtotime("-1 day")));//前一天0点
        $time2 = strtotime(date("Y-m-d 24:00:00",strtotime("-1 day")));//前一天24点
        $sql = "select count(*) from zl_new_accounts";
        $count = Db::query($sql);
        $live_acc = array();//单个模拟器同时在线帐号（异常）
        $time=strtotime(date('Y-m-d',time()));
        $data = array();
        $j=0;
        for($i=0;$i<$count[0]['count(*)'];$i=$i+80){
        $sql_day_count = "select COUNT(new.username) as day_acc,acc.username,acc.create_time,sum(acc.new_friends) as new_fri from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <=($i+80)and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1  and (unix_timestamp(acc.create_time)-16*3600)>= $time";
        $data_day_count = Db::query($sql_day_count);
        $data[$j]['new_fri'] = $data_day_count[0]['new_fri']; $data[$j]['day_acc'] = $data_day_count[0]['day_acc'];$data[$j]['status'] = 1;
            $sql = "select acc.username,acc.create_time as cr_time,acc.login_status from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1  ORDER by acc.create_time DESC limit 1";
            $data_status = Db::query($sql);
            foreach($data_status as $key => $value){
                if(time()>(strtotime($value['cr_time'])+3600)){
                    $data[$j]['status'] = -1;
                }
            }
            $sql_yesterday = "select count(acc.username) as yes_count_username,sum(acc.new_friends) as yes_new_friend from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1  and (unix_timestamp(acc.create_time)-16*3600)>= $time1 and (unix_timestamp(acc.create_time)-16*3600)< $time2";
            $data_yesterday = Db::query($sql_yesterday);
            $data[$j]['yes_count_username']=$data_yesterday[0]['yes_count_username'];
            $data[$j]['yes_new_friend'] = $data_yesterday[0]['yes_new_friend'];
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
    public function resetErrorAccounts(){
        $time=strtotime(date('Y-m-d',time()));
        $sql = "select acc.username,acc.create_time as cr_time,acc.login_status from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                 acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1 and acc.login_status=1";
        $data_status = Db::query($sql);
        foreach($data_status as $key => $value){
            if($value['login_status']==1 && time()>(strtotime($value['cr_time'])+3600)){
                 model('Accounts')->update(['login_status'=>0],['username'=>$value['username']]);
            }
        }
        $this->redirect(url('Simulator/index'));
    }
}