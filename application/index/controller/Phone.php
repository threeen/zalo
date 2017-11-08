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
            $realCounts = model('Accounts')->getRealCounts();
            $realDayCounts = model('Accounts')->getDayRealCounts();
            $friendsCounts = model('Accounts')->getFriendsAllCounts();
            $dayFriends = model('Accounts')->getDayFriends();
            $page = $data->render();
            return $this->fetch('admin/list',[
                'data'=>$data,
                'count'=>$count,
                'realCount'=>$realCounts,
                'realDayCount'=>$realDayCounts,
                'friendsCounts'=>$friendsCounts,
                'dayFriends'=>$dayFriends,
                'page'=>$page,
            ]);
        }
        else{
            $this->success('请先登录',url('index/index/index'));
        }
    }
    //有误帐号列表
    public function error_list(){
        if(isset($_COOKIE['admin'])) {
            $data = model('Accounts')->getErrorAccountsData();
            $count = model('Accounts')->getCounts();

            $friendsCounts = model('Accounts')->getFriendsAllCounts();
            $dayFriends = model('Accounts')->getDayFriends();
            return $this->fetch('admin/error_list',[
                'data'=>$data,
                'count'=>$count,

                'friendsCounts'=>$friendsCounts,
                'dayFriends'=>$dayFriends,
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
                $validate = validate('Accounts');
                if(!$validate->check($account)){
                    $this->error($validate->getError());
                }
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
                    $account = array(
                        'username' => $arr[0],
                        'create_time' => date($arr[3]),
                        //'login_status' => $arr[4],
                    );
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
                $simulator_num = input('post.simulator');
                if(empty($data) && empty($simulator_num)){
                    return $this->error("搜索内容为空！");
                }
                cookie('searchData',$data);
                $data = trim($data);
                $send_search = input('send_search');
                if(isset($send_search) && $send_search==1){
                    $searchData = model('NewAccounts')->where('username','like','%'.$data.'%')->where(['status'=>1])->paginate();
                    $page = $searchData->render();
                    $count = model('NewAccounts')->getSearchCounts($data);
                    return $this->fetch('admin/send_search',[
                        'searchData'=>$searchData,
                        'page' => $page,
                        'count' => $count,
                    ]);
                }else{
                    $datas = [
                        'friends'=>['egt',0],
                        'new_friends'=>['egt',0],
                        'new_nearby'=>['egt',0],
                        'nearby_per'=>['egt',0],
                        'nearby_per'=>['elt',1],
                        'status'=>['eq',1],
                        'username'=>['like','%'.$data.'%'],

                    ];
                    if(isset($simulator_num)){
                        $datas['simulator_num']=$simulator_num;
                        $count = model('Accounts')->getSearchSimulatorCounts($data,$simulator_num);
                    }else{
                        $count = model('Accounts')->getSearchCounts($data);
                    }
                    $searchData = model('Accounts')->where($datas)->paginate();
                    $page = $searchData->render();
                    return $this->fetch('admin/search',[
                        'searchData'=>$searchData,
                        'page' => $page,
                        'count' => $count,
                    ]);
                }
            }
            else{
                $this->success('请先登录',url('index/index/index'));
            }
    }
    //传送帐号
    public function sendAccounts(){
        $data = model('NewAccounts')->getDatas();
        $count = model('NewAccounts')->getCounts();
        return $this->fetch('admin/send_list',[
            'count' => $count,
            'data' => $data,
        ]);
    }
    //传送帐号列表编辑
    public function sendEdit(){
        $edit = input('edit');
        if(isset($edit)){
            //执行修改逻辑
            $id = input('id');
            $data = input('post.');
            $account = array(
                'latitude'=>$data['latitude'],
                'longitude'=>$data['longitude'],
            );
            $validate = validate('NewAccounts');
            if(!$validate->check($account)){
                $this->error($validate->getError());
            }
            $model = model('NewAccounts')->save($account,['id'=>$id]);
            if($model){
                $this->success('数据更新成功');
            }else{
                return "数据更新失败";
            }
        }else{
            //执行入库（插入）逻辑
            $id = input('id');
            $data = model('NewAccounts')->editGetData($id);
            return $this->fetch('admin/send_edit',[
                'data' => $data
            ]);
        }

    }
    //读取新账号存入数据库
    public function test()
    {
        //$last_simulator_num = model('NewAccounts')->where(['status'=>1])->order(['id'=>'desc'])->find();
        //echo $last_simulator_num['simulator_num'];exit();
        $dir="public/zalo帐号/";
        $files=scandir($dir);
        for($i=2;$i<count($files);$i++){
            $start = file_get_contents('public/zalo帐号/'.$files[$i]);
            $txt = file_get_contents('public/index.txt');
            $end = file_put_contents('public/index.txt',$txt.$start);
        }
        $txt = file_get_contents('public/index.txt');
        echo $txt;
        exit;
        print_r($files);//exit;
        for($i=2;$i<count($files);$i++){
            $file = fopen("public/zalo帐号/$files[$i]", "r");
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
            for($i=0;$i<count($username)-1;$i++){
                $exist_user = model('NewAccounts')->getOneAccounts($username[$i]);
                if($exist_user){
                    echo "该帐号".$exist_user['username']."已经存在"."<br>";
                }else{
                    $data[$i]['username']=$username[$i];
                    $data[$i]['password']=$password[$i];
                    $data[$i]['latitude']=$latitude[$i];
                    $data[$i]['longitude']=$longitude[$i];
                    $data[$i]['device_num']=$device_num[$i];
                }
                //$data[$i]['simulator_num']=$last_simulator_num['simulator_num']+1;
            }
        }
        $result = model('NewAccounts')->allowField(true)->saveAll($data);
        if($result){
            echo "帐号入库成功";
        }
    }
}