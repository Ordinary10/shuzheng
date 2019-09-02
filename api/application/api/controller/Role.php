<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 15:56
 * doc: 角色管理
 *+++++++++++++++++++++++++++++
 */
namespace app\api\controller;
use app\common\service\Auth;

class Role extends Base {

        private static $role_model;
    public function __construct() {
        parent::__construct();
        self::$role_model = new \app\common\model\AuthGroup();
    }
    
    // 获取角色列表
    public function getRoleList()
    {
        $where = $this->condition();
        $data = self::$role_model->getRoleList($where,$this->makePage());
        if(empty($data['lists'])) {
            return self::success_result([],'查询成功',0);
        }
        return self::success_result($data['lists'],'查询成功',[],$data['count']);
    }

    // 查询条件
    private function condition() {
        $where = [];
        // 公司名
        if(!empty(self::$params['title'])) {
            $where['name'] = ['like','%'.self::$params['title'].'%'];
        }
        return $where;
    }

    // 编辑角色管理
    public function editorRole()
    {
        if(empty(self::$params)) {
            return  self::error_result('请输入要编辑的信息');
        }
        // 验证角色名字是否重复
        $id = self::$role_model->where(['title'=>self::$params['title']])->value('id');
        if(self::$params['id'] != $id) {
            return self::error_result('角色名重复');
        }
        $re = self::$role_model->editorRole(self::$params);
        if(!$re) {
            return self::error_result('编辑角色失败');
        }
        return self::success_result([],'编辑角色成功');
    }

    // 修改角色状态
    public function renewalRoleStatus()
    {
        if(empty(self::$params['id'])) {
            return  self::error_result('请选择要修改状态的角色');
        }
        $status = self::$role_model->where(['id'=>self::$params['id']])->value('status');
        if(empty($status)) {
        	return self::error_result('没有对应的角色信息');
        }
        $status = $data['status'] == 1 ? -1:1;
        $re = self::$role_model->where(['id'=>self::$params['id']])->update(['status'=>$status]);
        if(!$re) {
        	return  self::error_result('修改状态失败');
        }
        return self::success_result([],'修改状态成功');
    }

    // 获取某个角色的权限，只能查看该用户所拥有的权限
    public function getRoleRules()
    {
        if(!isset(self::$params['role_id'])) {
            return self::throw_error_result('请选着角色');
        }
        $rules = self::$role_model->getInfoById(self::$userInfo['group_id']);
        if(empty($rules) || $rules['status']!=1) {
            return  self::error_result('该角色不存在或已被冻结');
        }
        $rule_ids = $rules['rules']=='*' ? '*' : explode(',',$rules['rules']);
        $auth_model = new Auth();
        $rule_lists=$auth_model->getRoleAuthTree(self::$params['role_id'],$rule_ids);
        return self::success_result($rule_lists);
    }
}
