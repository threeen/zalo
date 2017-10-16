<?php
namespace app\index\controller;
use think\Controller;
use think\Model;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch('index/login');
    }
    public function checkLogin(){
        $data = input();
        $model = Model('admin');
        $admin = $model->where(['name'=>$data['name']])->find();
            if($admin && $admin->password==$data['password']){
                cookie('admin',$admin->name);
                $this->redirect('index/phone/index');
            }else{
                $this->error("登录失败");
        }
    }
    public function logout(){
        cookie('admin',null);
        return $this->fetch('index/login');
    }
    public function register(){
        return $this->fetch('index/register');
    }
    public function registerCheck(){
        $data = input('post.');
        $account = Model('admin')->where(['name'=>$data['name']])->find();
        $validate = validate('Admin');
        if(!$validate->check($data)){
            $this->error($validate->getError());
        }else{
            if($account){
                $this->error("该帐号已经存在");
            }else{
            //进行入库逻辑操作
                    if($data['password']!=$data['repassword']){
                        $this->error("两次密码不一致");
                    }
                    if(!captcha_check($data['verifycode'])){
                        $this->error('验证码不正确');
                    }
              }
            $acc = array(
            'name'=>$data['name'],
            'password'=>$data['password'],
            );
            $model = model('Admin')->save($acc);
            if($model){
                $this->success('管理员添加成功');
            }else{
                return "数据更新失败";
            }
        }
    }
}
