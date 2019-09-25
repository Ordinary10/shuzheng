<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 18:15
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\service;


use app\common\model\PurchaseOrder;
use app\common\model\PurchaseOrderDetail;
use app\common\model\PurchaseOrderProgress;
use think\Db;

class PurchaseOrderService extends BaseService {
    private static $order_model;
    private static $detail_model;

    public function __construct() {
        self::$order_model = new PurchaseOrder();
        self::$detail_model = new PurchaseOrderDetail();
    }

    /**
     * 采购单编辑,有ID则更新，无则添加，采购金额超过5000则需要管理员审核，否则直接到采购员审核
     * @param $param array
     * array(2) {
        ["remark"] => string(27) "库存告急，急需采购"
        ["data"] => array(1) {
            [0] => array(3) {
                ["goods_id"] => string(1) "1"
                ["amount"] => string(2) "10"
                ["unit_price"] => string(2) "38"
            }
        }
    }
     * @param int   $uid
     * @return int|bool
     */
    public function editPurchaseOrder($param,$uid)
    {
        $order_data = [
            'status' => 'apply',
            'total_count' => 0,
            'total_amount' => 0,
            'uid'=>$uid,
        ];
        $detail_data = [];
        foreach ($param['data'] as $val){
            if(empty($val['goods_id']) || empty($val['amount']) || empty($val['unit_price'])){
                return self::setError('采购数量、单价必填');
            }
            $amount = round($val['amount'] * $val['unit_price'],2);
            $detail_data[] = [
                'goods_id'=>$val['goods_id'],
                'apply_amount'=>$val['amount'],
                'unit_price' => $val['unit_price'],
                'estimated_money'=>$amount,
            ];
            $order_data['total_count'] += 1;
            $order_data['estimated_amount'] += $amount;
        }
        if(empty($order_data['total_count']))   return self::setError('采购商品信息不正确');
        $order_data['verify_status'] = $order_data['estimated_amount'] >= config('boss_verify') ? 1 : 5;
        Db::startTrans();
        $order_id = self::$order_model->edit($order_data,$param['order_id']);
        if(!$order_id){
            Db::rollback();
            return self::setError('采购单录入失败');
        }
        $re = self::$detail_model->edit($detail_data,$order_id);
        if(!$re){
            Db::rollback();
            return self::setError('采购单详情录入失败');
        }
        $progress = empty($param['order_id']) ? 'apply' : 'edit';
        $re = $this->dealProgress($order_id,$uid,$progress,$param['remark']);
        if(!$re){
            Db::rollback();
            return self::setError('流程处理失败');
        }
        Db::commit();
        return  $order_id;
    }

    //采购单审核
    public function verify($order_id,$param,$uid)
    {
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info) || $order_info['status'] != 'apply')  return   self::setError('订单信息查询失败');
        Db::startTrans();
        $status = $param['verify_status'] === 'pass' ? 'pass' : 'deny';
        $re = $this->dealProgress($order_id,$uid,$status,$param['remark']);
        if(!$re){
            Db::rollback();
            return self::setError('流程处理失败');
        }
        //boss审核通过订单状态不变，只改变verify_status
        if($order_info['verify_status'] == 1 && $status === 'pass'){
            $re = self::$order_model->setStatus($order_id,$order_info['status'],['verify_status'=>5]);
        }else{
            $re = self::$order_model->setStatus($order_id,$status,['verify_status'=>($status =='pass' ? 10 : 15)]);
        }
        if(!$re){
            Db::rollback();
            return self::setError('状态修改失败');
        }
        Db::commit();
        return  true;
    }

    //采购完成，录入系统
    public function buy($order_id,$param,$uid)
    {
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info) || $order_info['status'] != 'pass')  return   self::setError('订单信息查询失败');
        Db::startTrans();
        $total_amount = 0;    //实际采购金额
        foreach ($param['data'] as $val){
            $total_amount += $val['buy_money'];
            $re = self::$detail_model->buy($val['detail_id'],$val['buy_amount'],$val['buy_money'],$val['supplier']);
            if(!$re){
                Db::rollback();
                return self::setError('数据录入失败，请重试');
            }
        }
        $status = 'buy';
        $re = $this->dealProgress($order_id,$uid,$status,$param['remark']);
        if(!$re){
            Db::rollback();
            return self::setError('流程处理失败');
        }
        $re = self::$order_model->setStatus($order_id,$status,['total_amount'=>$total_amount]);
        if(!$re){
            Db::rollback();
            return self::setError('状态修改失败');
        }
        Db::commit();
        return true;
    }
    
    //签收
    public function sign($param,$uid)
    {
        $order_id = $param['order_id'];
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info))  return   self::setError('订单信息查询失败');
        Db::startTrans();
        $status = 'done';
        $re = $this->dealProgress($order_id,$uid,$status,$param['remark'],$param['proof']);
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

    //入库
    public function putIn($order_id,$param,$uid)
    {
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info) || $order_info['status'] != 'buy')  return   self::setError('订单信息查询失败');
        Db::startTrans();
        foreach ($param['data'] as $val){
            $re = self::$detail_model->putIn($val['detail_id'],$val);
            if(!$re){
                Db::rollback();
                return self::setError('数据录入失败，请重试');
            }
        }
        $status = 'done';
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

    /**
     *
     * @param array $data
     * $data = [
        ['bar_code'=>111,'num'=>10],
        ['bar_code'=>222,'num'=>10],
    ];
     * @return bool
     */
    public function checkOut($data)
    {
        foreach ($data as $val){
            $re = self::$detail_model->setUsedAmount($val['bar_code'],$val['num']);
            if(!$re)    return  false;
        }
        return true;
    }

    public function getProgressById($order_id)
    {
        $progress_model = new PurchaseOrderProgress();
        $progress = $progress_model->getProgressByOrderId($order_id);
        foreach ($progress as &$val){
            $val['status_name'] = self::$order_model->progress_status[$val['status']];
        }
    }

    //流程处理
    private function dealProgress($order_id,$uid,$progress,$remark = '',$proof){
        $progress_model = new PurchaseOrderProgress();
        return  $progress_model->addProgress($order_id,$uid,$progress,$remark,$proof);
    }
    
}