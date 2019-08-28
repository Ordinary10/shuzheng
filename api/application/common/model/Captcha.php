<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 验证码管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;
use think\Model;

class Captcha extends Model {
    public function setCaptcha($key,$code,$expire=300)
    {
        $data=[
            'captcha_code'=>strtolower($code),
            'expire_time'=>time()+$expire,
        ];
        $is_has=$this->where(['captcha_key'=>$key])->column('captcha_key');
        if(!empty($is_has)){
            return  $this->where(['captcha_key'=>$key])->update($data);
        }else{
            $data['captcha_key']=$key;
            return  $this->insert($data);
        }
    }

    public function getCaptcha($key)
    {
        $info = $this->where(['captcha_key'=>$key])->find();
        return  $info ? $info->toArray() : [];
    }

    public function delCaptcha($key)
    {
        return $this->where(['captcha_key'=>$key])->delete();
    }
}