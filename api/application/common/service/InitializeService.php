<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 18:04
 * doc: 初始化界面类
 *+++++++++++++++++++++++++++++
 */
namespace app\common\service;
use app\common\model\AuthGroup;
use app\common\model\CheckoutOrder;
use app\common\model\Company;
use app\common\model\PurchaseOrder;
use app\common\model\Supplier;

class InitializeService extends BaseService {

    private static $userInfo;
  
    private $types = [
       'role',     // 角色信息
       'company',  // 部门信息
       'supplier', // 供应商信息
       'purchase_order_status',              //采购单状态
       'checkout_status',                   //出库单状态
    ];

    public function getData($types,$userInfo)
    {
        // 处理发送过来的数据转数组
        $types = empty($types) ? [] : collection($types)->toArray();
        $data = [];
        self::$userInfo = $userInfo;
        foreach ($types as $type){
            if(!in_array($type,$this->types))   continue;
            $type_arr = explode('_',$type);
            $fun = 'get';
            foreach ($type_arr as $val){
                $fun .= ucfirst($val);
            }
            if(!method_exists($this,$fun)){
                $data[$type] = [];
            }else{
                $data[$type] = $this->{$fun}($userInfo);
            }
        }
        return  $data;
    }

    // 获取公司信息
    private function getCompany() {
        $company_model = new Company();
        return $company_model->field('id,name')->select();
    }

    // 获取角色信息
    private function getRole() {
        $auth_group = new AuthGroup();
        return $auth_group->field('id,title as name')->select();
    }

    // 获取供应商信息
    private function getSupplier() {
        $supplier_model = new Supplier();
        return $supplier_model->field('id,name')->select();
    }

    private function getPurchaseOrderStatus(){
        $model= new PurchaseOrder();
        $status = $model->status;
        $data = [];
        foreach ($status as $key=>$val){
            $data[] = ['id'=>$key,'name'=>$val];
        }
        return $data;
    }

    private function getCheckoutStatus(){
        $model = new CheckoutOrder();
        $status = $model->status;
        $data = [];
        foreach ($status as $key=>$val){
            $data[] = ['id'=>$key,'name'=>$val];
        }
        return $data;
    }

}