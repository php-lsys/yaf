<?php
namespace LSYS\Yaf\View;
use LSYS\Yaf\Utils;
/**
 * 基本视图基类
 */
class Simple extends \Yaf\View\Simple{
	/**
	 * 页面资源对象
	 * @return Utils
	 */
	public function utils(){
	    return \LSYS\Yaf\DI::get()->yafUtils();
	}
	/**
	 * 局部块
	 * @param string $widget
	 * @param array $data
	 * @return string
	 */
	public function widget($widget,$data=[]){
		assert(is_subclass_of($widget, Widget::class));
		$obj=(new \ReflectionClass($widget));
		return $obj->newInstance($this)->render($data);
	}
}