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
}
