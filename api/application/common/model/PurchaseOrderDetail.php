<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 18:30
 * doc: 采购单详情
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\exception\DbException;
use think\Model;

class PurchaseOrderDetail extends Model {

    //新增或者编辑
    public function edit($detail,$order_id)
    {
        $re = $this->where(['order_id'=>$order_id])->delete();
        if($re === false) return false;
        foreach ($detail as &$val){
            $val['order_id'] = $order_id;
        }
        return $this->insertAll($detail);
    }
    

    public function getInfoByOrderId($order_id)
    {
        $info = $this->alias('a')
            ->where(['a.order_id'=>$order_id])
            ->join('goods b','a.goods_id=b.id','left')
            ->join('supplier c','a.supplier_id=c.id','left')
            ->field('a.*,b.name,b.unit,c.name supplier,b.lower_unit')
            ->select();
        $info =  empty($info) ? [] : collection($info)->toArray();
        foreach ($info as &$val){
            $val['estimated_money'] = floatval($val['estimated_money']);
        }
    }

    //购买后回填购买数量及金额
    public function buy($detail_id,$buy_amount,$buy_money,$supplier)
    {
        if(empty($detail_id) || empty($buy_amount)) return  false;
        return  $this->where(['id'=>$detail_id])
            ->update(['buy_amount'=>$buy_amount,'buy_money'=>$buy_money,'supplier_id'=>$supplier]);
    }
    
    //入库回填条形码
    public function putIn($detail_id,$param)
    {
        if(empty($detail_id) || empty($param['bar_code'])) return  false;
        $data = [
            'bar_code'=>$param['bar_code'],
            'place'=>$param['place'],
        ];
        return  $this->where(['id'=>$detail_id])->update($data);
    }

    //根据条形码获取对应库存信息
    public function getInfoByBarCode($bar_code)
    {
        try{
            $info = $this->alias('a')
                ->join('goods b','a.goods_id = b.id','left')
                ->where(['a.bar_code'=>$bar_code])
                ->field('a.*,b.name')
                ->find();
            return  empty($info) ? [] : $info->toArray();
        }catch (DbException $e){
            return  [];
        }


    }

    //根据条形码设置已使用数量
    public function setUsedAmount($bar_code,$used_amount)
    {
        return  $this->where(['bar_code'=>$bar_code])->setInc('used_amount',$used_amount);
    }


    
    
}