<?php
namespace app\index\controller;
use think\Controller;
class Base extends Controller{
    public function __construct(){
        if(isset($_COOKIE['admin'])){

        }else{
            $this->success('请先登录',url('index/index/index'));
        }
    }
}