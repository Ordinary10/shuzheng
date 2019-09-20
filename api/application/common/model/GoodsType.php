<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/9 16:28
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class GoodsType extends Model {

    public function getLists()
    {
        $data = $this->field('type_id as id,pid,type_name')->select();
        return empty($data) ? [] : collection($data)->toArray();
    }

    public function getTopType()
    {
        $data = $this->field('type_id as id,pid,type_name')->where(['pid'=>0])->select();
        return empty($data) ? [] : collection($data)->toArray();
    }

    public function getInfoById($type_id)
    {
        $info = $this->where(['type_id'=>$type_id])->find();
        return empty($info) ? [] : $info->toArray();
    }

    //根据父类目获取下面的所有子类目ID
    public function getAllIdByPid($pid)
    {
        return $this->where(['type_id|pid'=>$pid])->column('type_id');
    }
    
    public function edit($param,$id)
    {
        $save_data = [
            'pid' => $param['pid'],
            'type_name' => $param['type_name'],
        ];
        if(empty($id)){
            return  $this->insert($save_data);
        }
        return  $this->where(['type_id'=>$id])->update($save_data);
    }

    public function del($ids)
    {
        return  $this->where(['type_id'=>['in',$ids]])->delete();
    }
    
    
    
}