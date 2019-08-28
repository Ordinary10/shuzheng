<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 额外用户信息
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;
use think\Model;

class UserExtra extends Model {

    public function add($uid)
    {
        return  $this->insert(['uid'=>$uid]);
    }
    
    /**
     * 更新token，在有效期则不更新
     * @param  $uid int
     * @param  $expire_time int 过期时间，秒
     * @param $is_login bool
     * @return string|bool
     * @throws
     */
    public function updateToken($uid,$expire_time=1800,$is_login = false)
    {
        $is_exist = $this->where(['uid'=>$uid])->field('uid,tk_expire_time,token')->find();
        if($is_exist['tk_expire_time'] >= time()){
            return $is_exist['token'];
        }
        $data = [
            'uid'=>$uid,
            'token'=>mt_rand(100000,999999),
            'tk_uptime'=>time(),
        ];
        $data['tk_expire_time'] = $data['tk_uptime']+$expire_time;
        if($is_login){
            $data['last_login_time'] = date('Y-m-d H:i:s');
            $data['last_login_ip'] = request()->ip();
        }
        if($is_exist){
            $re = $this->where(['uid'=>$uid])->update($data);
        }else{
            $re = $this->insert($data);
        }
        return  $re ? $data['token'] : false;
    }

    /**
     * 更新token过期时间
     * @param  $uid int
     * @param  $expire_time int 过期时间，秒
     * @return int
     */
    public function updateTokenExpireTime($uid,$expire_time=3600)
    {
        $data=[
            'tk_expire_time'=>time() + $expire_time
        ];
        return  $this->where(['uid'=>$uid])->update($data);
    }

}