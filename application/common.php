<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status){
    if($status == 1){
        $str="<span class='label label-success radius'>正常</span>";
    }
    elseif($status == -1){
        $str="<span class='label label-danger radius'>已删除</span>";
    }
    return $str;
}

/**
 * 分页样式
 */
function pageStyle($obj){
    if(!$obj){
        return "";
    }
    $params = request()->param();
    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';
}