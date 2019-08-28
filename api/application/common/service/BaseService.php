<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 18:04
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\service;


class BaseService {
    protected static $error_msg = '';

    protected static function setError($msg){
        self::$error_msg = $msg;
        return false;
    }

    public function getError(){
        return  self::$error_msg;
    }
}