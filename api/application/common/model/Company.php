<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: 
 * Date: 2019/8/30 
 * Doc: 公司管理
 *+++++++++++++++++++++++++++++
 */
namespace app\common\model;
use think\Model;

class Company extends Model
{
	// 获取公司列表
    public function getCompanyLists($where,$page)
    {
        $count = $this->where($where)->count('id');
        if($count == 0) {
            return  ['count'=>0,'lists'=>[]];
        }
        $lists = $this->where($where)->page($page)->select();
        return ['count'=>$count,'lists'=>collection($lists)->toArray()];
    }

    // 编辑公司信息
    public function editorCompany($data)
    {
        $save_data = [
            'name' => $data['name'],
            'mobile' => $data['mobile'],
            'staff_nums' => $data['staff_nums'],
            'remark' => $data['remark']
        ];
        if(empty($data['id'])) {
            $save_data['ctime'] = date('Y-m-d H:i:s');
            $save_data['status'] = 1;
            return  $this->insertGetId($save_data);
        }
        return $this->where(['id'=>$data['id']])->update($save_data);
    }
}