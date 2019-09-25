<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/9/25 17:29
 * doc: 商品出入库
 *+++++++++++++++++++++++++++++
 */


namespace app\common\service;


use app\common\model\Goods;
use app\common\model\GoodsInOut;
use app\common\model\InOutOrder;

class InOutService extends BaseService {
    private static $model;
    private static $goods_inout_model;
    private static $goods_model;

    public function __construct()
    {
        self::$model = new InOutOrder();
        self::$goods_inout_model = new GoodsInOut();
        self::$goods_model = new Goods();
    }
    //出库
    public function checkOut($param)
    {
        $order_param = [
            'uid' => $param['uid'],
            'img' => $param['img'],
            'remark' => $param['remark'],
            'type' => -1,
        ];
        $order_id = self::$model->editorGoodsInventory($order_param);
        if(!$order_id) return self::setError('出库单处理失败');
        $raw = self::$goods_inout_model->checkOut($order_id,$param['goods_info']);
        if(!$raw)   return self::setError('商品出库记录保存失败');
        //修改商品数量
        foreach ($param['goods_info'] as $val){
            $re = self::$goods_model->setStock($val['goods_id'],-$val['num']);
            if(!$re)    self::setError('商品库存修改失败');
        }
        return  true;
    }
}