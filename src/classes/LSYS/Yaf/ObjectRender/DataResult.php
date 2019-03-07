<?php
/**
 * 默认出错渲染类
 */
namespace LSYS\Yaf\ObjectRender;
class DataResult{
	public static function factory($status=true){
	    return (new static())->status(!!$status);
	}
	protected $_data;
	protected $_page;
	protected $_msg;
	protected $_status;
	protected $_code;
	protected $_attrs=[];
	public function msg($msg){
		$this->_msg=$msg;
		return $this;
	}
	public function code($code){
		$this->_code=$code;
		return $this;
	}
	public function data($data){
	    $this->_data=$data;
	    return $this;
	}
	public function page(\LSYS\Pagination $page){
	    $this->_page=$page;
	    return $this;
	}
	public function status($status){
		$this->_status=$status;
		return $this;
	}
	public function attr(array $attrs){
		$this->_attrs=$attrs;
		return $this;
	}
	protected function _dataArray(){
		$body=$this->_data;
		if($body==null)return $body;
		if (is_resource($body)){
			$body=stream_get_contents($body);
		}
		if($body instanceof \JsonSerializable){
			$body=$body->jsonSerialize();
		}
		if (is_object($body)&&method_exists($body, 'as_array')){
			$body=$body->asArray();
		}
		if (is_object($body)&&method_exists($body, 'asArray')){
			$body=$body->asArray();
		}
		if ($body instanceof \Traversable){
			$_body=[];
			foreach ($body as $v){
				if (is_object($v)||is_resource($v)){
					if (is_resource($v)){
						$v=stream_get_contents($v);
					}
					if (is_object($v)&&method_exists($v, '__tostring')){
						$v=strval($v);
					}
					if (!is_scalar($v)){
						ob_start();
						print_r($v);
						$v=ob_get_clean();
					}
					$_body[]=strval($v);
				}else{
					$_body[]=$v;
				}
			}
			$body=$_body;
		}
		if (is_object($body)&&method_exists($body, '__tostring')){
			$body=strval($body);
		}
		if (is_object($body)){
			ob_start();
			print_r($body);
			$body=ob_get_clean();
		}
		return $body;
	}
	public function asArray(){
		$out=$this->_attrs;
		$body=$this->_dataArray();
		if($body!==null){
			$out['data']=$body;
		}
		if($this->_msg!==null){
			$out['message']=$this->_msg;
		}
		if($this->_page!==null){
	        $out['page']=$this->_page->asArray();
		}
		if($this->_code!==null){
			$out['code']=$this->_code;
		}
		$out['status']=$this->_status;
		return $out;
	}
	public function __toString(){
		$body=$this->_data;
		if (is_resource($body)){
			$body=stream_get_contents($body);
		}
		if (is_object($body)&&method_exists($body, '__tostring')){
			$body=strval($body);
		}
		if (!is_scalar($body)){
			ob_start();
			print_r($body);
			$body=ob_get_clean();
		}
		$body=strval($body);
		if (empty($body)&&$this->_msg){
		    $body="[".($this->_status?'Success':'Fail')."] ".$this->_msg;
		}
		return $body;
	}
}
