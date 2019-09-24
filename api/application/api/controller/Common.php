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

    // 上传图片
    public function uploadImg()
    {
        $file = $_FILES['file'];//获取文件信息
        if(empty($file)) {
            return self::error_result('参数错误');
        }
        if($file['size'] < 40) {
            return self::error_result('图片太小');
        }
        if(!is_array($file['name'])){
            $file = [
                'name' => [$file['name']],
                'type' => [$file['type']],
                'tmp_name' => [$file['tmp_name']],
                'error' => [$file['error']],
                'size' => [$file['size']],
            ];
        }
        $re = [];
        foreach($file['tmp_name'] as $key=>$item){
            $houzhui = substr(strrchr($file['name'][$key], '.'), 1);
            $newPath = (empty(self::$post['type']) ? '' : self::$post['type'] . '/') . time().rand(1000,9999) . '.' . $houzhui;
            $return = ossUpload($newPath,$item);
            if(!$return) {
                return self::error_result('上传失败');
            }
            $re[] = $return;
        }
        return self::success_result(['url'=>count($re)==1 ? $re[0] : $re],'成功');
    }
}