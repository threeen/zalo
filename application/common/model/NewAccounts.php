<?php

namespace app\common\model;

use think\Model;

class NewAccounts extends Model
{
    public function getNewAccountsData(){
        return model('NewAccounts')->where(['status'=>1])->select();
    }
}
