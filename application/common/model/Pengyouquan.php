<?php

namespace app\common\model;

use think\Model;

class Pengyouquan extends Model
{
    //根据帐号名获取当个帐号数据
    public function getOneAccounts($username){
        return model('Pengyouquan')->where(['username'=>$username])->find();
    }
}
