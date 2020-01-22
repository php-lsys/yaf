<?php
namespace LSYS\Yaf;
use LSYS\Yaf\ObjectRender\DataResult;
use LSYS\Yaf\ObjectRender\Output;
abstract class Controller extends \Yaf\Controller_Abstract{
    /**
     * 禁用默认渲染视图
     * {@inheritDoc}
     * @see \Yaf\Controller_Abstract::init()
     */
    public function init(){
        \Yaf\Application::app()->getDispatcher()->disableView();
    }
	/**
	 * 自适应输出数据
	 * @param mixed $data
	 */
	protected function displayData($data=null,$format=null){
	    \Yaf\Application::app()->getDispatcher()->disableView();
		if (!$data instanceof DataResult) $data=DataResult::factory()->data($data);
		(new Output(\LSYS\ObjectRender\DI::get()->objectRender()->setObject($data), $this->_response))->render($format);
		return false;
	}
	/**
	 * 自适应输出消息
	 * @param string $msg
	 * @param boolean $status
	 */
	protected function displayMsg($msg,$format=null){
	    $this->displayData(DataResult::factory()->msg($msg),$format);
	    return false;
	}
	/**
	 * retrieve view engine
	 * @return \LSYS\Yaf\View\Simple
	 */
	public function getView(){
		return parent::getView();
	}
}
