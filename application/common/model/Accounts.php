<?php

namespace app\common\model;

use think\Model;

class Accounts extends Model
{
    //获取帐号列表数据,通过时间排序
    public function getAccountsData(){
        return model('Accounts')->where(['status'=>1])->order(['create_time'=>'desc'])->paginate();
    }
    //获取帐号列表数据,通过时间排序
    public function getAccounts(){
        return model('Accounts')->where(['status'=>1])->order(['id'=>'asc'])->paginate();
    }
    //获取帐号总数
    public function getCounts(){
        return model('Accounts')->where(['status'=>1])->count('id');
    }
    //获取某段数据
    public function getValueArea($start,$end){
        return model('Accounts')->where(['status'=>1])->limit($start, $end)->select();
    }
    //删除帐号
    public function accDel($id){
        return model('Accounts')->save(['status'=>-1],['id'=>$id]);
    }
    //获取编辑帐号数据
    public function editGetData($id){
        return model('Accounts')->where(['id'=>$id])->find();
    }
    public function getOneAccounts($username){
        return model('Accounts')->where(['username'=>$username])->find();
    }
    //重新启用帐号
    public function regain($id){
        return model('Accounts')->save(['status'=>1],['id'=>$id]);
    }
}
