<?php 
class Action_GoodsTransferDel extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$transferId = $arrInput['transferId'];
		if (empty($transferId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsTransferDao = new Dao_GoodsTransfer();
		$ret = $goodsTransferDao->delete($transferId);
		if ($ret) {
			return Tool_Util::returnJson();
		} else {
			return Tool_Util::returnJson('', 1, '删除失败');
		}
	}
}
