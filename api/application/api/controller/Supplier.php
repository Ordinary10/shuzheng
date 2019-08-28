<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/27 17:43
 * doc: 供应商管理
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;



class Supplier extends Base {
    private static $model;

    public function __construct() {
        parent::__construct();
        self::$model = new \app\common\model\Supplier();
    }

    public function getLists()
    {
        $map = [];
        if(!empty(self::$params['name']))   $map = ['name'=>['like','%'.self::$params['name'].'%']];
        $lists = self::$model->getLists($map);
        foreach ($lists['lists'] as &$val){
            $val['status_name'] = $val['status'] == 1 ? '正常' : '禁用';
        }
        return  self::success_result($lists['lists'],'查询成功',[],$lists['count']);
    }

    //供应商编辑或新增
    public function edit()
    {
        if(empty(self::$params['name']))    return self::error_result('名称必填');
        $re = self::$model->edit(self::$params,self::$params['id']);
        if($re === false)   return self::error_result('操作失败，请重试');
        return self::success_result();
    }


}