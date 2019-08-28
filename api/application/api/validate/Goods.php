<?php
/*+++++++++++++++++++++++++++++
 * CopyRight: yundieTech
 * User: a06220226
 * Date: 2019/8/8 16:11
 * doc: 
 *+++++++++++++++++++++++++++++
 */


namespace app\api\validate;


class Goods extends Base {
    protected $rule = [
        'name' => 'require|length:1,20',
        'unit' => 'require|length:1,20',
        'type_id' => 'require',
        'type_name' => 'require|length:1,10'
    ];

    protected $message = [
        'name.require' => '请输入名称',
        'name.length' => '名称为1-20个字符',
        'unit.require' => '请输入单位',
        'unit.length' => '单位为1-5个字符',
        'type_id' => '请选择所属种类',
        'type_name'=>'名称为1-10位字符'
    ];

    protected $scene = [
        'edit_goods'=>'name,unit,type_id',
        'edit_goods_type'=>'type_name',
    ];
}