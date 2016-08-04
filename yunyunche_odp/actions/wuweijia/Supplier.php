<?php
/**
 * @name Action_AddCustomer
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Supplier extends Ap_Action_Abstract {

	public function execute() {
		Tool_Util::displayTpl($data, 'admin/page/supplier.tpl');
	}

}
