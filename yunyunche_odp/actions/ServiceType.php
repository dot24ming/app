<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_ServiceType extends Ap_Action_Abstract {

	public function execute() {
		$data = array();
		Tool_Util::displayTpl($data, 'admin/page/servicetype.tpl');
	}

}
