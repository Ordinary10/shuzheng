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
        $where = $this->listFilter(self::$params);
        $lists = self::$user_model->getLists($where,$this->makePage());
        if(empty($lists['lists'])) {
            return self::success_result([],'查询成功');
        }
        return self::success_result($lists['lists'],'查询成功',[],$lists['count']);
    }

    private function listFilter($param){
        $where=[];
        if(isset($param['status']) && $param['status']!=='all') {
            $where['a.status'] = $param['status'];
        }
        if(!empty($param['account'])) {
            $where['a.account'] = $param['account'];
        }
        if(!empty($param['username'])) {
            $where['a.uname'] = $param['uname'];
        }
        if(!empty($param['role'])) {
            $where['c.group_id'] = $param['role'];
        }
        return  $where;
    }
    //编辑或新增用户
    public function edit()
    {
        $validate = $this->validate(self::$params,'user.edit');
        if($validate !== true)  return self::error_result($validate);

        if(empty(self::$params['uid']) && empty(self::$params['pwd'])){
            return  self::error_result('请填写初始密码！');
        }
        if(!empty(self::$params['pwd']) && (strlen(self::$params['pwd']) > 15 || strlen(self::$params['pwd']) < 6)){
            return  self::error_result('密码长度6-15位！');
        }
        $is_has = self::$user_model->where(['account'=>self::$params['account']])->value('uid');
        if(empty(self::$params['uid'])){
            if($is_has) return  self::error_result('该用户名已经存在！');
            $uid = self::$user_model->add_user(self::$params);
        }else{
            if($is_has && $is_has != self::$params['uid']) {
                return  self::error_result('该用户名已经存在！');
            }
            $uid = self::$user_model->edit(self::$params,self::$params['uid'],self::$params['pwd']);
        }
        if(!$uid) {
            return  self::error_result('用户编辑失败！');
        }
        //保存用户角色
        $group_access_model = new AuthGroupAccess();
        $group_access_model->edit($uid,self::$params['role']);
        return self::success_result();
    }

    // 修改用户密码
    public function changePwd()
    {
        if(empty(self::$params['old_pwd']) || empty(self::$params['new_pwd1']) || empty(self::$params['new_pwd2']))
            return  self::error_result('请填写必填信息！');
        if(empty(self::$params['old_pwd'])){
            return  self::error_result('请填写初始密码！');
        }elseif (self::$params['old_pwd'] === self::$params['new_pwd1']){
            return  self::error_result('新密码不能与旧密码一致！');
        }elseif(strlen(self::$params['new_pwd1']) > 15 || strlen(self::$params['new_pwd1']) < 6){
            return  self::error_result('密码长度6-15位！');
        }elseif (self::$params['new_pwd1'] !== self::$params['new_pwd2']){
            return  self::error_result('两次输入密码不一致，请重新输入！');
        }

        $info = self::$user_model->getInfoByAccount(self::$userInfo['account']);
        if(!$info || make_pwd(self::$params['old_pwd'],$info['salt']) != $info['pwd'])
            return  self::error_result('旧密码输入错误！');
        $salt = rand(100000,999999);
        $new_pwd = make_pwd(self::$params['new_pwd1'],$salt);
        $re = self::$user_model->where(['uid'=>self::$userInfo['uid']])->update(['salt'=>$salt,'pwd'=>$new_pwd]);
        if($re) return  self::success_result([],'密码修改成功！');
        return  self::error_result('密码修改失败，请重试！');
    }

}