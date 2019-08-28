<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/27 17:53
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class Supplier extends Model {

    public function getLists($map)
    {
        $data = ['lists'=>[],'count'=>0];
        $data['count'] = $this->where($map)->count();
        if(empty($data['count']))   return $data;
        $lists = $this->where($map)->select();
        $data['lists'] = empty($lists) ? [] : collection($lists)->toArray();
        return  $data;
    }

    public function edit($param,$id)
    {
        $save_data = [
            'name' => $param['name'],
            'remark' =>$param['remark'],
            'status' =>$param['status'],
        ];
        if(empty($id)){
            $save_data['ctime'] = date('Y-m-d H:i:s');
            return  $this->insertGetId($save_data);
        }else{
            return $this->where(['id'=>$id])->update($save_data);
        }
    }
}