<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileUserRequirementQuery extends Ap_Action_Abstract {
	public function execute() {
		Tool_Util::displayTpl($data, 'mobile/page/userrequirement/query.tpl');
	}
}
