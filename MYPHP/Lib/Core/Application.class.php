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

        define('CONTROLLER', $c);
        define('ACTION', $a);

        $c .= 'Controller';
        if(class_exists($c)) {
            $obj = new $c();
            $obj->$a();
        } else {
            $obj = new EmptyController();
            $obj->index();
        }

    }

    private static function _autoload($className) {
        switch(true) {
            //判断是否是控制器
            case strlen($className)>10 && substr($className,-10) == 'Controller':
                $path = APP_CONTROLLER_PATH.'/'.$className.'.class.php';
                if(!is_file($path)){
                    $emptyPath = APP_CONTROLLER_PATH.'/EmptyController.class.php';
                    if(is_file($emptyPath)){
                        include $emptyPath;
                        return;
                    } else {
                        halt($path.'控制器未找到');
                    }
                }
                include $path;
                break;
            default:
                $path = TOOL_PATH.'/'.$className.'.class.php';

                if(!is_file($path)) halt($path.'类未找到');
                include $path;
                break;
        }
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

        //加载公共配置项
        $commonPath = COMMON_CONFIG_PATH.'/config.php';

        $commonConfig = <<<str
<?php
return array(
    //配置项 => 配置值
);
?>
str;
        is_file($commonPath) || file_put_contents($commonPath, $commonConfig);
        C(include $commonPath);

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