<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 16:50
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;


use app\common\model\AuthGroupAccess;
use app\common\model\User;
use app\common\model\UserExtra;
use app\common\service\Auth;
use app\common\service\Captcha;

class Login extends Base {
    public function doLogin()
    {
        if(empty(self::$params['account']) || empty(self::$params['pwd']) || empty(self::$params['code'])) {
            return  self::error_result('登录失败,请检查账号信息是否正确输入！');
        }
        $randNo = self::$params['loginRandom'];
        $captchaModel=new \app\common\model\Captcha();
        $code=$captchaModel->getCaptcha(ip2long(request()->ip()).$randNo);
        if(empty($code)) {
            return  self::error_result('验证码错误！');
        }
        //删除验证码
        $captchaModel->delCaptcha(ip2long(request()->ip()).$randNo);
        if (time() > $code['expire_time']) {
            return  self::error_result('验证码已过期！');
        }
        if(strtolower(self::$params['code'])!=$code['captcha_code']) {
            return  self::error_result('验证码错误！');
        }

        $userModel=new User();
        $info=$userModel->getInfoByAccount(self::$params['account']);
        if(!$info || make_pwd(self::$params['pwd'],$info['salt'])!=$info['pwd']) {
            return self::error_result('账号或密码错误！');
        }
        //获取权限
        $re=Auth::getAuth($info['uid']);
        if(empty($re)) {
            return self::error_result('该账号权限被禁用，请联系管理员');
        }
        //更新token
        $userExtra=new UserExtra();
        $key=$userExtra->updateToken($info['uid'],config('tk_expire_time'),true);
        $re['token']=make_token($info['uid'],$key);
        $re['userInfo']=['username'=>$info['uname'],'nickname'=>$info['nickname'],'is_first_login'=>empty($info['token']) ? 1 : 0];
        return  self::success_result($re,'登录成功');
    }

    // 获取验证码
    public function captcha()
    {
        $config=[
            'length'=>4,
            'useNoise'=>false,
            'useCurve'=>false
        ];
        $captcha=new Captcha($config);
        $code=$captcha->setCode();

        $captchaModel=new \app\common\model\Captcha();
        $re = $captchaModel->setCaptcha(ip2long(request()->ip()).self::$params['loginRandom'],$code);
        if(!$re) return self::error_result('验证码保存失败');
        return  $captcha->showImg();
    }

    /**
     * 小程序登陆 account用户账号  code 微信登录code
     * @throws
     * @return string
     */
    public function mimiProgramLogin()
    {
        if(empty(self::$params['code']) && empty(self::$params['account']) ) {
            return  self::error_result('登录失败！');
        }
        // 用户展示的界面
        // 角色 other未知  admin管理员  salesman业务员  chef厨师
        $show_pages=[
            'role'=>'',
            'pages'=>[],
        ];

        $userModel = new User();
        //微信账号登录
        if(!empty(self::$params['code'])) {
            $openid = $this->weixinLogin(self::$params['code']);
        }

        if(empty(self::$params['account'])){
            if(empty($openid)) {
                return  self::error_result('微信登录失败，请使用账号登录！');
            }
            $info = $userModel->getInfoByWxOpenid($openid);
        }else{
            $info = $userModel->getInfoByAccount(self::$params['account']);
            if(empty($info['wx_openid']) && !empty($openid)) {
                $userModel->updateWxOpenid($info['uid'],$openid);
            }
            if(!empty($info) && make_pwd(self::$params['pwd'],$info['salt'])!=$info['pwd']){
                return  self::error_result('账号或密码错误！');
            }
        }

        if(empty($info)) {
            return  self::error_result('账号或密码错误');
        }
        //获取权限
        $role_model = new AuthGroupAccess();
        $role = $role_model->where(['uid'=>$info['uid']])->value('group_id');
        $rules =[
            1 =>'boss',
            2 =>'store',
            3 =>'storage',
            4 =>'purchase',
        ];
        if(!isset($rules[$role]))   return self::error_result('账号权限不足，请联系管理员');
        $show_pages['role'] = isset($rules[$role]) ? $rules[$role] : 'other';

        //更新token
        $userExtra = new UserExtra();
        $key=$userExtra->updateToken($info['uid'],config('tk_expire_time'));
        $re['userInfo'] = ['username'=>$info['uname'],'nickname'=>$info['nickname']];

        $re['token']=make_token($info['uid'],$key,!empty($re['is_customer']) ? 1 : 0);
        $re['show_page'] = $show_pages;
        return  self::success_result($re,'登录成功');
    }

    //微信账号登录
    private function weixinLogin($code){
        $appid=config('wx_appId');
        $appSecret=config('wx_appSecret');
        $info=file_get_contents("https://api.weixin.qq.com/sns/jscode2session?appid={$appid}&secret={$appSecret}&js_code={$code}&grant_type=authorization_code");
        $info=json_decode($info,true);
        return !empty($info) && !empty($info['openid']) ? $info['openid'] : '';
    }
}