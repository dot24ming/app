<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 */
class Action_Product extends Ap_Action_Abstract {

	public function execute() {
		Tool_Util::displayTpl(array(), 'admin/page/product.tpl');
	}
}

