<?php

namespace app\common\model;

use think\Model;

class NewAccounts extends Model
{
    public function getNewAccountsData($id){
        $start = ($id-1)*80;
        $end = 80*$id;
        $data = [
            'status' => 1,
            'id' => ['elt',$end],
        ];
        $data1 = [
            'id' => ['gt',$start]
        ];
       return model('NewAccounts')->where($data)->where($data1)->select();
    }
    public function getDatas(){
        return model('NewAccounts')->paginate(80);
    }
    //获取帐号总数
    public function getCounts(){
        return model('NewAccounts')->where(['status'=>1])->count('id');
    }
    //获取编辑帐号数据
    public function editGetData($id){
        return model('NewAccounts')->where(['id'=>$id])->find();
    }
    //获取搜索帐号数
    public function getSearchCounts($data){
        return model('NewAccounts')->where('username','like','%'.$data.'%')->where(['status'=>1])->count();
    }
    //获取帐号列表数据,通过时间排序
    public function getAccounts(){
        return model('NewAccounts')->where(['status'=>1])->order(['id'=>'asc'])->paginate();
    }
    //获取某段数据
    public function getValueArea($start,$end){
        $sql = "select new.id,new.username,acc.friends,acc.new_friends,acc.create_time from zl_accounts acc LEFT JOIN zl_new_accounts new on new.username=acc.username where
                new.id>$start and new.id <= $end and acc.friends>=0 and acc.new_friends>=0 and acc.nearby_per>=0 and acc.new_nearby>=0 and
                acc.nearby_per<=1  ORDER BY new.id  ";
        $data = Db::query($sql);
        return $data;
    }
    //根据帐号名获取当个帐号数据
    public function getOneAccounts($username){
        return model('NewAccounts')->where(['username'=>$username])->find();
    }
}
