<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/12 18:15
 * doc: 公共服务类
 *+++++++++++++++++++++++++++++
 */
namespace app\common\service;
use app\common\model\Company;
use app\common\model\GoodsType;


class CommonalityService extends BaseService {

    /**
     * 根据门店ID获取公司名
     * @param int $dp_id 门店ID
     * @return string 公司名称
     */
    public function getCompanyNameById($dp_id)
    {
        $model = new Company();
        static $data;
        if(!empty($data)){
            return isset($data[$dp_id]) ? $data[$dp_id] : '';
        }
        $data = $model->select();
        $data = array_column($data,'name','id');
        return isset($data[$dp_id]) ? $data[$dp_id] : '';
    }

    /**
     * 根据类目id获取类目名
     * @param int $type_id 类目id
     * @return string 类目名
     */
    public function getGoodsNameById($type_id)
    {
        $model = new GoodsType();
        static $data;
        if(!empty($data)){
            return isset($data[$type_id]) ? $data[$type_id] : '';
        }
        $data = $model->field('type_id,type_name')->select();
        $data = array_column($data,'type_name','type_id');
        return isset($data[$type_id]) ? $data[$type_id] : '';
    }

    // 获取类目为全路径
    public function getGoodsTypeParentId($type_id)
    {
        $model = new GoodsType();
        static $data;
        if(!empty($data)){
            return isset($data[$type_id]) ? $data[$type_id] : '';
        }
        $data = $model->field('type_id,pid')->select();
        $data = array_column($data,'pid','type_id');
        return isset($data[$type_id]) ? $data[$type_id] : '';
    }


}