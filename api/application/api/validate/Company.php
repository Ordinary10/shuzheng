<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/15 15:56
 * doc: 
 *+++++++++++++++++++++++++++++
 */
namespace app\api\validate;

class Company extends Base {

    protected $rule = [
    	'name'=>'require|length:2,10',
    	'mobile'=>'require|number|length:11,15',
    	'type'=>'require|in:1,2'
    ];

    protected $message = [
        'name'=>'姓名为2-10位',
        'mobile'=>'手机号码错误',
        'type'=>'加盟类型错误',
    ];

    protected $scene = [
        'edit'=>'name,mobile,type',
    ];
}
