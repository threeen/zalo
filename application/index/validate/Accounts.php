<?php
namespace app\index\validate;

use think\Validate;

class Accounts extends Validate
{
    protected $rule = [
        'username'  =>  'require|max:25',
        'friends' =>  'number',
        'new_friends' => 'number',
        'create_time' => 'require'
    ];
}