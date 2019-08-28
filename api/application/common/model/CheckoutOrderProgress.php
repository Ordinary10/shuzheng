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

class CheckoutOrderProgress extends Model {
    public function addProgress($order_id,$uid,$status,$remark)
    {
        $data = [
            'order_id' => $order_id,
            'uid' => $uid,
            'status' => $status,
            'remark' => $remark,
            'ctime' =>date('Y-m-d'),
        ];
        return  $this->insert($data);
    }

    
    
}