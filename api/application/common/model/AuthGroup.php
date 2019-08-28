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
    public function getLists($map,$paging)
    {
        $count = $this->where($map)->count('id');
        if(empty($count))
             return  ['count'=>0,'lists'=>[]];
        $lists = $this->where($map)->page($paging['page'],$paging['limit'])
                          ->field('id,title,status,company_id')->select();
        return ['count'=>$count,'lists'=>collection($lists)->toArray()];
    }

    public function getGroupByCompany($company_id)
    {
        $lists = $this->where(['company_id'=>['in',[0,$company_id]],'status'=>1,'id'=>['neq',1]])->select();
        return $lists ? collection($lists)->toArray() : [];
    }

    /**
     * 添加角色
     * @param $data array 标题,权限
     * @param $company_id int 公司ID
     * @return int|string
     * User: 詹宇恒
     * 新增角色
     */
    public function addAuth($data,$company_id)
    {
        $data = [
            'title'  =>  $data['title'],
            'status' =>  1,
            'company_id' =>  $company_id,
            'rules' =>  $data['rules'],
            'ctime' =>  date('Y-m-d H:i:s')
        ];
        return  $this->insert($data);
    }

    /**
     * @param $data
     * @param $id
     * @return false|int
     * User: 詹宇恒
     * 修改角色
     */
    public function setAuth($data, $id)
    {
        return $this->save($data, array('id' => $id));
    }

    /**
     * @param $id
     * @return int
     * User: 詹宇恒
     * 删除角色
     */
    public function deleAuth($id)
    {
        return $this->where(array('id' => $id))->update(['status'=>0]);
    }

    public function getInfoById($id,$company_id)
    {
        $info = $this->where(['id'=>$id])->field('id,title,status,rules,company_id,ctime')->find();
        $group_model = new AuthGroupRules();
        $authority = $group_model->where(['group_id'=>$id,'company_id'=>$company_id])->value('rules');   
        if(!empty($authority))
            $info['rules'] = $authority;
        return  empty($info) ? [] : $info->toArray();
    }

    // 修改角色状态
    public function changeStatus($id,$oldStatus)
    {
        return  $this->where(['id'=>$id])->update(['status'=>$oldStatus==1 ? -1 : 1]);
    }
}