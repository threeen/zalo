<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Simulator extends Controller{
    public function index(){
        $sql = "select count(*) from zl_new_accounts";
        $count = Db::query($sql);echo $count;exit;
        $time=strtotime(date('Y-m-d',time()));
        $data = array();
        for($i=0;$i<$count;$i=$i+80){
        $sql_day_count = "select COUNT(new.username) as day_acc,sum(acc.new_friends) as new_fri from zl_accounts acc,zl_new_accounts new  where new.username=acc.username and
                new.id>$i and new.id <= ($i+80) and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1 and unix_timestamp(acc.create_time)>$time";
        $data_day_count = Db::query($sql_day_count);
        $data[]['new_fri'] = $data_day_count[0]['new_fri']; $data[]['day_acc'] = $data_day_count[0]['day_acc'];
        }
        print_r($data);exit;
        return $this->fetch('admin/simulator/simulator');
    }
}