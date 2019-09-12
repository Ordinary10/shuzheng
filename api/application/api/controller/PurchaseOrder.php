<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 17:36
 * doc: 采购单管理
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;


use app\common\model\PurchaseOrderDetail;
use app\common\model\PurchaseOrderProgress;
use app\common\service\CommonService;
use app\common\service\PurchaseOrderService;

class PurchaseOrder extends Base {
    private static $service;
    private static $order_model;
    private static $detail_model;

    public function __construct()
    {
        parent::__construct();
        self::$service = new PurchaseOrderService();
        self::$order_model = new \app\common\model\PurchaseOrder();
        self::$detail_model = new PurchaseOrderDetail();
    }

    //获取列表
    public function getLists()
    {
        $where = $this->listFilter();
        if($where === false)    return self::success_result([]);
        $lists = self::$order_model->getLists($where,self::makePage());
        $common_service = new CommonService();
        $all_uid = array_column($lists['lists'],'uid');
        foreach ($lists['lists'] as &$val){
            $val['uname'] = $common_service->getUserNameByUid($val['uid'],$all_uid);
            $val['status_name'] = self::$order_model->status[$val['status']];
            $val['store_name'] = $common_service->getStoreNameByUid($val['uid'],$all_uid);
        }
        return  self::success_result($lists['lists'],'查询成功',[],$lists['count']);
    }

    private function listFilter(){
        $where = [];
        if(!empty(self::$params['name'])){          //申请人
            $user_model = new \app\common\model\User();
            $uid = $user_model->getUserId(self::$params['name']);
            if (empty($uid))    return false;
            $where['uid'] = ['in',$uid];
        }
        !empty(self::$params['apply_time']) && $where['ctime'] = ['between',[self::$params['apply_time'],self::$params['apply_time'] . '23:59:59']];
        !empty(self::$params['status']) && $where['status'] = self::$params['status'];
        if(!empty(self::$params['company']) || !empty(self::$params['name'])){
            if(!empty(self::$params['company']) && !empty(self::$params['name'])){
                $map = ['dp_id'=>self::$params['company'],'uname'=>['like', '%' . self::$params['name'] . '%']];
            }elseif (!empty(self::$params['company']) && empty(self::$params['name'])){
                $map =  ['dp_id'=>self::$params['company']];
            }elseif (empty(self::$params['company']) && !empty(self::$params['name'])){
                $map = ['dp_id'=>self::$params['company'],'uname'=>['like', '%' . self::$params['name'] . '%']];
            }
            $user_model = new \app\common\model\User();
            $uid = $user_model->getUserIdByMap($map);
            if (empty($uid))    return false;
            $where['uid'] = ['in',$uid];
        }
        return $where;
    }

    //采购单申请或编辑
    public function apply()
    {
        if(empty(self::$params['data']))  return  self::error_result('请录入需要采购的商品');
        $order_id = self::$service->editPurchaseOrder(self::$params,self::$userInfo['uid']);
        if(!$order_id)    return self::error_result(self::$service->getError());
        return  self::success_result($order_id,'操作成功');
    }

    //获取采购单详情
    public function getDetailInfo()
    {
        $order_id = self::$params['order_id'];
        if(empty($order_id)) return self::error_result('参数错误');
        $order_info = self::$order_model->getInfoById($order_id);
        if(empty($order_info))  return  self::error_result('采购单信息查询失败');
        $common_service = new CommonService();
        $order_info['uname'] = $common_service->getUserNameByUid($order_info['uid'],[$order_info['uid']]);
        $order_info['store_name'] = $common_service->getStoreNameByUid($order_info['uid'],[$order_info['uid']]);
        $order_info['status_name'] = self::$order_model->status[$order_info['status']];
        $order_info['detail'] = self::$detail_model->getInfoByOrderId($order_id);
        //流程
        $progress = new PurchaseOrderProgress();
        $order_info['progress'] = $progress->getProgressByOrderId($order_id);
        foreach ($order_info['progress'] as &$val){
            $val['status_name'] = self::$order_model->status[$val['status']];
        }
        return  self::success_result($order_info);
    }

    //采购单审核
    public function verify()
    {
        $validate = $this->validate(self::$params,'purchaseOrder.verify');
        if($validate !== true)  return self::error_result($validate);
        $re = self::$service->verify(self::$params['order_id'],self::$params,self::$userInfo['uid']);
        if(!$re)    return self::error_result(self::$service->getError());
        return  self::success_result('','操作成功');
    }

    //采购完成录入系统
    public function buy()
    {
        if(empty(self::$params['order_id']))    return self::error_result('参数错误');
        if(empty(self::$params['data']))  return  self::error_result('请录入采购商品信息');
        $re = self::$service->buy(self::$params['order_id'],self::$params,self::$userInfo['uid']);
        if(!$re)    return self::error_result(self::$service->getError());
        return  self::success_result('','操作成功');
    }

    //入库
    public function putInStorage()
    {
        if(empty(self::$params['order_id']))    return self::error_result('参数错误');
        if(empty(self::$params['data']))  return  self::error_result('请录入商品入库信息');
        $re = self::$service->putIn(self::$params['order_id'],self::$params,self::$userInfo['uid']);
        if(!$re)    return self::error_result(self::$service->getError());
        return  self::success_result('','操作成功');
    }


}