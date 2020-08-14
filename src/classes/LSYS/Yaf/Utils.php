<?php
namespace LSYS\Yaf;
class Utils{
    /**
     * 生成链接
     * @param array $info
     * @param array $query
     * @param string $name 
     * @return string|boolean
     */
    public function urlLink(array $info,array $query,$name="_default"){
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
