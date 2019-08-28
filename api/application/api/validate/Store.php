<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/16 17:56
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\api\validate;


class Store extends Base {
    protected $rule = [
        'name' => 'require|length:2-10',
        'manager_name' => 'require|length:2-5',
        'remark' => 'max:50',
    ];

    protected $message = [
        'name' => '门店名称为2-10位',
        'manager_name' => '负责人姓名为2-5位',
        'manager_mobile' => '负责人手机号不正确',
        'remark' => '备注不超过50字',
    ];

    protected $scene = [
        'edit' => 'name,manager_name,remark'
    ];
}