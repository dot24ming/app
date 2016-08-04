<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_MobileUserRequirementAdd extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$userId = intval($arrInput['user_id']);
		$plateNumber = $arrInput['plate_number'];
		$data['user_id'] = $userId;
		$data['plate_number'] = $plateNumber;

		Tool_Util::displayTpl($data, 'mobile/page/userrequirement/add.tpl');
	}
}
