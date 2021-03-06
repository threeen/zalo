<?php

namespace app\common\model;

use think\Model;

class Circle extends Model
{
    //根据帐号名获取当个帐号数据
    public function getOneAccounts($username){
        return model('Circle')->where(['username'=>$username])->find();
    }
    //获取已经发过朋友圈的帐号
    public function getAlreadyData(){
        return model('Circle')
            ->alias('cir')
            ->field("cir.id,cir.username,cir.create_time,acc.friends,cir.times")
            ->join('zl_accounts acc','cir.username = acc.username')
            ->order(['create_time'=>'desc'])
            ->paginate();
    }
    //获取已经发过朋友圈的个数
    public function getAlreadyCounts(){
        return model('Circle')->count('id');
    }
}
