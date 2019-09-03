<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/16 16:00
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class CheckoutOrder extends Model {
    public $status = [
        'done'=>'已完成',
        'distribute'=>'待签收',
        'deny'=>'已拒绝',
        'pass'=>'待配货',
        'apply'=>'待审核'
    ];

    /**
     * 获取列表
     * @param $where array
     * @param $page int
     * @return array
     * @throws
     */
    public function getLists($where,$page)
    {
        $data = ['lists'=>[],'count'=>0];
        $data['count'] = $this->alias('a')
            ->join('user u','a.uid=u.uid','left')
            ->where($where)
            ->count();
        if(empty($data['count']))   return  $data;
        $lists = $this->alias('a')
            ->join('user u','a.uid=u.uid','left')
            ->where($where)
            ->page($page)
            ->field('a.*,u.uname')
            ->select();
        $data['lists'] = empty($lists) ? [] : collection($lists)->toArray();
        return  $data;
    }
    
    //新增或编辑出库单
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

    public function setStatus($order_id,$status)
    {
        $save_data = [
            'status'=>$status,
            'up_time'=>date('Y-m-d H:i:s'),
        ];
        return $this->where(['id'=>$order_id])->update($save_data);
    }

}