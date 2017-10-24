<?php
namespace app\index\controller;
use think\Controller;
class Phone extends Controller{
    public function index(){
        if(isset($_COOKIE['admin'])){
            return $this->fetch('admin/index');
        }else{
            $this->success('请先登录',url('index/index/index'));
        }
    }
    public function list_phone(){
        if(isset($_COOKIE['admin'])) {
            $data = model('Accounts')->getAccountsData();
            $count = model('Accounts')->getCounts();
            return $this->fetch('admin/list',[
                'data'=>$data,
                'count'=>$count,
            ]);
        }
        else{
            $this->success('请先登录',url('index/index/index'));
        }
    }
    public function edit(){
        if(isset($_COOKIE['admin'])){
            $edit = input('edit');
            if(isset($edit)){
                //执行修改逻辑
                $id = input('id');
                $data = input('post.');
                $account = array(
                    'username'=>$data['username'],
                    'friends'=>$data['friends'],
                    'new_friends'=>$data['new_friends'],
                    'create_time'=>date('Y-m-d H:i:s',time())
                );
                $model = model('Accounts')->save($account,['id'=>$id]);
                if($model){
                    $this->success('数据更新成功');
                }else{
                    return "数据更新失败";
                }
            }else {
                $id = input('id');
                $data = model('Accounts')->editGetData($id);
                return $this->fetch('admin/edit', [
                    'data' => $data,
                ]);
            }
        }else{
            $this->success('请先登录',url('index/index/index'));
        }
    }
    public function getPostData(){
        $data = input('post.userName');
        if(empty($data)){
            return "抛送数据为空";
        }else{
            $arr = explode('#',$data);
            $acc = model('Accounts')->getOneAccounts($arr[0]);
            if($acc){
                if($arr[2]==$acc['data2']){
                    return "无好友增加";
                }else {
                    $account = array(
                        'username' => $arr[0],
                        'friends' => $arr[2],
                        'new_friends' => $arr[2] - $acc['data2'],
                        'new_nearby' => $arr[1] - $acc['data2'],
                        'nearby_per' => ($arr[1] - $acc['data2']) / 45,
                        'create_time' => date($arr[3]),
                        'login_status' => $arr[4],
                        'data1' => $arr[1],
                        'data2' => $arr[2]
                    );
                }
            }else{
                $account = array(
                    'username'=>$arr[0],
                    'friends'=>$arr[2],
                    'new_friends'=>$arr[2]-$arr[1],
                    'new_nearby'=>0,
                    'nearby_per'=>0,
                    'create_time'=>date($arr[3]),
                    'login_status' => $arr[4],
                    'data1'=>$arr[1],
                    'data2'=>$arr[2]
                );
            }

            $validate = validate('Accounts');
            if(!$validate->check($account)){
                $this->error($validate->getError());
            }
            $acc = model('Accounts')->where(['username'=>$arr[0]])->find();
            if($acc && $acc->username){//判断帐号是否存在，如果存在根据帐号执行更新操作，否则执行insert操作
                $model = model('Accounts')->save($account,['username'=>$arr[0]]);
                if($model){
                    return "数据更新成功";
                }else{
                    return "数据更新失败";
                }
            }else{
                $model = model('Accounts')->save($account);
                if($model){
                    return "数据更新成功";
                }else{
                    return "数据更新失败";
                }
            }
        }
    }
    //删除帐号
    public function accDel(){
        $id = input('id');
        $res = model('Accounts')->accDel($id);
        if($res){
            $this->success('删除成功', 'phone/list_phone');
        }else{
            echo "删除失败";
        }
    }
    //已经删除的帐号
    public function del(){
        $data = model('Accounts')->where(['status'=>-1])->paginate();
        return $this->fetch('admin/del',[
            'data'=>$data,
        ]);
    }
    //重新启用
    public function regain(){
        $id = input('id');
        $res = model('Accounts')->regain($id);
        if($res){
            $this->success('重新启用成功', 'phone/list_phone');
        }else{
            echo "重新启用失败";
        }
    }

    //搜索功能函数
    public function search(){
            if(isset($_COOKIE['admin'])) {
                $isset = isset($_COOKIE['searchData']) ? $_COOKIE['searchData'] : '';
                $data = input('post.account',$isset,'');
                cookie('searchData',$data);
                $data = trim($data);
                $searchData = model('Accounts')->where('username','like','%'.$data.'%')->where(['status'=>1])->paginate();
                $page = $searchData->render();
                $count = model('Accounts')->getSearchCounts($data);
                return $this->fetch('admin/search',[
                    'searchData'=>$searchData,
                    'page' => $page,
                    'count' => $count,
                ]);
            }
            else{
                $this->success('请先登录',url('index/index/index'));
            }
    }
    public function test(){
        $data = file_get_contents('public/zalo.txt');
        $arr = explode(' ',trim($data));
        $arr = explode(' ',$arr);
        print_r($arr);exit;
        foreach($arr as $key=>$value){
            $new = explode('|',$value);
        }
        foreach($new as $value){
            echo $value."<br>";
        }

        //model('NewAccounts')->save();
    }
}