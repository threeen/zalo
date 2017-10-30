<?php
namespace app\index\validate;

use think\Validate;

class Accounts extends Validate
{
    protected $rule = [
        'username'  =>  'require|max:25',
        'friends' =>  'require|number',
        'new_friends' => 'require|number',
        'create_time' => 'require'
    ];
}