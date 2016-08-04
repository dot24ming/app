<?php
/**
 * @name Bootstrap
 * @desc 所有在Bootstrap类中, 以_init开头的方法, 都会被Ap调用,
 * 这些方法, 都接受一个参数:Ap_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 * @author 吴伟佳
 */
class Bootstrap extends Ap_Bootstrap_Abstract{
	public function _initRoute(Ap_Dispatcher $dispatcher) {
		//在这里注册自己的路由协议,默认使用static路由
	}
	
	public function _initPlugin(Ap_Dispatcher $dispatcher) {
        //注册saf插件
        $objPlugin = new Saf_ApUserPlugin();
        $dispatcher->registerPlugin($objPlugin);

		// 自己的跳转 
        $objPlugin = new Saf_AdminUserPlugin();
       	$dispatcher->registerPlugin($objPlugin);
    }
	
	public function _initView(Ap_Dispatcher $dispatcher){
		//在这里注册自己的view控制器，例如smarty,firekylin
		$dispatcher->disableView();//禁止ap自动渲染模板
	}
	
    public function _initDefaultName(Ap_Dispatcher $dispatcher) {
		//设置路由默认信息
		//$dispatcher->setDefaultModule('Index');
		$dispatcher->setDefaultController('Main');
	}


	//加载第三方库,PHPExcel:开始
	const EXT_CLASS_PATH='phpexcel';
	const EXT_CLASS_PREFIX='PHPExcel';
	const LOCAL_CLASS_FILE_EXT='.php';
	/**
	* * @breif overwrite _initSystem
	* * @param $dispatcher:object
	* *
	* * @return NULL :
	* */
	public function _initSystem(Ap_Dispatcher $dispatcher) {
		//!启用spl自动加载器
		ini_set('ap.use_spl_autoload', true);
		spl_autoload_register(array(__CLASS__, 'localAutoLoad'));
	}
	/**
	* @breif :加载PHPExcel
	*
	* @param $className : class
	* @return bool: true/false
	*/
	public static function localAutoLoad($className) {
		Bd_Log::Notice("class: $className");
		Bd_Log::Notice("prefix: " . self::EXT_CLASS_PREFIX);
		if (strpos($className, self::EXT_CLASS_PREFIX) === 0) {
			$realName = substr($className, self::EXT_CLASS_PREFIX);
			$filePath = LIB_PATH . DIRECTORY_SEPARATOR .
			self::EXT_CLASS_PATH . DIRECTORY_SEPARATOR .
			str_replace('_', DIRECTORY_SEPARATOR, $realName) . self::LOCAL_CLASS_FILE_EXT;
			return Ap_Loader::import($filePath);
		}
		//Bd_Log::Notice("Not find class $className");
		return false;
	}
	//!加载第三方库，PHPExcel:结束
}
