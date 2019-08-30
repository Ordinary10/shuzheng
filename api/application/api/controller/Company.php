<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 15:56
 * doc: 公司管理
 *+++++++++++++++++++++++++++++
 */
namespace app\api\controller;

class Company extends Base {

    private static $company_model;
    public function __construct() {
        parent::__construct();
        self::$company_model = new \app\common\model\Company();
    }
    
    // 获取公司列表
    public function getCompanyLists()
    {
    	$where = $this->condition();
    	$data = self::$company_model->getCompanyLists($where,$this->makePage());
    	if(empty($data['lists'])) {
            return self::success_result([],'查询成功',0);
        }
        return self::success_result($data['lists'],'查询成功',[],$data['count']);
    }

    // 列表搜索条件
    private function condition() {
        $where = [];
        return $where;
    }

    // 编辑门店
    public function editorCompany()
    {
        if(empty(self::$params)) {
            return  self::error_result('请填写要编辑的信息');
        }
        $re = self::$company_model->editorCompany(self::$params);
        if($re !=0 && $re !=1) {
            return  self::error_result('编辑信息失败');
        }
        return self::success_result([],'编辑信息成功');
    }

    // 修改公司状态
    public function renewalCompany()
    {
        if(empty(self::$params['id'])) {
            return  self::error_result('请选着要修改状态的公司');
        }
        $data = self::$company_model->where(['id'=>self::$params['id']])->find();
        if(empty($data)) {
            return  self::error_result('没有对应的公司信息');
        }
        $status = $data['status'] == 1 ? 0:1;
        $re = self::$company_model->where(['id'=>self::$params['id']])->update(['status'=>$status]);
        if(!$re) {
        	return  self::error_result('修改状态失败');
        }
        return self::success_result([],'修改状态成功');
    }
}
