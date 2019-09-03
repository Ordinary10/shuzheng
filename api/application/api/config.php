<?php
/*+++++++++++++++++++++++++++++
 * CopyRight yundieTech
 * User: mzq
 * Date: 2019/5/22 9:50
 * Doc: 公共配置
 *+++++++++++++++++++++++++++++
 */
return  [
    'tk_uptime'=>7200,              //token更新周期
    'tk_expire_time'=>7200,         //token过期时间

    'wx_appId'=>'wx2a9bb7fe55423cc1',
    'wx_appSecret'=>'4f21a11a5a0452cc7d21803d176597ce',

    //腾讯云身份证识别接口配置
    'tencentyun_img'=>[
        'app_id'    =>  '1258154484',
        'bucket'    =>  'YOUR_BUCKET',
        'secret_id' =>  'AKIDc5TM7niRHey767xc6WmuqjT1SmV58ozz',
        'secret_key'=>  '3xhy62rX3OcmaEp155uat1Phdkx12whT',
    ],

    //阿里OSS配置
    'ali_oss' => [
        'bucket'            =>  'zucheguanjia',
        'endpoint'          =>  'http://oss-cn-qingdao.aliyuncs.com',
        'accessKeyId'       =>  'OloVUQHizGZCf1iI',
        'accessKeySecret'   =>  'usOFFVDmWLfDNLyCAQpmzCMHag6w3N',
    ],

];
 
