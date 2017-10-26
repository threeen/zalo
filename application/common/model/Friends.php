<?php
namespace app\common\model;
use think\Model;
class Friends extends Model{
    public function add($data){
        return $this->save($data);
    }

}