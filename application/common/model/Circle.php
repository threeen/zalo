<?php

namespace app\common\model;

use think\Model;

class Circle extends Model
{
    //根据帐号名获取当个帐号数据
    public function getOneAccounts($username){
        return model('Circle')->where(['username'=>$username])->find();
    }
}