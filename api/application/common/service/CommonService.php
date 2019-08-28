<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 18:04
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\service;


use app\common\model\User;

class CommonService extends BaseService {

    /**
     * 根据ID获取用户姓名
     * @param int $uid 当前需要查询的用户ID
     * @param array $all_uid 需要查询的所有用户ID
     * @return string
     */
    public function getUserNameByUid($uid,$all_uid = [])
    {
        $model = new User();
        static $users;
        if(!empty($users)){
            return isset($users[$uid]) ? $users[$uid] : '';
        }
        $where = empty($all_uid) ? [] : ['uid',['in',$all_uid]];
        $users = $model->getUserName($where);
        $users = array_column($users,'uname','uid');
        return isset($users[$uid]) ? $users[$uid] : '';
    }


}