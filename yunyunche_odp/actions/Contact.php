<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Contact extends Ap_Action_Abstract {

	public function execute() {
		Tool_Util::displayTpl(array(), 'admin/page/contact.tpl');
	}
}

