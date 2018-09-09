<?php
/**
 * 使用示例: $this->widget(StaticTPL::class,[StaticTPL::TPL=>'publicheader'])
 */
namespace LSYS\Yaf\View\Widget;
use LSYS\Exception;
class StaticTPL extends \LSYS\Yaf\View\Widget{
	const MODULE="__StaticTPL_MODEL__";
	const TPL="__StaticTPL_TPL__";
	public function render($data=[]){
		$module=null;
		if (isset($data[self::MODULE]))$module=$data[self::MODULE];
		if (isset($data[self::TPL]))$tpl=$data[self::TPL];
		if (!isset($tpl))throw new Exception("static widget can't miss tpl var");
		return $this->_render($data,$tpl,$module);
	}
}