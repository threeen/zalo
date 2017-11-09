<?php
namespace app\index\controller;
use think\Controller;
class Simulator extends Controller{
    public function simulator(){
        return $this->fetch('admin/group/simulator');
    }
}