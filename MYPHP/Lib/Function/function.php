<?php
/**
 * 函数库
 *
 * @Author: lizengcai
 */
function halt($error,$level='ERROR',$type=3,$dest=NULL) {
    if(is_array($error)) {
        Log::write($error['message'],$level,$type,$dest);
    } else {
        Log::write($error,$level,$type,$dest);
    }
    $e = array();
    //开启debug操作
    if(DEBUG){
        if(!is_array($error)) {
            $trace = debug_backtrace();
            $e['message'] = $error;
            $e['file'] = $trace[0]['file'];
            $e['line'] = $trace[0]['line'];
            $e['class'] = isset($trace[0]['class']) ? $trace[0]['class'] : '';
            $e['function'] = isset($trace[0]['function']) ? $trace[0]['function'] : '';
            ob_start();
            debug_print_backtrace();
            $e['trace'] = htmlspecialchars(ob_get_clean());
        } else {
            $e = $error;
        }
    } else {
        if($url = C('ERROR_URL')){
            go($url);
        }else{
            $e['message'] = C('ERROR_MSG');
        }
    }
    include DATA_PATH.'/Tpl/halt.html';
    die;
}

function p($arr) {
    if(is_bool($arr) || is_null($arr)) {
        var_dump($arr);
    } else {
        echo '<pre style="padding: 10px;border-radius:5px;background:#f5f5f5;border:1px solid #ccc;fontsize:14px">'.print_r($arr,true).'</pre>';
    }
}

function go($url, $time=0, $msg='') {
    if(!headers_sent()) {
        $time == 0 ? header('Location:'.$url) : header("refresh:{$time};url={$url}");
        die($msg);
    } else {
        echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time) die($msg);
    }
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

function print_const() {
    $const = get_defined_constants(true);
    p($const['user']);
}
?>