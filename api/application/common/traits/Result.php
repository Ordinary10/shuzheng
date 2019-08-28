<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 返回消息管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\traits;
use app\common\model\UserExtra;
use think\Debug;

trait Result {

    /**
     * 输出最终数据
     * @param   $status int 状态
     * @param   $data mixed 返回的数据
     * @param   $msg string 返回的提示语
     * @param   mixed $extra 额外返回的数据
     * @param   int $count  数据条数
     * @return  string  json
     */
    public static function throw_result($status, $data, $msg,$extra=[],$count = 0)
    {
        $res = [
            'code' => intval($status),
            'token' => $status==1  ? self::deal_token() : (isset(self::$token['full_token']) ? self::$token['full_token'] : ''),
            'msg' => empty($msg) ? '' : $msg,
            'count'=>empty($count) ?  count($data) : $count,
            'time' => Debug::getUseTime(),
            'data' => $data,
            'extra'=>$extra
        ];
        ob_clean();
        return json($res);
    }

    //输出接口请求成功数据
    public static function success_result($data = [], $msg = 'success',$extra = [],$count=0)
    {
        return self::throw_result(1, $data, $msg,$extra,$count);
    }

    //输出数据请求失败数据
    public static function error_result($msg = 'error', $data = [])
    {
        return self::throw_result(0, $data, $msg);
    }

    //token处理,登陆一次更新一次，超过一小时无操作则重新登录,每次接口请求更新过期时间
    protected static function deal_token(){
        if(empty(self::$userInfo)) return  '';
        $tk_expire_time=config('tk_expire_time');
        $tk_expire_time=empty($tk_expire_time) ? 7200 : $tk_expire_time;
        $userExtra=new UserExtra();
        $userExtra->updateTokenExpireTime(self::$token['uid'],$tk_expire_time);
        $token=empty(self::$token['token']) ? '' : self::$token['token'];
        return  make_token(self::$userInfo['uid'],$token);
    }
}