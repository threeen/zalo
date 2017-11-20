<?php
namespace app\index\controller;
use think\Controller;
use think\Model;

class Circle extends Controller
{
    public function index(){
        $data = model('Circle')->getAlreadyData();
        $counts = model('Circle')->getAlreadyCounts();
        return $this->fetch('admin/circle/index',[
            'data' => $data,
            'counts'=> $counts
        ]);
    }
    public function friends(){
        $data = model('NewAccounts')->getAllFriendsAccounts();
        return $this->fetch('admin/circle/friends',[
            'data' =>  $data
        ]);
    }
}
