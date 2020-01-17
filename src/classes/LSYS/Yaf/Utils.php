<?php
namespace LSYS\Yaf;
class Utils{
    /**
     * 生成链接
     * 	第一个参数为数组 为默认路由 传两个参数
     *  第一个参数为字符串 为路由名称 传三个参数
     * @param string $name
     * @param array $info
     * @param array $query
     * @return string|boolean
     */
    public function urlLink(){
        $args=func_get_args();
        if (is_array($args[0])){
            $name="_default";
            $info=isset($args[0])?(array)$args[0]:[];
            $query=isset($args[1])?(array)$args[1]:[];
        }else{
            $name=strval($args[0]);
            $info=isset($args[1])?(array)$args[1]:[];
            $query=isset($args[2])?(array)$args[2]:[];
        }
        $route=\Yaf\Application::app()->getDispatcher()->getRouter()->getRoute($name);
        if ($route==null)return '';
        return $this->urlBase().ltrim($route->assemble($info,$query),'/');
    }
    /**
     * 基本路径
     * @return string
     */
    public function urlBase(){
        $baseuri=\Yaf\Application::app()->getConfig()->get("application")->get("baseUri");
        $baseuri= rtrim($baseuri,'/').'/';
        return $baseuri;
    }
    /**
     * get tpl name
     * @return string
     */
    public function tpl($tpl_name){
        $config=\Yaf\Application::app()->getConfig()->get("application");
        return $tpl_name.'.'.$config->get("view")->get("ext");
    }
    /**
     * 判断当前是否是指定controller
     * @param string $controller
     * @return boolean
     */
    public function isController($controller){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->controller==$controller;
    }
    /**
     * 判断当前是否是指定module
     * @param string $module
     * @return boolean
     */
    public function isModule($module){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->module==$module;
    }
    /**
     *  判断当前是否是指定action
     * @param string $action
     * @return boolean
     */
    public function isAction($action){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->action==$action;
    }
}
