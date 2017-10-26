<?php
namespace app\common\model;
use think\Model;
class Moments extends Model{
    public function add($data){
        return $this->save($data);
    }
}