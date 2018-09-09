<?php
namespace LSYS\Yaf;
use LSYS\Web\Request;

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
    public function url_link(){
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
        return $this->url_base().ltrim($route->assemble($info,$query),'/');
    }
    /**
     * 基本路径
     * @return string
     */
    public function url_base(){
        $baseuri=\Yaf\Application::app()->getConfig()->get("application")->get("baseUri");
        $baseuri= rtrim($baseuri,'/').'/';
        return $baseuri;
    }
    /**
     * 得到完整站点地址
     * @param string $domain
     * @param string $ssl
     * @return string
     */
    public function site($domain=null,$ssl=null,$port=true){
        return Request::site($domain,$ssl,$port);
    }
    /**
     * 生成文件路径
     * @param string $key
     * @param string $item
     * @return string
     */
    public function url_file($key,$item){
        return \LSYS\FileGet\DI::get()->fileget($key)->url($item);
    }
    /**
     * 生成图片路径
     * @param string $key
     * @param string $item
     * @param string $resize
     * @return string
     */
    public function url_image($key,$item,$resize=NULL){
        $image=\LSYS\FileImageGet\DI::get()->fileimageget($key);
        if (!$image)return false;
        if ($resize===null)return $image->url($item);
        else return $image->resize_url($item, $resize);
    }
	/**
     * 生成图片所有可用路径
     * @param string $key
     * @param string $item
     * @return string[]
     */
    public function url_images($key,$item){
        $image=\LSYS\FileImageGet\DI::get()->fileimageget($key);
        if (!$image)return false;
		return $image->urls($item);
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
    public function is_controller($controller){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->controller==$controller;
    }
    /**
     * 判断当前是否是指定module
     * @param string $module
     * @return boolean
     */
    public function is_module($module){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->module==$module;
    }
    /**
     *  判断当前是否是指定action
     * @param string $action
     * @return boolean
     */
    public function is_action($action){
        return \Yaf\Application::app()->getDispatcher()->getRequest()->action==$action;
    }
}
