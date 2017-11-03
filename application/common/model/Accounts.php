<?php

namespace app\common\model;

use think\Model;

class Accounts extends Model
{
    //获取帐号列表数据,通过时间排序
    public function getAccountsData(){
        return model('Accounts')->where(['status'=>1])->order(['create_time'=>'desc'])->paginate();
    }
    public function getErrorAccountsData(){
        $data = [
            [['friends','new_friends','nearby']=>['lt',0]],
            'nearby_per'=>['lt',0],
            'nearby_per'=>['gt',1],
            'status'=>['neq',1]
        ];
        return model('Accounts')->where($data)->order(['create_time'=>'desc'])->paginate();
    }
    //获取帐号列表数据,通过时间排序
    public function getAccounts(){
        return model('Accounts')->where(['status'=>1])->order(['id'=>'asc'])->paginate();
    }
    //获取帐号总数
    public function getCounts(){
        return model('Accounts')->where(['status'=>1])->count('id');
    }
    //获取总好友数
    public function getFriendsAllCounts(){
        return model('Accounts')->where(['status'=>1])->sum('friends');
    }
    //获取日新增的好友总数
    public function getDayFriends(){
        $data = [
            'status' => 1,
            'create_time' => ['gt',date('Y-m-d',time())]
        ];
        return model('Accounts')->where($data)->sum('new_friends');
    }
    //获取搜索帐号数
    public function getSearchCounts($data){
        return model('Accounts')->where('username','like','%'.$data.'%')->where(['status'=>1])->count();
    }
    //获取某段数据
    public function getValueArea($start,$end){
        return model('Accounts')->where(['status'=>1])->limit($start, $end)->select();
    }
    //删除帐号
    public function accDel($id){
        return model('Accounts')->save(['status'=>-1],['id'=>$id]);
    }
    //获取编辑帐号数据
    public function editGetData($id){
        return model('Accounts')->where(['id'=>$id])->find();
    }
    public function getOneAccounts($username){
        return model('Accounts')->where(['username'=>$username])->find();
    }
    //重新启用帐号
    public function regain($id){
        return model('Accounts')->save(['status'=>1],['id'=>$id]);
    }
    //获取在线帐号
    public function getLiveAccounts(){
        return model('Accounts')->where(['status'=>1,'login_status'=>1])->order(['create_time'=>'desc'])->paginate();
    }
}
