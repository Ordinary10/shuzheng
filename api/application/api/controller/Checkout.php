<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 18:32
 * doc: 出库单
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;

use app\common\model\CheckoutOrder;
use app\common\service\CheckoutOrderService;
use app\common\service\CommonService;

class Checkout extends Base {
    private static $service;
    private static $order_model;

    public function __construct() {
        parent::__construct();
        self::$service = new CheckoutOrderService();
        self::$order_model = new CheckoutOrder();
    }

    public function getLists()
    {
        $where = $this->listFilter();
        if($where === false)    return self::success_result([]);
        try{
            $lists = self::$order_model->getLists($where,self::makePage());
        }catch (\mysqli_sql_exception $e){
            return self::error_result('数据查询失败');
        }
        $common_service = new CommonService();
        $all_uid = array_column($lists['lists'],'uid');
        foreach ($lists['lists'] as &$val){
            $val['status_name'] = self::$order_model->status[$val['status']];
            $val['store_name'] = $common_service->getStoreNameByUid($val['uid'],$all_uid);
        }
        return  self::success_result($lists['lists'],'查询成功',[],$lists['count']);
    }

    private function listFilter()
    {
        $where = [];
        if(!empty(self::$params['dp_id']))  $where['u.dp_id'] = self::$params['dp_id'];
        if(!empty(self::$params['status']))  $where['a.status'] = self::$params['status'];
        return  $where;
    }

    public function getDetailInfo()
    {
        if(empty(self::$params['order_id']))    return  self::error_result('信息错误');
        $order_id = self::$params['order_id'];
        $info = self::$order_model->getInfoById($order_id);
        $common_service = new CommonService();
        $order_info['uname'] = $common_service->getUserNameByUid($info['uid'],[$info['uid']]);
        $info['status_name'] = self::$order_model->status[$info['status']];
        $info['store_name'] = $common_service->getStoreNameByUid($info['uid'],[$info['uid']]);
        $info['detail_info'] = self::$order_model->getDetail($order_id);
        $info['progress'] = self::$service->getProgress($order_id);
        return self::success_result($info);
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
        $re = self::$service->distribute(self::$params,self::$userInfo['uid']);
        if(!$re)    return self::error_result(self::$service->getError());
        return  self::success_result('','操作成功');
    }
    
    //确认收货
    public function confirmReceipt()
    {
        if(empty(self::$params['order_id']))    return self::error_result('数据错误');
        $re = self::$service->done(self::$params,self::$userInfo['uid']);
        if(!$re)    return self::error_result(self::$service->getError());
        return  self::success_result('','操作成功');
    }
    
}