<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Serviceadd extends Ap_Action_Abstract {

	public function execute() {
		
		//get project class
		//get project subclass
		//get project department
		//4. chage data to out format
		$arrOutput = "";
		
		//5. build page
		// smarty模板，以下渲染模板的代码依赖于提供一个tpl模板
		$tpl = Bd_TplFactory::getInstance();
	    $tpl->assign('arrOutput',$arrOutput['data']);
	    $tpl->display('yunyunche_odp/serviceadd.tpl');
		
		//这里直接输出,作为示例
		//$strOut = $arrOutput['data'];
        //echo $strOut;

		//notice日志信息打印，只需要添加日志信息，saf会自动打一条log
		Bd_Log::addNotice('out', $arrOutput);
		#Bd_log::debug($arrOutput);
		#var_dump($arrOutput);

	}

}
