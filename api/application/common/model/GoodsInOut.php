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
        '-1'=>'出库'
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


    public function getGoodsNumByBarcode($bar_code)
    {
        $info = $this->where(['batch_number'=>$bar_code])->field('sum(num * flag) num,goods_id')->find();
        return  empty($info) ? [] : $info->toArray();
    }

    //出库出库为二级单位
    public function checkOut($order_id,$data)
    {
        $save_data = [];
        foreach ($data as $val){
            $save_data[] = [
                'order_id' => $order_id,
                'goods_id' => $val['goods_id'],
                'batch_number' => $val['batch_number'],
                'locator' => empty($val['locator']) ? '' : $val['locator'],
                'unit_type'=>2,
                'unit_num'=>$val['num'],
                'flag' => -1,
                'specs'=> 1,
                'num' => $val['num'],
                'ctime' => date('Y-m-d H:i:s'),
            ];
        }
        return $this->insertAll($save_data);
    }



}