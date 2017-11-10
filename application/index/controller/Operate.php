<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Operate extends Controller{
    public function index(){
        $data = model('Accounts')->getLiveAccounts();
//        if(!empty($data)){
//
//            foreach($data as $value){
//                if(time()>(strtotime($value['create_time'])+60)){
//                    model('Accounts')->save(['login_status'=>0,'create_time'=>date('Y-m-d H:i:s',time())],['id'=>$value['id']]);
//                }
//            }
//        }
        return $this->fetch('admin/operate/index',[
            'data' => $data
        ]);
    }
    public function moments(){
    $username = input('username');
    return $this->fetch('admin/operate/moments',[
        'username' => $username
    ]);
}
    public function in_moments(){
        $data = array();
        $content = input('post.content');
        $username = input('username');
        if(empty($content)){
            return "朋友圈内容为空";
        }else{
            $data = [
                'username' => $username,
                'content' => $content,
            ];
            $result = model('Moments')->add($data);
            if($result){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
        }

    }
    public function group(){
        $data = model('NewAccounts')->getAccounts();
        $count = model('NewAccounts')->getCounts();
        $simulator = ceil($count/80)+1;
        $acc = model('NewAccounts')->getValueArea(0,80);
        return $this->fetch('admin/group/index',[
            'data' => $data,
            'count' => $count,
            'simulator' => $simulator,
            'acc' => $acc
        ]);
    }
    //模拟器数据分组
    public function groupAccounts(){
        $value = input('post.data',1,'intval');
        $start = ($value-1)*80;
        $end = 80;
        $sql = "select new.id,new.username from zl_new_accounts new,zl_accounts acc where
               new.id>=1 and new.id<80";
        //$sql = "select * from zl_accounts acc LEFT JOIN zl_new_accounts new on new.username=acc.username where new.id>=1 and new.id <= 80";
        $data = Db::query($sql);
        print_r($data);exit();
//        $data = model('NewAccounts')->getValueArea($start,$end);
//        $username = $accounts = $new = array();
//        foreach($data as $value){
//            $username [] = $value['username'];
//        }
//        foreach($username as $value){
//            $accounts=model('Accounts')->where(['username'=>$value])->select();
//        }
//        print_r($accounts);
//        echo "<br>";echo "<br>";echo "<br>";echo "<br>";
//        print_r($data);
//        exit();
        //$new = array_unique(array_merge($accounts,$data));
        echo json_encode($data);
    }
    //数据回传到模拟器
    public function returnData(){
        $id = input('id',null,'intval');
        if(empty($id)){
            return "没有传模拟器号";
        }
        $data = model('NewAccounts')->getNewAccountsData($id);
        if(!empty($data)){
            $str = '';
            for($i=0;$i<count($data);$i++){
                $str .= $data[$i]['username'] . "#" . $data[$i]['password'] . "#" . $data[$i]['latitude'] . "#" . $data[$i]['longitude']. "#" . $data[$i]['device_num'] . "<br>";
            }
            echo $str;
        }else{
            return ;
        }
    }
    //朋友圈数据回传给模拟器
    public function returnFriends(){
        //$data = input('username');
        $moments = model('Friends')->getFriendsData('17073175454');
        echo $moments['content']."<br>".$moments['image'];

    }
    public function friends(){
        $username = input('username');
        return $this->fetch('admin/operate/friends',[
            'username' => $username
        ]);
    }
    public function upload(){
        $text = input('post.');
        $username = input('username');
         // 获取表单上传文件
        $files = request()->file('image');
        if(empty($text['text']) && empty($files)){
            return "不得提交空内容";
        }
        $path = '';
        if(empty($files)){
            $data =[
                'username' => $username,
                'content' => $text['text'],
                'image' => '',
            ];
            $result = model('Friends')->add($data);
            if($result){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
        }else{
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>1024*1024,'ext'=>'jpg,jpeg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
                if($info){
                    $path .= "http://zalo.dayugame.cn/public/uploads/".$info->getSaveName()."#";
                }else{
                    // 上传失败获取错误信息
                    return $file->getError();
                }

            }
            $data =[
                'username' => $username,
                'content' => $text['text'],
                'image' => $path,
            ];
            $result = model('Friends')->add($data);
            if($result){
                $this->success('提交成功');
            }else{
                $this->error('提交失败');
            }
        }
    }
    //新账号上传
    public function sendNewAccounts(){
        $isset = input('send');
        if($isset){
             //执行新账号上传逻辑
            $files = request()->file('image');
            foreach($files as $file){
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->validate(['size'=>1024*1024,'ext'=>'txt'])->move(ROOT_PATH . 'public' . DS . 'zalo帐号');
                if($info){
                    echo "上传成功";
                }else{
                    // 上传失败获取错误信息
                    return $file->getError();
                }

            }
        }else{
            return $this->fetch('admin/operate/send_new_accounts');
        }
    }

}