<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 权限管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;
use think\Model;

class AuthRule extends Model
{
    public function getRulesByIds($ids)
    {
        if ($ids === '*') {
            $re = $this->where(['status'=>1])->order('sort asc')->select();
        } else {
            $ids = is_string($ids) ? explode(',', $ids) : $ids;
            $re = $this->where(['id' => ['in', $ids],'status'=>1])->order('sort asc')->select();
        }
        return empty($re) ? [] : collection($re)->toArray();
    }

    /**
     * User: 詹宇恒
     * 修改权限
     */
    public function setRule($data, $id)
    {
        return $this->save($data, array('id' => $id));
    }

    /**
     * @param $where
     * @return int|string
     * User: 詹宇恒
     * 新增权限
     */
    public function addRule($where)
    {
        return $this->insert($where);
    }

    /**
     * @param $where
     * @param $id
     * User: 詹宇恒
     * 删除权限
     */
    public function deleRule($id)
    {
        return $this->where(array('id' => $id))->delete();
    }
}