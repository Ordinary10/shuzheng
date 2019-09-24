<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: 
 * Date: 2019/9/23 10:27
 * doc: 商品出入库记录管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;

use think\Model;

class GoodsInOut extends Model {

    // 出入库类型
    public $goodsType = [
        '1'=>'入库',
        '-1'=>'出口'
    ];
    
    // 编辑商品录入库信息
    public function editorCommodityWarehousing($order_id,$data)
    {
        $save_data = [];
        foreach ($data as $k => $val) {
            $save_data[$k] = [
                'id'=>$val['id'],
                'order_id' => $order_id,
                'goods_id' => $val['goods_id'],
                'batch_number' => $val['batch_number'],
                'locator' => $val['locator'],
                'unit_type' => $val['unit_type'],
                'flag' => $val['flag'],
                'num' => $val['num'],
                'specs' => $val['specs'],
            ];
            if($val['unit_type'] == 1) {
                $save_data[$k]['num'] = $val['unit_num'] * $val['specs'];
            }
            if(empty($val['id'])) {
                $save_data[$k]['ctime'] = date('Y-m-d H:i:s');
            }
        }
        if(empty($save_data))   return  true;
        return $this->saveAll($save_data);
    }
}