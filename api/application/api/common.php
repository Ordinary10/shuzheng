<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 公共方法
 *+++++++++++++++++++++++++++++
 */
//设置错误报告级别
error_reporting(E_ERROR);

if(!function_exists('make_token')){
    //token组装
    function make_token($uid,$token,$is_customer=0){
        $data=[
            'uid'=>$uid,
            'ip'=>request()->ip(),
            'token'=>$token,
            'is_customer'=>$is_customer
        ];
        return \app\common\service\Mycrypt::encrypt(json_encode($data));
    }
}

if(!function_exists('make_pwd')){
    //密码组装
    function make_pwd($pwd,$salt){
        return  md5(md5(md5($pwd) . $salt));
    }
}

if(!function_exists('get_rule')){
    function get_rule($rules,$ids=array()){
        $is_over=0;
        foreach($rules as $key=>$rule){
            if(in_array($rule['pid'],$ids)){
                $ids[]=$rule['id'];
                unset($rules[$key]);
                continue;
            }else{
                $is_over=1;
            }
        }
        if($is_over==0){
            get_rule($rules,$ids);
        }else{
            return $ids;
        }
    }
}


if(!function_exists('get_file_ext')){
    function get_file_ext($filename){
        return  substr($filename,strrpos($filename,'.'));
    }
}
