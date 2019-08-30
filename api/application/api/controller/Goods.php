<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 17:58
 * doc: 商品管理
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;


use app\common\model\GoodsType;
use think\Db;

class Goods extends Base {
    private static $model;
    private static $goods_type_model;

    public function __construct()
    {
        parent::__construct();
        self::$model = new \app\common\model\Goods();
        self::$goods_type_model = new GoodsType();
    }

    //获取商品列表
    public function getGoodsLists()
    {
        $lists = self::$model->getLists($this->_listFilter(),self::makePage());
        return $this->success_result($lists['lists'],'查询成功',null,$lists['count']);
    }

    private function _listFilter(){
        $where = [];
        $where['status'] = 1;
        !empty(self::$params['status']) && $where['status'] = self::$params['status'];
        !empty(self::$params['name']) && $where['name'] = ['like','%'.self::$params['status'].'%'];
        !empty(self::$params['type_id']) && $where['type_id'] = self::$params['type_id'];
        !empty(self::$params['over_safe_stock']) && $where['stock'] = Db::Raw('< safe_stock');
        return $where;
    }

    /**
     * 编辑或新增商品
     * array(5) {
        ["id"] => string(6) "0"
        ["name"] => string(6) "大米"
        ["unit"] => string(3) "袋"
        ["type_id"] => string(1) "1"
        ["safe_stock"] => string(2) "10"
        ["status"] => string(1) "1"
     }
     * @return string
     */
    public function editGoods()
    {
        $validate = $this->validate(self::$params,'goods.edit_goods');
        if($validate !== true)  return self::error_result($validate);
        $re = self::$model->edit(self::$params,intval(self::$params['id']));
        if($re === false)   return  self::error_result('操作失败');
        return self::success_result();
    }

    //获取商品类型列表
    public function getGoodsType()
    {
        $data = self::$goods_type_model->getLists();
        foreach ($data as &$val){
            $val['id'] = $val['type_id'];
        }
        $re = array2tree($data);
        return self::success_result($re);
    }

    //编辑或新增商品类型
    public function editGoodsType()
    {
        $validate = $this->validate(self::$params,'goods.edit_goods_type');
        if($validate !== true)  return self::error_result($validate);
        $re = self::$goods_type_model->edit(self::$params,intval(self::$params['id']));
        if($re === false)   return  self::error_result('操作失败');
        return self::success_result();
    }

    public function delGoodsType()
    {
        $ids = explode(',',trim(self::$params['type_id'],','));
        if(empty($ids)) return self::error_result('请选择要删除的种类');
        $re = self::$goods_type_model->del($ids);
        if(!$re)    return self::error_result('删除失败');
        return  self::success_result('删除成功');
    }


    
}