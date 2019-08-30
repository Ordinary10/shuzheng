<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 17:24
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;


use app\common\model\User;
use app\common\service\Mycrypt;
use app\common\traits\Result;
use think\Controller;
use think\Request;
use think\Response;

class Base extends Controller {
    protected static $params = [];
    protected static $userInfo = [];
    protected static $token;

    use Result;

    public function _initialize()
    {
        /*
        header('Access-Control-Allow-Origin:*');
        $post = \request()->post();
        $get = \request()->get();
        self::$params = array_merge($post,$get);      //小程序用request获取不到值
        $need_auth_check=!in_array(strtolower(\request()->controller()),array('login'));
        $token = self::$params['token'];
        if(empty($token) && $need_auth_check){
            header('Content-type: application/json');
            echo json_encode(['code'=>-998,'msg'=>'token失效请重新登陆！','token'=>'']);
            exit();
        }
        //token及权限验证
        if($need_auth_check){
            self::$token=json_decode(Mycrypt::decrypt($token),true);
            self::$token['full_token']=$token;
            self::$userInfo=self::checkToken();
            if(!self::$userInfo){
                header('Content-type: application/json');
                echo json_encode(['code'=>-998,'msg'=>'token失效请重新登陆！','token'=>'']);
                exit();
            }
            //权限验证
            /*if(!Auth::check(self::$post['fun'],self::$userInfo)){
                return self::throw_error_result('您无该操作权限，请联系管理员！',null,true);
            }*/
        //}
    }



    protected function makePage($page = 0,$limit = 0){
        return empty($page) || empty($limit) ?
            self::$params['page'] . ',' . self::$params['limit'] : $page . ',' . $limit;
    }


    //验证token是否正确
    private static function checkToken(){
        if( !self::$token
            || empty(self::$token['uid'])
            || empty(self::$token['ip'])
            || empty(self::$token['token'])
        )
        {
            return false;
        }
        $userModel=new User();
        $userInfo=$userModel->getUserInfoByUid(self::$token['uid']);
        if(empty($userInfo) || $userInfo['token']!=self::$token['token'] || $userInfo['tk_expire_time']<time()){
            return false;
        }
        return $userInfo;
    }





}