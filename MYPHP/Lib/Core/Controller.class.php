<?php
/**
 *父类
 */
class Controller {
    public function __construct() {
        if(method_exists($this,'__init')) {
            $this->__init();
        }
    }
    protected function success($msg,$url=NULL,$time=3){
        $url = $url ? "window.location.href='".$url."'" : 'window.history.back(-1)';
        include APP_TPL_PATH.'/success.html';
    }
    protected function error($msg,$url=NULL,$time=3){
        $url = $url ? "window.location.href='".$url."'" : 'window.history.back(-1)';
        include APP_TPL_PATH.'/error.html';
    }
}
?>