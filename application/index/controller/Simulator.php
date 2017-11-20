<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Simulator extends Controller{
    public function index(){
        $sql = "select count(*) from zl_new_accounts";
        $count = Db::query($sql);
        $time=strtotime(date('Y-m-d',time()));
        $data = array();
        $j=0;
        for($i=0;$i<$count[0]['count(*)'];$i=$i+80){
        $sql_day_count = "select COUNT(new.username) as day_acc,sum(acc.new_friends) as new_fri from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1 and unix_timestamp(acc.create_time)>$time";
        $data_day_count = Db::query($sql_day_count);
        $data[$j]['new_fri'] = $data_day_count[0]['new_fri']; $data[$j]['day_acc'] = $data_day_count[0]['day_acc'];

            $sql = "select acc.create_time as cr_time from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1 and unix_timestamp(acc.create_time)>$time";
            $data = Db::query($sql);
            print_r($data);exit;

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