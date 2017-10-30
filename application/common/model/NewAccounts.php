<?php

namespace app\common\model;

use think\Model;

class NewAccounts extends Model
{
    public function getNewAccountsData(){
        $data = [
            'status' => 1,
            'id' => ['lt',21],
        ];
        return model('NewAccounts')->where($data)->select();
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
}
