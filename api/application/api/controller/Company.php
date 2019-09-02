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
        $this->disposeData($data['lists']);
        return self::success_result($data['lists'],'查询成功',[],$data['count']);
    }

    // 处理数据
    public function disposeData(&$data)
    {
        foreach ($data as $k => &$val) {
            $val['type_name'] = self::$company_model->companyType[$val['type']];
            $val['status_name'] = self::$company_model->companyStatus[$val['status']];
        }
    }

    // 列表搜索条件
    private function condition() {
        $where = [];
        // 公司名
        if(!empty(self::$params['name'])) {
            $where['name'] = ['like','%'.self::$params['name'].'%'];
        }
        // 经营类型
        if(!empty(self::$params['type'])) {
            $where['type'] = self::$params['type'];
        }
        // 状态
        if(!empty(self::$params['status'])) {
            $where['status'] = self::$params['status'];
            if(self::$params['status'] == 2) {
                $where['status'] = 0;
            }
        }
        return $where;
    }

    // 编辑门店
    public function editorCompany()
    {
        $validate = $this->validate(self::$params,'Company.edit');
        if($validate !== true) {
            return self::error_result($validate);
        }
        if(empty(self::$params['id'])) {
            $info = self::$company_model->where(['name'=>self::$params['name']])->find();
            if(!empty($info)) {
                return  self::error_result('公司名重复');
            }
        }
        $re = self::$company_model->editorCompany(self::$params);
        if(!$re) {
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
