<?php
/**
 * 默认出错渲染类
 */
namespace LSYS\Yaf\ObjectRender\RenderSupport;
use LSYS\ObjectRender\Render;
use LSYS\ObjectRender;
use LSYS\ObjectRender\RenderSupport;
class DataResult implements Render,RenderSupport{
	public function support_class(){
	    return [\LSYS\Yaf\ObjectRender\DataResult::class];
	}
	public function format($format,$body){
	    assert($body instanceof \LSYS\Yaf\ObjectRender\DataResult);
		switch ($format){
			case ObjectRender::FORMAT_JSON:
			case ObjectRender::FORMAT_JSONP:
			case ObjectRender::FORMAT_XML:
				$body=$body->as_array();
				switch ($format){
					case ObjectRender::FORMAT_JSONP:
					    return ObjectRender::enjsonp($body);
						break;
					case ObjectRender::FORMAT_JSON:
					    return ObjectRender::enjson($body);
						break;
					case ObjectRender::FORMAT_XML:
					    return ObjectRender::enxml($body);
						break;
				}
			break;
			case ObjectRender::FORMAT_HTML:
			case ObjectRender::FORMAT_TEXT:
				return (string)$body;
			break;
			default: return NULL;
		}
	}
}