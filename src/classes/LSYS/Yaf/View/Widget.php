<?php
namespace LSYS\Yaf\View;
/**
 * Widget 基类
 */
abstract class Widget{
	protected $_view;
	public function __construct(Simple $view){
		$this->_view=$view;
	}
	/**
	 * 代理方式获取数据
	 * $callable 当货物不到时回调
	 * @param string $key
	 * @param callable $callable
	 * @return mixed
	 */
	public function proxyData($key,callable $callable){
		if (!$this->_view->__isset($key))return call_user_func($callable);
		return $this->_view->{$key};
	}
	/**
	 * 渲染指定模板
	 * @param array $data 数据
	 * @param string $tpl 模板路径
	 * @param string $module 模块名
	 * @return string
	 */
	protected function _render($data=NULL,$tpl=NULL,$module=NULL){
		if ($tpl==null){
			$tpl=get_called_class();
			if (strpos($tpl, '\\')!==false) $tpl=substr(strrchr($tpl, '\\'), 1);
			$tpl=strtolower($tpl);
		}
		if (substr($tpl, 0,1)=='/')$tpl=substr($tpl, 1);
		else $tpl="widget".DIRECTORY_SEPARATOR.$tpl;
		$old_path=$this->_view->getScriptPath();
		$config=\Yaf\Application::app()->getConfig()->get("application");
		if ($module!=null){
			$moduels=\Yaf\Application::app()->getModules();
			$moduels=array_combine(array_map('strtolower',$moduels),$moduels);
			$module=strtolower($module);
			if (isset($moduels[$module])){
				$template_dir=$config->get("directory").DIRECTORY_SEPARATOR.
					"modules".DIRECTORY_SEPARATOR.
					$moduels[$module].DIRECTORY_SEPARATOR."views";
			}
		}
		if (!isset($template_dir)){
			$template_dir=$config->get("directory").DIRECTORY_SEPARATOR."views";
		}
		$this->_view->setScriptPath($template_dir);
		$html=$this->_view->render($this->_view->utils()->tpl($tpl),$data);
		$this->_view->setScriptPath($old_path);
		return $html;
	}
	/**
	 * 执行widget并输出渲染结果
	 * @param array $data
	 * @return string
	 */
	abstract public function render($data=NULL);
}