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
                        //'login_status' => $arr[4],
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
                    //'login_status' => $arr[4],
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

    public function test()
    {
        $file = fopen("public/zalo.txt", "r");
        $str = $acc = $data = array();
        $username = $password = $latitude = $longitude = $device_num = array();
        $i=0;
        //输出文本中所有的行，直到文件结束为止。
        while(! feof($file))
        {
            $str[$i]= fgets($file);//fgets()函数从文件指针中读取一行
            $i++;
        }
        fclose($file);
        $str=array_filter($str);
        foreach($str as $value){
            $acc[][]= explode('|',$value);
        }
        foreach($acc as $key=>$value){
            foreach($value as $key => $val){
                foreach($val as $key=> $v){
                    if($key == 0)
                    $username[] = $val[$key];
                    elseif($key == 1)
                    $password[] = $val[$key];
                    elseif($key == 2)
                    $latitude[] = $val[$key];
                    elseif($key == 3)
                    $longitude[] = $val[$key];
                    elseif($key == 4)
                    $device_num[] = $val[$key];
                }
            }
        }
//        echo count($username);
//        print_r($username);
//        print_r($password);
//        print_r($latitude);
//        print_r($longitude);exit;
        for($i=0;$i<count($username)-1;$i++){
            $data['username']=$username[$i];
            $data['password']=$password[$i];
            $data['latitude']=$latitude[$i];
            $data['longitude']=$longitude[$i];
            $data['device_num']=$device_num[$i];
            model('NewAccounts')->allowField(true)->saveAll($data);
        }
//        foreach($acc as $key=>$value){
//            foreach($value as $key => $val) {
//                foreach($val as $key=> $v) {
//                    if ($key == 0)
//                        $data['username'] = $v[$key];
//                    if ($key == 1)
//                        $data['password'] = $v[$key];
//                    if ($key == 2)
//                        $data['latitude'] = $v[$key];
//                    if ($key == 3)
//                        $data['longitude'] = $v[$key];
//                    if ($key == 4) {
//                        $data['device_num'] = $v[$key];
//                        model('NewAccounts')->save($data);
//                        echo "成功";
//                    }
//                }
//            }
//        }
    }
}