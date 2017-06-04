<?php
/**
 * 函数库
 *
 * @Author: lizengcai
 */
function p($arr) {
    echo '<pre>';
    print_r($arr);
    echo '</pre>';
}

/**
 * 功能:
 * 1.加载配置项C($sysConfig)=>C($userConfig)
 * 2.读取配置项C('CODE_LEN')
 * 3.临时动态改变配置项C('CODE_LEN',20)
 * 4.C():读取全部配置项
 */
function C($var = NULL, $value = NULL) {
    static $config = array();
    //加载配置项
    if(is_array($var)) {
        $config = array_merge($config, array_change_key_case($var, CASE_UPPER));
        return;
    }
    //动态改变配置项
    if(is_string($var)) {
        $var = strtoupper($var);
        //两个参数传递
        if(!is_null($value)) {
            $config[$var] = $value;
            return;
        }

        return isset($config[$var]) ? $config[$var] : NULL;
    }
    //读取全部配置项
    if(is_null($var) && is_null($value)) {
        return $config;
    }
}
?>