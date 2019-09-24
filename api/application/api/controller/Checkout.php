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
use app\common\model\GoodsInOut;
use app\common\model\InOutOrder;

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
        $info['uname'] = $common_service->getUserNameByUid($info['uid'],[$info['uid']]);
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

    // 商品入库列表
    public function goodsInventoryList()
    {
        $in_out_order = new InOutOrder();
        $where = $this->condition();
        $data = $in_out_order->getGoodsInventoryList($where,$this->makePage());
        if(empty($data['lists'])) {
            return self::success_result([],'查询成功',[],0);
        }
        $this->disposeData($data['lists']);
        return self::success_result($data['lists'],'查询成功',[],$data['count']);
    }

    // 处理数据
    public function disposeData(&$data)
    {
        $order_id = array_column($data,'id');
        $all_uid = array_column($data,'uid');
        $goods_in_out = new GoodsInOut();
        $common_service = new CommonService();
        $detail = $goods_in_out->where(['order_id'=>['in',$order_id]])->select();
        foreach ($data as $key => &$value) {
            $value['uname'] = $common_service->getUserNameByUid($value['uid'],$all_uid);
            $value['type_name'] = $goods_in_out->goodsType[$value['type']];
            foreach ($detail as $k => $val) {
                $val['flag_name'] = $goods_in_out->goodsType[$val['flag']];
                if($value['id'] == $val['order_id']) {
                    $value['detail'] = $val;
                }
            }
        }

    }

    // 商品入库列表搜索条件
    private function condition() {
        $where = [];
        if(!empty(self::$params['type'])) {
            $where['type'] = self::$params['type'];
        }
        return $where;
    }
    
    // 商品入库
    public function commodityWarehousing()
    {
        if(empty(self::$params)) {
            return self::error_result('请输入要录入的信息');
        }
        if(empty(self::$params['detail'])) {
            return self::error_result('没有录入商品信息');
        }
        $batch_number = $order_id = array_column(self::$params['detail'],'batch_number');
        $goods_in_out = new GoodsInOut();
        $verification = $goods_in_out->where(['batch_number'=>['in',$batch_number]])->select();
        if(!empty($verification)) {
            return self::error_result('录入的批次号重复');
        }
        $in_out_order = new InOutOrder();
        self::$params['uid'] = self::$userInfo['uid'];
        self::$params['uid'] = 69;
        $in_out_order->startTrans();
        $order_id = $in_out_order->editorGoodsInventory(self::$params);
        if(!$order_id) {
            $in_out_order->rollback();
            return self::error_result('入库商品基础信息失败');
        }
        $raw = $goods_in_out->editorCommodityWarehousing($order_id,self::$params['detail']);
        if(!$raw) {
            $in_out_order->rollback();
            return self::error_result('入库商品基础信息失败');
        }
        $in_out_order->commit();
        return  self::success_result('','商品入库成功');
    }

    // 商品出库
    public function goodsDecrease()
    {
        if(empty(self::$params)) {
            return self::error_result('请输入要录入的信息');
        }
        $in_out_order = new InOutOrder();
        self::$params['uid'] = self::$userInfo['uid'];
        $in_out_order->startTrans();
        $order_id = $in_out_order->editorGoodsInventory(self::$params);
        if(!$order_id) {
            $in_out_order->rollback();
            return self::error_result('出库商品失败');
        }
        $goods_in_out = new GoodsInOut();
        $raw = $goods_in_out->editorCommodityWarehousing($order_id,self::$params['detail']);
        if(!$raw) {
            $in_out_order->rollback();
            return self::error_result('出库商品失败');
        }
        $in_out_order->commit();
        return  self::success_result('','商品出库成功');
    }
}