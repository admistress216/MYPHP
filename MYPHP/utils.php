<?php
/**
 * 核心类
 */
final class Utils {
    public static function run() {
        self::_set_const(); //设置常量
        self::_create_dir(); //创建文件夹
        self::_import_file(); //载入必须文件
        Application::run(); //执行应用类
    }
    private static function _set_const() {
        $path = str_replace('\\','/', __FILE__);
        define('MYPHP_PATH', dirname($path));
        define('CONFIG_PATH', MYPHP_PATH.'/Config');
        define('DATA_PATH', MYPHP_PATH.'/Data');
        define('LIB_PATH', MYPHP_PATH.'/Lib');
        define('CORE_PATH', LIB_PATH.'/Core');
        define('FUNCTION_PATH', LIB_PATH.'/Function');
        //定义根目录
        define('ROOT_PATH', dirname(MYPHP_PATH));
        //应用目录
        define('APP_PATH', ROOT_PATH.'/'.APP_NAME);
        define('APP_CONFIG_PATH', APP_PATH.'/Config');
        define('APP_CONTROLLER_PATH', APP_PATH.'/Controller');
        define('APP_TPL_PATH', APP_PATH.'/Tpl');
        define('APP_PUBLIC_PATH', APP_TPL_PATH.'/Public');

    }

    /**
     *创建应用目录
     *
     * @return [type] [description]
     */
    private static function _create_dir() {
        $arr = array(
            APP_PATH,
            APP_CONFIG_PATH,
            APP_CONTROLLER_PATH,
            APP_TPL_PATH,
            APP_PUBLIC_PATH
        );
        foreach($arr as $v) {
            is_dir($v) || mkdir($v,0777,true);
        }

        is_file(APP_TPL_PATH.'/success.html') || copy(DATA_PATH.'/Tpl/success.html', APP_TPL_PATH.'/success.html');
        is_file(APP_TPL_PATH.'/error.html') || copy(DATA_PATH.'/Tpl/error.html', APP_TPL_PATH.'/error.html');
    }

    private static function _import_file() {
        $fileArr = array(
            FUNCTION_PATH.'/function.php',
            CORE_PATH.'/Application.class.php',
            CORE_PATH.'/Controller.class.php'
        );
        foreach($fileArr as $v) {
            require_once $v;
        }
    }
}

Utils::run();