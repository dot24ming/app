<?php 
class Action_GoodsStorageDel extends Saf_Api_Base_Action {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('' , 1 , '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];
		$storageId = $arrInput['storageId'];
		if (empty($storageId)) {
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		$goodsStorageDao = new Dao_GoodsStorage();
		$ret = $goodsStorageDao->delete($storageId);
		if ($ret) {
			return Tool_Util::returnJson();
		} else {
			return Tool_Util::returnJson('', 1, '删除失败');
		}
	}
}
