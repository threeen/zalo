<?php
namespace app\common\model;
use think\Model;
class Moments extends Model{
    public function add($data){
        return $this->save($data);
    }
    public function getMomentsData($username){
        return model('Moments')->where(['status'=>0,'username'=>$username])->find();
    }
}