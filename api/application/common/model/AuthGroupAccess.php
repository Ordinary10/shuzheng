<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 分组记录管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;
use think\Model;

class AuthGroupAccess extends Model {

    public function getRuleIds($uid)
    {
        $ids=$this->alias('a')->where(['a.uid'=>$uid,'b.status'=>1])
            ->join('auth_group b','a.group_id=b.id','LEFT')
            ->join('user ur','ur.uid=a.uid','LEFT')
            ->field('a.uid,b.id,b.title,b.rules')
            ->find();
        return  empty($ids) ? [] : $ids->toArray();
    }

    public function edit($uid,$group_id)
    {
        $info=$this->where(['uid'=>$uid])->find();
        if(empty($info)){
            return  $this->insert(['uid'=>$uid,'group_id'=>$group_id]);
        }else{
            if($info['group_id']==$group_id)    return  true;
            return  $this->where(['uid'=>$uid])->update(['group_id'=>$group_id]);
        }
    }

    //添加租赁公司管理员
    public function addAdmin($uid)
    {
        return  $this->insert(['uid'=>$uid,'group_id'=>3]);
    }

    // 获取用户角色id
    public function getRoleId($uid)
    {
        return $this->where(['uid'=>$uid])->field('group_id')->find();
        
    }

}