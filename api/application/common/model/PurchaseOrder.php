<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 18:17
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class PurchaseOrder extends Model {
    public $status = [
        'apply' => '待审核',
        'pass' => '待采购',
        'buy' => '待入库',
        'done' => '已完成',
        'deny' => '已拒绝',
    ];

    public $progress_status = [
        'apply' => '发起申请',
        'pass' => '通过审核',
        'buy' => '采购完成',
        'done' => '入库完成',
        'deny' => '拒绝审核',
    ];

    public function getLists($where,$page)
    {
        $data = ['count'=>0,'lists'=>[]];
        $count = $this->where($where)->count();
        if(empty($count))   return $data;
        $lists = $this->where($where)->page($page)->order('id desc')->select();
        return  ['count'=>$count,'lists'=>collection($lists)->toArray()];
    }

    public function edit($order_info,$id = 0)
    {
        $order_info['up_time'] = date('Y-m-d H:i:s');
        if(empty($id)){
            $order_info['ctime'] = $order_info['up_time'];
            return $this->insertGetId($order_info);
        }else{
            $re =  $this->where(['id'=>$id])->update($order_info);
            return  $re === false ? false : $id;
        }
    }

    public function getInfoById($id)
    {
        $info = $this->where(['id'=>$id])->find();
        return empty($info) ? [] : $info->toArray();
    }

    public function setStatus($order_id,$status,$extra = [])
    {
        $save_data = [
            'status'=>$status,
            'up_time'=>date('Y-m-d H:i:s'),
        ];
        if(!empty($extra)) $save_data = array_merge($save_data,$extra);
        return $this->where(['id'=>$order_id])->update($save_data);
    }


}