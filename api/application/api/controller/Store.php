<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/16 17:56
 * doc: 门店管理
 *+++++++++++++++++++++++++++++
 */


namespace app\api\controller;


use think\Request;

class Store extends Base {
    private static $model;
    public function __construct(Request $request = null) {
        parent::__construct($request);
        self::$model = new \app\common\model\Store();
    }


    public function getLists()
    {
        $data = self::$model->getLists($this->_listFilter(),$this->makePage());
        return  self::success_result($data['lists'],'查询成功',[],$data['count']);
    }

    private function _listFilter(){
        $where = [];
        if(!empty(self::$params['name']))   $where['name'] = ['like','%'.self::$params['name'].'%'];
        return $where;
    }

    //新增或编辑门店
    public function edit()
    {
        $validate = $this->validate(self::$params,'store.edit');
        if($validate !== false) return self::error_result($validate);
        $re = self::$model->edit(self::$params,self::$params['id']);
        if(!$re)    return self::error_result('操作失败，请重试');
        return self::success_result();
    }
    



    
}