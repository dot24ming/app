<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Qrcode extends Ap_Action_Abstract {

	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$type = $arrInput['type'];
		$userId = $arrInput['user_id'];
		if (empty($userId)){
			$userId = -1;
		}
		$res = '';
		if ($type == 1 ){
			$res = Tool_WeiXin::genLimitQrUrl($type . "_" . Tool_Const::$storeId . "_" . $userId);
		}
		else{
			$res = Tool_WeiXin::genLimitQrUrl_X($type . "_" . Tool_Const::$storeId . "_" . $userId);
		}
		return Tool_Util::returnJson($res);
	}

}
