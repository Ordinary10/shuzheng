<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/7 18:27
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\common\model;


use think\Model;

class Goods extends Model {

    public function getLists($where,$page)
    {
        $data = [
            'count'=>0,
            'lists'=>[],
        ];
        $data['count'] = $this->where($where)->count();
        if(empty($data['count']))   return  $data;
        $lists = $this->where($where);
        if(!empty($page)) $lists=$lists->page($page)->select();
        $data['lists'] = empty($lists) ? [] : collection($lists)->toArray();
        return  $data;
    }

    /**
     * 编辑或新增商品信息，返回商品ID
     * @param array $param
     * @param int $id
     * @return bool|int
     */
    public function edit($param,$id)
    {
        $save_data = [
            'name' => $param['name'],
            'type_id' => $param['type_id'],
            'unit' => $param['unit'],
            'safe_stock' => intval($param['safe_stock']),
        ];
        if(empty($id)) {
            $save_data['status'] = 1;
            $save_data['ctime'] = date('Y-m-d H:i:s');
            return $this->insertGetId($save_data);
        }
        $re = $this->where(['id'=>$id])->update($save_data);
        return $re === false ? false : $id;
    }
}