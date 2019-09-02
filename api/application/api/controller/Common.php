<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 15:56
 * doc: 公共类
 *+++++++++++++++++++++++++++++
 */
namespace app\api\controller;
use app\common\service\InitializeService;

class Common extends Base {

    // 获取页面初始化的数据
    public function getPageInitInfo() {
    	$initial_model = new InitializeService();
        return self::success_result($initial_model->getData(self::$params['type'],self::$userInfo));
    }

}