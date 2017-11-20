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
    //存储朋友圈素材
    public function circle(){
        $text = input('post.');
        if(empty($text['start']) && empty($text['end'])){
           $acc = model('NewAccounts')->getAllFriendsAccounts();

        }elseif(empty($text['start']) && !empty($text['end'])){
           $acc = model('NewAccounts')->where(['id'=>['egt',$text['start']]])->select();
        }elseif(!empty($text['start']) && empty($text['end'])){
            $acc = model('NewAccounts')->where(['id'=>['elt',$text['end']]])->select();
        }else{
            $acc = model('NewAccounts')->where(['id'=>['egt',$text['start']]])->where(['id'=>['elt',$text['end']]])->select();
        }
        foreach($acc as $value){
            $username = implode('@',$value['username']);
        }
        echo $username;exit;
        //$username = input('username');
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
}
