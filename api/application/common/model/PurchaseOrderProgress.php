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

class PurchaseOrderProgress extends Model {

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

    /**
     * 根据订单ID获取流程
     * @param int $id
     * @throws
     * @return array
     */
    public function getProgressByOrderId($id)
    {
        $info = $this->alias('a')
            ->join('user u','a.uid=u.uid','left')
            ->where(['a.order_id'=>$id])
            ->order('id asc')
            ->field('a.*,u.uname')
            ->select();
        return empty($info) ? [] : collection($info)->toArray();
    }

    
    
}