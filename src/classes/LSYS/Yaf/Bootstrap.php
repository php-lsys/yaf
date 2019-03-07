<?php
namespace {
	$sets=array();
	$env='\LSYS\Core::'.strtoupper(\Yaf\Application::app()->environ());
	if (defined($env))$sets['environment']=constant($env);
	$config=\Yaf\Application::app()->getConfig()->get("application");
	if ($config->get("timezone"))$sets['timezone']=$config->get("timezone");
	\LSYS\Core::sets($sets);
	\LSYS\Config\File::dirs(array(
	    realpath(\Yaf\Application::app()->getAppDirectory()."/../conf"),
	));
	\LSYS\Yaf\Exception::setLevel([
	    \LSYS\HTTPException\HTTP400Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP401Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP402Exception::class=>\LSYS\Loger::NOTICE,
	    \LSYS\HTTPException\HTTP403Exception::class=>\LSYS\Loger::NOTICE,
	    \LSYS\HTTPException\HTTP404Exception::class=>\LSYS\Loger::NOTICE,
	    \LSYS\HTTPException\HTTP405Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP406Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP407Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP408Exception::class=>\LSYS\Loger::ERROR,
	    \LSYS\HTTPException\HTTP409Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP410Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP411Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP412Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP413Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP414Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP415Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP416Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP417Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP500Exception::class=>\LSYS\Loger::ERROR,
	    \LSYS\HTTPException\HTTP501Exception::class=>\LSYS\Loger::WARNING,
	    \LSYS\HTTPException\HTTP502Exception::class=>\LSYS\Loger::ERROR,
	    \LSYS\HTTPException\HTTP503Exception::class=>\LSYS\Loger::ERROR,
	    \LSYS\HTTPException\HTTP504Exception::class=>\LSYS\Loger::ERROR,
	    \LSYS\HTTPException\HTTP505Exception::class=>\LSYS\Loger::WARNING,
	]);
	//添加全局语言函数
	if (!function_exists("__")){
		function __($string, array $values = NULL, $domain = NULL)
		{
			$app=\Yaf\Application::app();
			$i18n=\LSYS\I18n\DI::get()->i18n(realpath($app->getAppDirectory()."/../I18n/"));
			return $i18n->__($string,  $values , $domain );
		}
	}
}
namespace LSYS\Yaf{
	/**
	 * @name Bootstrap
	 * @author lonely
	 */
	abstract class Bootstrap extends \Yaf\Bootstrap_Abstract{
	    /**
	     * 对象渲染DI设置
	     * @param \Yaf\Dispatcher $dispatcher
	     */
	    public function _initObjectRenderDi(\Yaf\Dispatcher $dispatcher) {
	        \LSYS\ObjectRender\DI::set(function(){
	            return (new \LSYS\PageAssets\DI())->objectRender(new \LSYS\DI\SingletonCallback(function(){
	                return (new \LSYS\ObjectRender())
    	                ->setRenderSupport(new \LSYS\Yaf\ObjectRender\RenderSupport\DataResult());
	            }));
	        });
	    }
		/**
		 * 初始化日志处理,日志可能在别处,所以放到Bootstrap类中 方便重写
		 * @param \Yaf\Dispatcher $dispatcher
		 */
		public function _initLogsHandler(\Yaf\Dispatcher $dispatcher) {
	    	$loger=\LSYS\Loger\DI::get()->loger();
	    	if (count($loger->getHandler())==0){
	    	    $dir=\Yaf\Application::app()->getAppDirectory()."/../logs";
	    		if (is_dir($dir))$dir=realpath($dir);
	    		$loger->addHandler(new \LSYS\Loger\Handler\Folder($dir,\LSYS\Loger::ERROR));
	    	}
		}
		/**
		 * 初始化视图
		 * @param \Yaf\Dispatcher $dispatcher
		 */
		public function _initView(\Yaf\Dispatcher $dispatcher) {
		    $view=DI::get()->yafView();
			$dispatcher->setView($view);
			$dispatcher->registerPlugin(new \LSYS\Yaf\Plugin\AssetsRender($view));
			return $view;
		}
	}
}
