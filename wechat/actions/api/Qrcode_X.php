<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Qrcode_X extends Ap_Action_Abstract {

	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$type = $arrInput['type'];
		$userId = $arrInput['user_id'];
		if (empty($userId)){
			$userId = -1;
		}

		return Tool_Util::returnJson(
			Tool_WeiXin::genLimitQrUrl_X($type . "_" . Tool_Const::$storeId . "_" . $userId)
		);

	}

}
