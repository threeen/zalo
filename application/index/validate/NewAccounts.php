<?php
namespace app\index\validate;

use think\Validate;

class NewAccounts extends Validate
{
    protected $rule = [
        'latitude'  =>  'require',
        'longitude' => 'require'
    ];

}