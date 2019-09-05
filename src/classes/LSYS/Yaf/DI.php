<?php
namespace LSYS\Yaf;
/**
 * @method \LSYS\Yaf\View\Simple yafView()
 * @method \LSYS\Yaf\Utils yafUtils()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->yafView)&&$di->yafView(new \LSYS\DI\SingletonCallback(function (){
            return new \LSYS\Yaf\View\Simple(NULL);
        }));
        !isset($di->yafUtils)&&$di->yafUtils(new \LSYS\DI\SingletonCallback(function(){
            return new \LSYS\Yaf\Utils();
        }));
        return $di;
    }
}