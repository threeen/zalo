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
            'id' => ['between',$start,$end],
        ];
       model('NewAccounts')->where($data)->select();
        echo model('NewAccounts')->getLastSql();exit;
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
        return model('NewAccounts')->where(['status'=>1])->limit($start, $end)->select();
    }
    //根据帐号名获取当个帐号数据
    public function getOneAccounts($username){
        return model('NewAccounts')->where(['username'=>$username])->find();
    }
}
