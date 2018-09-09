<?php
namespace LSYS\Yaf;
/**
 * @method \LSYS\Yaf\View\Simple yaf_view()
 * @method \LSYS\Yaf\Utils yaf_utils()
 */
class DI extends \LSYS\DI{
    /**
     * @return static
     */
    public static function get(){
        $di=parent::get();
        !isset($di->yaf_view)&&$di->yaf_view(new \LSYS\DI\SingletonCallback(function (){
            return new \LSYS\Yaf\View\Simple(NULL);
        }));
        !isset($di->yaf_utils)&&$di->yaf_utils(new \LSYS\DI\SingletonCallback(function(){
            return new \LSYS\Yaf\Utils();
        }));
        return $di;
    }
}