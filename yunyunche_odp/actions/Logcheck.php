<?php
/**
 * @name Action_Logcheck
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Logcheck extends Ap_Action_Abstract {

	public function execute() {
	    $arrRequest = Saf_SmartMain::getCgi();

        $arrInput = $arrRequest['post'];

        if(!isset($arrInput['username'])){
        	return Tool_Util::returnJson('', 1, '参数错误');
        }
		if(!isset($arrInput['password'])){
			return Tool_Util::returnJson('', 1, '参数错误');
		}

		
		$objServicePageLogcheck = new Service_Page_Logcheck();
		$arrPageInfo = $objServicePageLogcheck->execute($arrInput);
		if ($arrPageInfo === true) {
			$username = Tool_Util::filter($arrInput['username']);
			Service_Data_User::setCookie($username);

			$adminInfoDao = new Dao_AdminInfo();
			$adminInfo = $adminInfoDao->getAdminByUsername($username);
			$storeId = $adminInfo['store_id'];
			$storeIdArr = explode(',', $storeId);
			$daoStoreInfo = new Dao_StoreInfo();
			$stores = $daoStoreInfo->getStoreInfo($storeIdArr);			
			$storeList = array();
			foreach ($stores as $store) {
				$storeList[] = array(
					'store_id' => $store['store_id'],
					'name' => $store['name'],
				);
			}
			return Tool_Util::returnJson(array('storeIdList' => $storeList));
			//return Tool_Util::returnJson(array('storeIdList' => $storeIdArr));
		} else {
			return Tool_Util::returnJson('', 1, '用户名或密码不正确');
		}
	}

}
