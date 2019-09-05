<?php
/**
 * 默认出错渲染类
 */
namespace LSYS\Yaf\ObjectRender;
use LSYS\ObjectRender;
use Yaf\Response_Abstract;
class Output{
    public static $format;
    protected $_object_render;
    protected $_response;
    public function __construct(ObjectRender $object_render,Response_Abstract $response=null){
        $this->_object_render=$object_render;
        $this->_response=$response;
    }
    public function render($format=null){
        if (self::$format)$this->_object_render->setFormat(self::$format);
        if ($format)$this->_object_render->setFormat($format);
        if ($this->_response){
            if (method_exists($this->_response, 'setHeader')){
                foreach ($this->_object_render->getHeader() as $name=>$value){
                    $this->_response->setHeader($name, $value);
                }
                $this->_object_render->getHttpCode()&&http_response_code($this->_object_render->getHttpCode());
            }
            $this->_response->appendBody($this->_object_render->render());
        }else{
            print $this->_object_render;
            flush();
        }
    }
}
