<?php
namespace LSYS\Yaf\Plugin;
use LSYS\Yaf\View\Simple;
class AssetsRender extends \Yaf\Plugin_Abstract {
	protected $_simple;
	public function __construct(Simple $simple){
		$this->_simple=$simple;
	}
	public function dispatchLoopShutdown(\Yaf\Request_Abstract $request, \Yaf\Response_Abstract $response) {
		$merge=$this->_simple->assets()->getMerge();
		$merge&&$response->setBody($merge->render($response->getBody()));
	}
}
