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
        return model('NewAccounts')->paginate();
    }
    //获取帐号总数
    public function getCounts(){
        return model('NewAccounts')->where(['status'=>1])->count('id');
    }
}
