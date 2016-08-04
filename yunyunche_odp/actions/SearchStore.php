<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_SearchStore extends Ap_Action_Abstract {

	public function execute() {
       
	    //3. call PageService
		$arrRequest = Saf_SmartMain::getCgi(); 
		$arrInput = $arrRequest['post']; 
		if(!isset($arrInput["start_idx"])){
			$arrInput['start_idx'] = 0;
		}
		else{
			if ($arrInput['start_idx']<0){
				return Tool_Util::returnJson(NULL,101,'params error');
			}
		}
		if(!isset($arrInput["end_idx"])){
			$arrInput['end_idx'] = 10;
		}
		else{
			if ($arrInput['start_idx']>100){
				return Tool_Util::returnJson(NULL,101,'params error');
			}
		}

		$objServicePageLogin = new Service_Page_SelStore();
		$arrPageInfo = $objServicePageLogin->execute($arrInput);

		//4. chage data to out format
		$data = $arrPageInfo["data"];

		Tool_Util::displayTpl($data, 'admin/page/searchstore.tpl');
		exit;
	}

}
