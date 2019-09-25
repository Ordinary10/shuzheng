<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: 
 * Date: 2019/9/23 14:45
 * doc: 出入库订单管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;

use think\Model;

class InOutOrder extends Model {

    // 获取出入库订单列表
    public function getGoodsInventoryList($where,$page)
    {
        $count = $this->where($where)->count('id');
        if($count == 0) {
            return  ['count'=>0,'lists'=>[]];
        }
        $lists = $this->where($where)->page($page)->select();
        return ['count'=>$count,'lists'=>collection($lists)->toArray()];
    }

    // 编辑出入库订单信息
    public function editorGoodsInventory($data)
    {
        $save_data = [
            'uid' => $data['uid'],
            'img' => empty($data['img']) ? '' : $data['img'],
            'remark' => empty($data['remark']) ? '' : $data['remark'],
            'type' => $data['type'],
        ];
        if(empty($data['order_id'])) {
            $save_data['ctime'] = date('Y-m-d H:i:s');
            return  $this->insertGetId($save_data);
        }
        $re = $this->where(['id'=>$data['order_id']])->update($save_data);
        return  $re === false ? false : $data['order_id'];
    }
}