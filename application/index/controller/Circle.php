<?php
namespace app\index\controller;
use think\Controller;
use think\Model;

class Circle extends Controller
{
    public function index(){
        $data = model('Circle')->getAlreadyData();
        return $this->fetch('admin/circle/index',[
            'data' => $data
        ]);
    }
}
