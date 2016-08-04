<?php
/**
 * @name Action_ServiceitemAdd
 * @desc sample action, 和url对应
 * @author yincunxiang
 */
class Action_ServiceitemAdd extends Ap_Action_Abstract {

	public function execute() {
		//1. check if user is login as needed
		//$arrUserinfo = Saf_SmartMain::getUserInfo();
		//if (empty($arrUserinfo)) {
    	//	//ouput error
    	//}
    	
	    //2. get and validate input params
		/*
	    $arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
        if(!isset($arrInput['id'])){
        	//output error
		}
		*/
        //Bd_Log::debug('request input', 0, $arrInput);
        
	    //3. call PageService
		$objServicePageServiceitemAdd = new Service_Page_ServiceitemAdd();
		$arrPageInfo = $objServicePageServiceitemAdd->execute($arrInput);
		

		//4. chage data to out format
		$arrOutput = $arrPageInfo;
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		$tpl = Bd_TplFactory::getInstance();
	    $tpl->assign('arrOutput',$arrOutput['data']);
	    $tpl->display('yunyunche_odp/serviceitemadd.tpl');
		
		//这里直接输出,作为示例
		//$strOut = $arrOutput['data'];
        //echo $strOut;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		Bd_Log::addNotice('out', $arrOutput);
		#Bd_log::debug($arrOutput);
		#var_dump($arrOutput);

	}

}
