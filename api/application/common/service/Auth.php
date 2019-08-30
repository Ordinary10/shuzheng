<?php
/*+++++++++++++++++++++++++++++
 * Created by PhpStorm
 * User: a06220226
 * Date: 2018/7/9 10:39
 * doc: 权限类
 *+++++++++++++++++++++++++++++
 */


namespace app\common\service;


use app\common\model\AuthGroup;
use app\common\model\AuthGroupAccess;
use app\common\model\AuthRule;

class Auth {

    //权限验证,如果访问的方法在系统权限中，则验证该用户是否有该权限
    public static function check($node,$userInfo)
    {
        if(empty($node))    return  false;
        $node = strtolower($node);
        $auth_rule_model=new AuthRule();
        $all_rules = $auth_rule_model->getRulesByIds('*');
        if(empty($all_rules))   return  false;
        $all_node = array_column($all_rules,'name');
        if(!in_array($node,$all_node))  return true;
        //获取该用户所有权限
        $auth_model = new AuthGroupAccess();
        $rule_ids = $auth_model->getRuleIds($userInfo['uid']);
        if($rule_ids == '*')    return true;
        $rules = $auth_rule_model->getRulesByIds($rule_ids['rules']);
        if(empty($rules))   return false;
        $user_node = array_column($rules,'name');
        //如果访问的方法在系统权限中，则验证该用户是否有该权限
        return  in_array($node,$user_node);
    }

    //获取用户权限登陆调用
    public static function getAuth($uid)
    {
        $auth_model=new AuthGroupAccess();
        $rule_ids=$auth_model->getRuleIds($uid);
        $auth_rule_model=new AuthRule();
        $rules=$auth_rule_model->getRulesByIds($rule_ids['rules']);
        if(empty($rules))   return [];

        $rules=array_column($rules,null,'id');
        foreach ($rules as $k=>$rule){
            if($rule['type'] != 1)  continue;       //只处理PC端的
            if($rule['is_topmenu']==1){
                $top_menus[]=$rule;
            }
            $nodes[]=$rule;
            if($rule['level']==1){
                $modules[]=$rule;
            }elseif($rule['level']==2){
                $menus[$rules[$rule['pid']]['name']][]=$rule;
            }elseif ($rule['level']==3){
                $child_menus[]=$rule;
            }else{
                $events[]=$rule;
            }
        }
        //组装目录列表
        if(!empty($menus) && !empty($child_menus)){
            foreach ($modules as $module){
                foreach ($menus[$module['name']] as &$menu){
                    foreach ($child_menus as $key=>$child_menu){
                        if($child_menu['pid']==$menu['id']){
                            $menu['children'][]=$child_menu;
                            unset($child_menus[$key]);
                        }
                    }
                }
            }
        }
        isset($modules) && array_multisort(array_column($modules,'sort'),SORT_ASC,$modules);

        return [
            'modules'=>isset($modules) ? $modules : [],
            'menus'=>isset($menus) ? $menus : [],
            'top_menus'=>isset($top_menus) ? $top_menus : [],
            'event'=>isset($events) ? $events : [],
            'nodes'=>isset($nodes) ? $nodes : [],
            'is_super'=>$rule_ids['id'] == 1 ? 1 : 0,
        ];
    }

    //根据角色以树形结构组装权限节点
    public function getRoleAuthTree($role_id=0,$ids = '*')
    {
        $auth_rule_model=new AuthRule();
        $all_rules=$auth_rule_model->getRulesByIds($ids);
        if(empty($all_rules))   return [];
        // 处理一级节点
        $id = [];
        foreach ($all_rules as $k => $val) {
            if($val["level"] == 1 && $val["status"] == 0)
                foreach ($all_rules as $key => $value) {
                    if($value["pid"] == $val["id"])  unset($value[$key]);
                }
        }

        $rule_ids=[];
        if(!empty($role_id)) {
            $auth_model = new AuthGroup();
            $role_info = $auth_model->getInfoById($role_id);
            if (empty($role_info))  return [];
            $rule_ids=$role_info['rules']=='*' ? '*' : explode(',',$role_info['rules']);
        }
        //判断哪些权限是该角色拥有的
        foreach ($all_rules as &$val){
            $val['value']=$val['id'];
            $val['checked']=$rule_ids=='*' || in_array($val['id'],$rule_ids) ? true : false;
            $val['data']=[];
        }
        $trees=[];
        return  $this->rulesToTree($all_rules,$trees);
    }

    private function rulesToTree($rules,$trees){
        foreach ($rules as $key=>$rule){
            if($rule['level']==1){
                $trees[]=$rule;
                unset($rules[$key]);
            }else{
                foreach ($trees as &$tree){
                    if (!isset($tree['data']))  $tree['data']=[];
                    if(!empty($tree['data'])){
                        foreach ($tree['data'] as $k => &$val){
                            if($rule['pid']==$val['id']){
                                $val['data'][]=$rule;
                                unset($rules[$key]);
                                break;
                            }
                        }
                    }
                    if($rule['pid']==$tree['id']){
                        $tree['data'][]=$rule;
                        unset($rules[$key]);
                        break;
                    }

                }
            }
        }
        if(!empty($rules)){
            return  $this->rulesToTree($rules,$trees);
        }else{
            return  $trees;
        }
    }





}