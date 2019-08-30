<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 角色分组管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;

use think\exception\DbException;
use think\Model;
use think\Config;

class AuthGroup extends Model
{
    // 获取角色列表
    public function getRoleList($where,$page)
    {
        $count = $this->where($where)->count('id');
        if(empty($count)) {
            return  ['count'=>0,'lists'=>[]];
        }
        $lists = $this->where($map)->page($page)
                          ->field('id,title,status')->select();
        return ['count'=>$count,'lists'=>collection($lists)->toArray()];
    }

    public function getInfoById($id)
    {
        $info = $this->where(['id'=>$id])->field('id,title,status,rules,ctime')->find();
        return  empty($info) ? [] : $info->toArray();
    }

    // 编辑角色
    public function editorRole($data)
    {
        $save_data = [
            'title' => $data['title'],
            'rules' => $data['rules']
        ];
        if(empty($data['id'])) {
            $save_data['status'] = 1;
            $save_data['ctime'] = date('Y-m-d H:i:s');
            return $this->insertGetId($save_data);
        }
        return $this->where(['id'=>$data['id']])->update($save_data);
    }

}