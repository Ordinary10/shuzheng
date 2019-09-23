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
    
    // 编辑商品录入库信息
    public function editorCommodityWarehousing($data)
    {
        $save_data = [
            'goods_id' => $data['goods_id'],
            'locator' => $data['locator'],
            'unit_type' => $data['unit_type'],
            'num' => $data['num'],
            'uid' => $data['uid']
        ];
        if(empty($data['id'])) {
        	$save_data['batch_number'] = $data['batch_number'];
        	$save_data['ctime'] = date('Y-m-d H:i:s');
        	$save_data['flag'] = 1;
        	return  $this->insertGetId($save_data);
        }
        $save_data['flag'] = $data['flag'];
        return $this->where(['id'=>$data['id']])->update($save_data);
    }
}