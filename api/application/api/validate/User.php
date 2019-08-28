<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 15:56
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\api\validate;


class User extends Base {

    protected $rule = [
        'uname'=>'require|length:2,5',
        'account'=>'require|length:5,10',
        'status'=>'require',
        'role'=>'require'
    ];

    protected $message = [
        'uname' => '姓名为2-5位',
        'account'=>'账号为5-10位',
        'status'=>'请选择用户状态',
        'role'=>'请选择用户角色'
    ];

    protected $scene = [
        'edit'=>'uname,account,status,role',
    ];
}