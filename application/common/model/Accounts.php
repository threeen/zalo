<?php

namespace app\common\model;

use think\Model;

class Accounts extends Model
{
    //获取帐号列表数据
    public function getAccountsData(){
        return model('Accounts')->where(['status'=>1])->order(['create_time'=>'desc'])->paginate();
    }
    //获取帐号总数
    public function getCounts(){
        return model('Accounts')->where(['status'=>1])->count('id');
    }
    //删除帐号
    public function accDel($id){
        return model('Accounts')->save(['status'=>-1],['id'=>$id]);
    }
    //获取编辑帐号数据
    public function editGetData($id){
        return model('Accounts')->where(['id'=>$id])->find();
    }
    //重新启用帐号
    public function regain($id){
        return model('Accounts')->save(['status'=>1],['id'=>$id]);
    }
}
