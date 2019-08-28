<?php
namespace app\common\service;
/*+++++++++++++++++++++++++++++
 * Created by PhpStorm
 * User: a06220226
 * Date: 2018/7/5 16:22
 * doc: 加密方式
 *+++++++++++++++++++++++++++++
 */

class Mycrypt {
    private static $key='fja^^$%jG';
    private static $iv='4dasdk*%*4545^hl';
    private static $method='aes-128-cbc';
    private static $option=0;

    public static function encrypt($data)
    {
        return  base64_encode(openssl_encrypt($data,self::$method,self::$key,self::$option,self::$iv));
    }

    public static function decrypt($data)
    {
        return openssl_decrypt(base64_decode($data),self::$method,self::$key,self::$option,self::$iv);
    }
    
}