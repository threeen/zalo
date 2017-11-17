<?php
namespace app\index\controller;
use think\Controller;
class Simulator extends Controller{
    public function index(){
        return $this->fetch('simulator/index');
    }
}