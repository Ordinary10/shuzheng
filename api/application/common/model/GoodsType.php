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
        $data = $this->select();
        return empty($data) ? [] : collection($data)->toArray();
    }
    
    public function edit($param,$id)
    {
        $save_data = [
            'pid' => $param['pid'],
            'type_name' => $param['type_name'],
        ];
        if(empty($id)){
            return  $this->insert($save_data);
        }else{
            return  $this->where(['type_id'=>$id])->update($save_data);
        }
    }

    public function del($ids)
    {
        return  $this->where(['type_id'=>['in',$ids]])->delete();
    }
    
    
    
}