<?php
final class Application {
    public static function run() {
        self::_init(); //初始化
        self::_set_url(); //设置外部路径
        spl_autoload_register(array(__CLASS__,'_autoload')); //自动加载类
        self::_create_demo(); //创建demo
        self::_app_run(); //运行代码
    }

    private static function _app_run() {
        $c = isset($_GET[C('VAR_CONTROLLER')]) ? $_GET[C('VAR_CONTROLLER')] : 'Index';
        $a = isset($_GET[C('VAR_ACTION')]) ? $_GET[C('VAR_ACTION')] : 'index';

        $c .= 'Controller';

        $obj = new $c();
        $obj->$a();
    }

    private static function _autoload($className) {
        require APP_CONTROLLER_PATH.'/'.$className.'.class.php';
    }

    private static function _create_demo() {
        $path = APP_CONTROLLER_PATH.'/IndexController.class.php';

        $str = <<<str
<?php
class IndexController extends Controller{
    public function index(){
        echo 'OK';
    }
}
?>
str;
        is_file($path) || file_put_contents($path,$str);
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
?>