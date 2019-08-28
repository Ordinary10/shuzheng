<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 18:32
 * doc: 出库单
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;




use app\common\service\CheckoutOrderService;

class Checkout extends Base {
    private static $service;

    public function __construct() {
        parent::__construct();
        self::$service = new CheckoutOrderService();
    }

    //出库申请
    public function apply()
    {
        if(empty(self::$params['data']))  return  self::error_result('请录入需要采购的商品');
        $order_id = self::$service->editCheckoutOrder(self::$params,self::$userInfo['uid']);
        if(!$order_id)    return self::error_result(self::$service->getError());
        return  self::success_result($order_id,'操作成功');
    }

    //出库审核
    public function verify()
    {
        $validate = $this->validate(self::$params,'CheckoutOrder.verify');
        if($validate !== true)  return self::error_result($validate);
        $re = self::$service->verify(self::$params['order_id'],self::$params,self::$userInfo['uid']);
        if(!$re)    return self::error_result(self::$service->getError());
        return  self::success_result('','操作成功');
    }

    //配货
    public function distribute()
    {
        if(empty(self::$params['order_id']) || empty(self::$params['data']))    return self::error_result('数据错误');

    }
    
}