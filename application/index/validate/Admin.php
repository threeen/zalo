<?php
namespace app\index\validate;

use think\Validate;

class Admin extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:16',
        'password' =>  'require|max:32',
    ];

}