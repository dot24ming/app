<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_OutstockDemand extends Ap_Action_Abstract {

	public function execute() {

		$data = array();

		$tpl = Bd_TplFactory::getInstance();
		$tpl->assign('data',$data);
		Tool_Util::displayTpl($data, 'admin/page/outstockdemand.tpl');

		exit;
	}

}
