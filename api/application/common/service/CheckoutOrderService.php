<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 18:15
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\service;


use app\common\model\CheckoutOrder;
use app\common\model\CheckoutOrderDetail;
use app\common\model\CheckoutOrderProgress;
use app\common\model\PurchaseOrderDetail;
use think\Db;

class CheckoutOrderService extends BaseService {
    private static $order_model;
    private static $detail_model;

    public function __construct() {
        self::$order_model = new CheckoutOrder();
        self::$detail_model = new CheckoutOrderDetail();
    }

    /**
     * 出库单编辑,有ID则更新，无则添加
     * @param $param array
     * array(2) {
        ["remark"] => string(27) "分店货物不够了"
        ["data"] => array(1) {
            [0] => array(3) {
                ["goods_id"] => string(1) "1"
                ["amount"] => string(2) "10"
            }
        }
    }
     * @param int $uid 申请人UID
     * @return int|bool
     */
    public function editCheckoutOrder($param,$uid)
    {
        $order_data = [
            'uid'=>$uid,
            'status' => 'apply',
            'num' => 0
        ];
        $detail_data = [];
        foreach ($param['data'] as $val){
            if(empty($val['goods_id']) || empty($val['amount'])){
                return self::setError('采购数量和单价必填');
            }
            $detail_data[] = [
                'goods_id'=>$val['goods_id'],
                'apply_amount'=>$val['amount'],
            ];
            $order_data['num'] += 1;
        }
        if(empty($order_data['num']))   return self::setError('出库单商品信息不正确');
        Db::startTrans();
        $order_id = self::$order_model->edit($order_data,$param['order_id']);
        if(!$order_id){
            Db::rollback();
            return self::setError('出库单录入失败');
        }
        $re = self::$detail_model->edit($detail_data,$order_id);
        if(!$re){
            Db::rollback();
            return self::setError('出库单详情录入失败');
        }
        $progress = empty($param['order_id']) ? 'apply' : 'edit';
        $re = $this->dealProgress($order_id,$uid,$progress,$param['remark']);
        if(!$re) return self::setError('流程处理失败');
        Db::commit();
        return  $order_id;
    }

    //出库单审核
    public function verify($order_id,$param,$uid)
    {
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info))  return   self::setError('订单信息查询失败');
        $status = $param['verify_status'] === 'pass' ? 'pass' : 'deny';
        $re = $this->dealProgress($order_id,$uid,$status,$param['remark']);
        if(!$re) return self::setError('流程处理失败');
        $re = self::$order_model->setStatus($order_id,$status);
        if(!$re)    return self::setError('状态修改失败');
        return  true;
    }

    /**
     * 出库单配货，对应货物出库
     * @param array $param
     *
     * @param int $uid
     * @return bool
     */
    public function distribute($param,$uid)
    {
        $order_id = $param['order_id'];
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info))  return   self::setError('订单信息查询失败');
        Db::startTrans();
        $purchase_detail_model = new PurchaseOrderDetail();
        $detail_info = self::$detail_model->getInfoByOrderId($order_id);
        $detail_info = array_column($detail_info,null,'goods_id');
        foreach ($param['data'] as $val){
            if(empty($val['bar_code']) || empty($val['num'])) return self::setError('数据有误');
            $stock = $purchase_detail_model->getInfoByBarCode($val['bar_code']);
            if(empty($stock) || ($stock['buy_amount'] - $stock['used_amount']) < $val['num']){
                return  self::setError('条形码为' . $val['bar_code'] . '的商品库存不足');
            }
            if(!isset($detail_info[$stock['goods_id']])){
                return  self::setError('该笔出库单没有条形码为' . $val['bar_code'] . '的商品');
            }
            $detail_info[$stock['goods_id']]['delivery_amount'] += $val['num'];
            if($detail_info[$stock['goods_id']]['delivery_amount'] > $detail_info[$stock['goods_id']]){
                return  self::setError($stock['name'] . '出库数量超过了申请数量');
            }


        }
        $re = self::$detail_model->distribute($val['detail_id'],$val['delivery_amount']);
        if(!$re){
            Db::rollback();
            return self::setError('数据录入失败，请重试');
        }
        $status = 'distribute';
        $re = $this->dealProgress($order_id,$uid,$status,$param['remark']);
        if(!$re){
            Db::rollback();
            return self::setError('流程处理失败');
        }
        $re = self::$order_model->setStatus($order_id,$status);
        if(!$re){
            Db::rollback();
            return self::setError('状态修改失败');
        }
        Db::commit();
        return true;
    }

    //流程处理
    private function dealProgress($order_id,$uid,$progress,$remark = ''){
        $progress_model = new CheckoutOrderProgress();
        return  $progress_model->addProgress($order_id,$uid,$progress,$remark);
    }
    
}