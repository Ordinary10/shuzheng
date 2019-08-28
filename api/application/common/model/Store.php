<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/16 18:07
 * doc: 门店表
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class Store extends Model {

    public function getLists($where,$page)
    {
        $data = ['lists'=>[],'count'=>0];
        $data['count'] = $this->count();
        if(empty($data['count']))   return  ['lists'=>[],'count'=>0];
        $lists = $this->where($where)->page($page)->select();
        $data['lists'] = empty($lists) ? [] : collection($lists)->toArray();
        return  $data;
    }
    
    public function edit($param,$id)
    {
        $save_data = [
            'name' => $param['name'],
            'manager' => $param['manager'],
            'remark' => $param['remark'],
        ];
        if(empty($id)){
            $save_data['ctime'] = date('Y-m-d H:i:s');
            return $this->insertGetId($save_data);
        }else{
            $re = $this->where(['id'=>$id])->update('name');
            return $re === false ? false : $id;
        }
    }
}