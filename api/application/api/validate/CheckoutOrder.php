<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 17:57
 * doc:采购单验证器
 *+++++++++++++++++++++++++++++
 */


namespace app\api\validate;


class CheckoutOrder extends Base {
    protected $rule = [
        'order_id' => 'require',
        'verify_status' => 'require|in:pass,deny',
    ];

    protected $message = [
        'order_id' => '参数错误',
        'verify_status' => '请选择正确的审核状态',
    ];

    protected $scene = [
        'verify' => 'order_id,verify_status',
    ];
}