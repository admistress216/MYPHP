<?php
final class Application {
    public static function run() {
        self::_init(); //初始化
        self::_set_url(); //设置外部路径
    }

    private static function _init() {
        //加载系统配置项
        C(include CONFIG_PATH.'/config.php');

        /****加载用户配置项****/
        $userPath = APP_CONFIG_PATH.'/config.php';

        //初始化用户配置项提示
        $userConfig = <<<str
<?php
return array(
    //配置项 => 配置值
);
?>
str;
        is_file($userPath) || file_put_contents($userPath, $userConfig);
        C(include $userPath);
        /****加载用户配置项****/

        //设置默认时区
        date_default_timezone_set(C('DEFAULT_TIME_ZONE'));

        //是否开启session
        C('SESSION_AUTO_START') && session_start();
    }

    private static function _set_url() {
        $path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
        $path = str_replace('\\', '/', $path);
        define('__APP__', $path);
        define('__ROOT__', dirname(__APP__));
        define('__TPL__', __ROOT__.'/'.APP_NAME.'/Tpl');
        define('__PUBLIC__', __TPL__.'/Public');
    }
}