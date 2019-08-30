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

}