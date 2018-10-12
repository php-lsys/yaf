<?php
namespace LSYS\Yaf\View;
use LSYS\PageAssets;
use LSYS\Yaf\Utils;
/**
 * 基本视图基类
 */
class Simple extends \Yaf\View\Simple{
	/**
	 * 格式化数据
	 * @param string $datahandler
	 * @param mixed $data
	 * @param string $format
	 * @return string
	 */
	public function format($datahandler,$data,$format=null){
	    return \LSYS\FormatData\DI::get()->format_data()->format($datahandler, $data,$format);
	}
	/**
	 * 页面资源对象
	 * @return Utils
	 */
	public function utils(){
	    return \LSYS\Yaf\DI::get()->yaf_utils();
	}
	/**
	 * 页面资源对象
	 * @return PageAssets
	 */
	public function assets(){
		return \LSYS\PageAssets\DI::get()->page_assets();
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