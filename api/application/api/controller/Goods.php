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
use app\common\service\CommonalityService;
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
        if(empty($lists['lists'])) {
            return $this->success_result([],'查询成功',null,0);
        }
        $this->disposeData($lists['lists']);
        $goods_type = self::$goods_type_model->getLists();
        return $this->success_result($lists['lists'],'查询成功',array2tree($goods_type),$lists['count']);
    }

    // 处理数据
    private function disposeData(&$data) {
        $model = new CommonalityService();
        foreach ($data as $k => &$val) {
            $val['type_name'] = $model->getGoodsNameById($val['type_id']);
        }
    }

    private function _listFilter(){
        $where = [];
        $where['status'] = 1;
        !empty(self::$params['status']) && $where['status'] = self::$params['status'];
        !empty(self::$params['name']) && $where['name'] = ['like','%'.self::$params['name'].'%'];
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
        if($validate !== true) {
            return self::error_result($validate);
        }
        $re = self::$model->edit(self::$params,intval(self::$params['id']));
        if($re === false) {
            return  self::error_result('操作失败');
        }
        return self::success_result();
    }

    //编辑或新增商品类型
    public function editGoodsType()
    {
        $validate = $this->validate(self::$params,'goods.edit_goods_type');
        if($validate !== true) {
            return self::error_result($validate);
        }
        // 判断名字是否唯一
        if(empty(self::$params['id'])) {
            $info =  self::$goods_type_model->where(['pid'=>self::$params['pid'],'type_name'=>self::$params['type_name']])->value('type_id');
            if(!empty($info)) {
                return  self::error_result('类目已添加');
            }
        }
        $re = self::$goods_type_model->edit(self::$params,intval(self::$params['id']));
        if($re === false) {
            return  self::error_result('操作失败');
        }
        return self::success_result();
    }

    public function delGoodsType()
    {
        $ids = explode(',',trim(self::$params['type_id'],','));
        if(empty($ids)) {
            return self::error_result('请选择要删除的种类');
        }
        $re = self::$goods_type_model->del($ids);
        if(!$re) {
            return self::error_result('删除失败');
        }
        return  self::success_result('删除成功');
    }

    //根据类目获取商品
    public function getAllGoodsByType()
    {
        if(empty(self::$params['type_id'])){
            $where = [];
        }else{
            $type = self::$goods_type_model->getInfoById(self::$params['type_id']);
            if(empty($type)) return self::success_result([]);
            if($type['pid'] == 0){
                $son_type = self::$goods_type_model->getAllIdByPid($type['type_id']);
                $where = ['type_id'=>['in',$son_type]];
            }else{
                $where = ['type_id'=>self::$params['type_id']];
            }
        }
        $data = self::$model->getLists($where,[]);
        return self::success_result($data['lists']);
    }
    // 修改商品状态
    public function renewalGoodsStatus()
    {
        if(empty(self::$params['id'])) {
            return self::error_result('请选择要操作的商品');
        }


    
        $status = self::$model->where(['id'=>self::$params['id']])->value('status');
        if(empty($status)) {
            return self::error_result('没有对应的商品信息');
        }

        $status = $status == 1 ? -1:1;
        $re = self::$model->where(['id'=>self::$params['id']])->update(['status'=>$status]);
        if(!$re) {
            return  self::error_result('修改状态失败');
        }
        return self::success_result([],'修改状态成功');
    }
}