<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 18:30
 * doc: 采购单详情
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class CheckoutOrderDetail extends Model {

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
            ->field('a.*,b.name')
            ->select();
        return  empty($info) ? [] : collection($info)->toArray();
    }

    //配货
    public function distribute($detail_info)
    {
        if(empty($detail_info)) return  false;
        foreach ($detail_info as $val){
            $re = $this->where(['id'=>$val['id']])->setField('delivery_amount',$val['delivery_amount']);
            if($re === false)    return false;
        }
        return  true;
    }


    
    
}