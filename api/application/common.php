<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
require_once __DIR__ .'/../vendor/aliyuncs/oss-sdk-php/autoload.php';
// 应用公共文件
if (!function_exists('dd')) {
    /**
     * 打印变量并终止程序，主要用于调试
     * @param $var mixed 变量
     * @return  void
     */
    function dd($var)
    {
        dump($var);
        exit();
    }
}

/**
 * 构建层级（树状）数组
 * @param array  $array          要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name       父级ID的字段名
 * @param string $child_key_name 子元素键名
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
{
    $counter = array_children_count($array, $pid_name);
    if (!isset($counter[0]) || $counter[0] == 0) {
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0] > 0) {
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
            array_push($array, $temp);
        } else {
            if ($temp[$pid_name] == 0) {
                $tree[] = $temp;
            } else {
                $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter = array_children_count($array, $pid_name);
    }

    return $tree;
}

/**
 * 子元素计数器
 * @param array $array
 * @param int   $pid
 * @return array
 */
function array_children_count($array, $pid)
{
    $counter = [];
    foreach ($array as $item) {
        $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
        $count++;
        $counter[$item[$pid]] = $count;
    }

    return $counter;
}

/**
 * 把元素插入到对应的父元素$child_key_name字段
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name)
{
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if (!isset($item[$child_key_name]))
                $item[$child_key_name] = [];
            $item[$child_key_name][] = $child;
        }
    }

    return $parent;
}

//单条上传
if (!function_exists('ossUpload')) {
    function ossUpload($newPath,$filePath){
        $config = config('ali_oss');
        if(empty($config) || !isset($config['bucket']) || !isset($config['endpoint'])
            || !isset($config['accessKeyId']) || !isset($config['accessKeySecret'])){
            return  false;
        }
        try{
            $ossClient = new \OSS\OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
            $re=$ossClient->uploadFile($config['bucket'], $newPath, $filePath);
            if(!$re || !isset($re['info']['url'])) {
                return  false;
            }
            return $re['info']['url'];
        }catch(\OSS\Core\OssException $e){
            \think\Log::mylog('oss',$e->getErrorMessage(),'oss_error');
            return  false;
        }
    }
}