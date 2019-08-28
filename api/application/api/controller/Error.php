<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 17:23
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;


use think\Request;

class Error extends Base {
    public function index(Request $request)
    {
        return self::error_result('功能开发中');
    }
}