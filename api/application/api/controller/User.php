<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 15:56
 * doc: 
 *+++++++++++++++++++++++++++++
 */
namespace app\api\controller;
use app\common\model\AuthGroupAccess;

class User extends Base {

    private static $user_model;
    public function __construct() {
        parent::__construct();
        self::$user_model = new \app\common\model\User();
    }

    // 获取用户列表
    public function getUserLists()
    {
        $where = $this->condition();
        $data = self::$user_model->getLists($where,$this->makePage());
        if(empty($data['lists'])) {
            return self::success_result([],'查询成功',0);
        }
        return self::success_result($data['lists'],'查询成功',[],$data['count']);
    }

    // 列表搜索条件
    private function condition(){
        if(isset(self::$params['status']) && self::$params['status']!=='all') {
            $where['a.status'] = self::$params['status'];
        }
        if(!empty(self::$params['account'])) {
            $where['a.account'] = self::$params['account'];
        }
        if(!empty(self::$params['username'])) {
            $where['a.uname'] = self::$params['uname'];
        }
        if(!empty(self::$params['role'])) {
            $where['c.group_id'] = self::$params['role'];
        }
        return $where;
    }

    //编辑或新增用户
    public function edit()
    {
        $validate = $this->validate(self::$params,'user.edit');
        if($validate !== true) {
            return self::error_result($validate);
        }
        if(empty(self::$params['uid']) && empty(self::$params['pwd'])){
            return  self::error_result('请填写初始密码');
        }
        if(!empty(self::$params['pwd']) && (strlen(self::$params['pwd']) > 15 || strlen(self::$params['pwd']) < 6)){
            return  self::error_result('密码长度6-15位');
        }
        $is_has = self::$user_model->where(['account'=>self::$params['account']])->value('uid');
        if(empty(self::$params['uid'])) {
            if($is_has) {
                return  self::error_result('该用户名已经存在！');
            }
            $uid = self::$user_model->addUser(self::$params);
        } else {
            if($is_has && $is_has != self::$params['uid']) {
                return  self::error_result('该用户名已经存在');
            }
            $uid = self::$user_model->edit(self::$params,self::$params['uid'],self::$params['pwd']);
        }
        if(!$uid) {
            return  self::error_result('用户编辑失败');
        }
        //保存用户角色
        $group_access_model = new AuthGroupAccess();
        $group_access_model->edit($uid,self::$params['role']);
        return self::success_result([],'编辑成功');
    }

    // 修改用户密码
    public function changePwd()
    {
        if(empty(self::$params['old_pwd'])) {
            return  self::error_result('请填写初始密码');
        }
        if(empty(self::$params['new_pwd1']) || empty(self::$params['new_pwd2'])) {
            return  self::error_result('请输入新密码');
        }
        if(strlen(self::$params['new_pwd1']) > 15 || strlen(self::$params['new_pwd1']) < 6) {
            return  self::error_result('第一次密码长度不符合要求');
        }
        if(strlen(self::$params['new_pwd2']) > 15 || strlen(self::$params['new_pwd2']) < 6) {
            return  self::error_result('第二次密码长度不符合要求');
        }
        if (self::$params['old_pwd'] === self::$params['new_pwd1']) {
            return  self::error_result('新密码不能与旧密码一致！');
        }
        $info = self::$user_model->getInfoByAccount(self::$userInfo['account']);
        if(!$info || make_pwd(self::$params['old_pwd'],$info['salt']) != $info['pwd']) {
            return  self::error_result('旧密码输入错误！');
        }
        $salt = rand(100000,999999);
        $new_pwd = make_pwd(self::$params['new_pwd1'],$salt);
        $re = self::$user_model->where(['uid'=>self::$userInfo['uid']])->update(['salt'=>$salt,'pwd'=>$new_pwd]);
        if($re) return  self::success_result([],'密码修改成功！');
        return  self::error_result('密码修改失败，请重试！');
    }
}