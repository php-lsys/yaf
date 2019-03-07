<?php
namespace LSYS\Yaf;
use LSYS\Loger;
use LSYS\HTTPException\HTTP404Exception;
use LSYS\ObjectRender;
class Exception extends \Yaf\Exception {
	const ERROR_OUTPUT_HTML=1<<0;
	const ERROR_OUTPUT_JSON=1<<1;
	const ERROR_OUTPUT_JSONP=1<<2;
	const ERROR_OUTPUT_TEXT=1<<3;
	const ERROR_OUTPUT_XML=1<<4;
    /**
     * not write log exception
     * @var array
     */
    protected static $_exception_level=array();
    /**
     * Inline exception handler, displays the error message, source of the
     * exception, and the stack trace of the error.
     *
     * @param   \Exception  $e
     * @return  void
     */
    public static function handler($e)
    {
        $level=Loger::ERROR;
        if(is_object($e)){
            $notfound=array(\Yaf\Exception\LoadFailed\Action::class,
                \Yaf\Exception\LoadFailed\Controller::class);
            if (in_array(get_class($e), $notfound)){
                $p=$e->getPrevious();
                if($p instanceof \Error){
                    $e=new \ErrorException($p->getMessage(),$p->getCode(),false,$p->getFile(),$p->getLine(),$e);
                }else{
                    $e=new HTTP404Exception($e->getMessage(),$e,$e->getCode());
                }
            }
            foreach (self::$_exception_level as $k=>$v){
                if (is_a($e, $k)){
                    $level=$v;
                    break;
                }
            }
        }
        try{
            \LSYS\Loger\DI::get()->loger()->add($level,$e);
        }catch(\Exception $e){}
        try{
            (new \LSYS\Yaf\ObjectRender\Output(\LSYS\ObjectRender\DI::get()->objectRender()->setObject($e)))->render();
        }catch (\Exception $e){
            echo \LSYS\ObjectRender\Render\Exception::entext($e);
        }
        exit;
    }
    /**
     * 设置发生异常时,记录该异常的日志等级
     * array('class'=>loger::level)
     * @param array $exception_level
     */
    public static function setLevel(array $exception_level){
        foreach ($exception_level as $k=>$v){
            self::$_exception_level[$k]=$v;
        }
    }

    /**
     * 注册使用内部处理错误
     * @param boolean $error_output
     * @param array $skip_error
     */
    public static function setErrorHandler($error_output=Exception::ERROR_OUTPUT_HTML,$skip_error=array(
       //'mysqli::__construct' //mysql 连接错误会添加wraing 如果外部会做异常处理,这里可以跳过此错误处理
    )){
        set_exception_handler(function($e){
            self::handler($e);
        });
        $errors=[];
        set_error_handler(function ($code, $error, $file = NULL, $line = NULL)use(&$errors){
            $errors[]=func_get_args();
        });
        register_shutdown_function(function()use($error_output,$skip_error,&$errors){
            $loger=\LSYS\Loger\DI::get()->loger()->batchStart();
            if ($error = error_get_last()){
                $errors[]=array($error['type'],$error['message'],$error['file'],$error['line']);
            }
            foreach ($errors as $k=>$v){
                $find=false;
                foreach ($skip_error as $vv){
                    if (strpos($v[1], $vv)!==false){
                        unset($errors[$k]);
                        $find=true;
                        break;
                    }
                }
                if($find)continue;
                $code=array_shift($v);
                $error=array_shift($v);
                $file=array_shift($v);
                $line=array_shift($v);
                $v=new \ErrorException($error,$code,null,$file,$line);
                switch ($code){
                    case E_NOTICE:
                    case E_DEPRECATED:
                    case E_USER_DEPRECATED:
                    case E_USER_NOTICE:
                        $loger->addNotice($v);
                        break;
                    case E_CORE_WARNING:
                    case E_COMPILE_WARNING:
                    case E_USER_WARNING:
                        $loger->addWarning($v);
                        break;
                    default:
                        $loger->addError($v);
                        break;
                }
                if($code===E_WARNING) unset($errors[$k]);//rename copy等函数错误不在抛出异常
                else $errors[$k]=$v;
            }
            try{
                $loger->batchEnd();
            }catch(\Exception $e){}
            if(count($errors)==0)return ;
            $format=\LSYS\Yaf\ObjectRender\Output::$format;
            if ($format==null){
                $accepts=array(
                    ObjectRender::FORMAT_HTML=>'text/html,application/xhtml+xml',
                    ObjectRender::FORMAT_TEXT=>'text/plain',
                );
                if (!isset($_SERVER['HTTP_ACCEPT'])||empty($_SERVER['HTTP_ACCEPT'])){
                    $format=ObjectRender::FORMAT_HTML;
                }else{
	                foreach (explode(",",$_SERVER['HTTP_ACCEPT']) as $v){
	                    foreach ($accepts as $k=>$accept){
	                        foreach (explode(",",$accept) as $vv){
	                            if (strpos($v, $vv)!==false){
	                                $format=ObjectRender::FORMAT_HTML;
	                                break;
	                            }
	                        }
	                        if ($format)break;
	                    }
	                    if ($format)break;
	                }
				}
            }
            switch ($format){
                case ObjectRender::FORMAT_HTML:
					if($error_output&self::ERROR_OUTPUT_HTML){
		                echo \LSYS\ObjectRender\Render\Exception::enhtmlassets();
		                $foot=false;
		                foreach ($errors as $v){
		                    if(!$foot&&in_array($v->getCode(),[E_COMPILE_ERROR,E_CORE_ERROR]))$foot=true;
		                    try{
		                        echo \LSYS\ObjectRender\Render\Exception::enhtml($v,null,\LSYS\ObjectRender\Render\Exception::ENHTML_RENDER_FULL);
		                    }catch (\Exception $e){
		                        echo $e->getTraceAsString();
		                    }
		                }
		                if($foot)echo \LSYS\ObjectRender\Render\Exception::enhtmlenv();
					}
                    break;
                case ObjectRender::FORMAT_XML:
					if($error_output&self::ERROR_OUTPUT_XML){
						foreach ($errors as $v){
			                try{
			                    echo \LSYS\ObjectRender::enxml(\LSYS\ObjectRender\Render\Exception::entext($v));
			                }catch (\Exception $e){
			                    echo \LSYS\ObjectRender::enxml($e->getTraceAsString());
			                }
			            }
					}
				break;
                case ObjectRender::FORMAT_JSONP:
					if($error_output&self::ERROR_OUTPUT_JSONP){
						echo ";\n";
		                foreach ($errors as $v){
		                    try{
		                        echo "/*\n".\LSYS\ObjectRender\Render\Exception::entext($v)."\n*/\n";
		                    }catch (\Exception $e){
		                        echo "//".$e->getTraceAsString()."\n";
		                    }
		                }
					}
				break;
                case ObjectRender::FORMAT_JSON:
                case ObjectRender::FORMAT_TEXT:
					if($error_output&self::ERROR_OUTPUT_TEXT||$error_output&self::ERROR_OUTPUT_JSON){
		                foreach ($errors as $v){
		                    try{
		                        echo \LSYS\ObjectRender\Render\Exception::entext($v);
		                    }catch (\Exception $e){
		                        echo $e->getTraceAsString();
		                    }
		                }
					}
                    break;
            }
            $errors=[];
        });
    }
}
