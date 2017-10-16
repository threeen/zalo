<?php
namespace app\index\controller;
use think\Controller;
//定义生成接口数据类
class Api extends Controller
{
    /*
     * 生成json数据格式
     * @param integer $code 状态码
     * @param string $message 提示信息
     * $param array $data 数据
     * return string
     */
    public static function json($code, $message = '', $data = array())
    {
        //如果状态码不是数字就返回空
        if(!is_numeric($code)) {
            return '';
        }
        //构造返回数据
        $result = array(
                'code' => $code,
             'message' => $message,
             'data' => $data
         );
         echo json_encode($result);
     }
    public function test(){
        //下面就来测试一下吧
        $data = array(
            'id' => 1,
            'name' => 'zjp',
            'content' => array(
                'sex' => '男',
                'age' => '24',
                'num' => array(1,3,5,7,9)
            )
        );
        $json = Api::json(200, "返回数据成功", $data);
    }

    public function getPostData(){
        $data = input('post.');
        if(empty($data)){
            retrun ;
        }else{
            //进行数据库入库处理逻辑操作
            //url:   http://192.168.37.1/thinkphp5/public/index.php/index/api/getPostData
            return "数据跟新成功";
        }

    }
}
?>