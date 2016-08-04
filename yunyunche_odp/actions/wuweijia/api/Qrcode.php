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

		return Tool_Util::returnStr(
			Tool_WeiXin::genLimitQrUrl($type . "_" . Tool_Const::$storeId)
		);

	}

}
