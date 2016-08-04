<?php 
class Action_PackageCardAdd extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['post'];
		//var_dump($arrInput);
		//参数解析
		$userId = $arrInput['user_id'];
		$saleman = $arrInput['saleman'];
		$remark = $arrInput['remark'];
		$settlement = $arrInput['settlement'];
		$packagesStr = $arrInput['info'];
		$packages = json_decode($packagesStr, true);
		$packageInfoDao = new Dao_PackageInfo();
		$packageInfoDao->startTransaction();
		//支付
		$packageDao = new Dao_Package();
		$packageCardDao = new Dao_PackageCard();
		$packageCardInfoDao = new Dao_PackageCardInfo();
		$allCost = 0.0;
		foreach ($packages as $package){
			$pack = array();
			//$pack['package_code'] = $package['package_code'];
			$pack['package_code'] = $package['package_code'];
			//$pack['package_name'] = $package['package_name'];
			$packageCode = $package['package_code'];
			$packageNameDb = $packageDao->getPackageByCode($packageCode);
			if (empty($packageNameDb)){
				return Tool_Util::returnJson('', 1);
			}
			$packageName = $packageNameDb[0]['package_name'];
			$pack['package_name'] = $packageName;
			//$pack['package_type'] = $package['package_type'];
			//$pack['package_price'] = $package['package_price'];
			//$pack['package_expiration'] = $package['package_expiration'];
			$packageExpiration = $packageNameDb[0]['package_expiration'];
			$pack['deadline'] = date('y-m-d',time() + ($packageExpiration*24*3600));
			$pack['active_date'] = date('y-m-d H:i:s',time());
			$pack['user_id'] = $userId;
			$pack['saleman'] = $saleman;
			$pack['settlement'] = $settlement;
			$pack['remark'] = $remark.$packageExpiration."test";
			$pack['package_discount'] = $package['package_discount'];
			$pack['package_cost'] = $package['package_cost'];
			$allCost += $package['package_cost'];
			$cardId = $packageCardDao->addPackageCard($pack);
			if (!$cardId){
				$packageInfoDao->rollback();
				return Tool_Util::returnJson('', 2);
			}
			$packageInfos = $packageInfoDao->getPackageInfo($packageCode);
			if (empty($packageInfos)){
				return Tool_Util::returnJson('', 4);
			}
			foreach($packageInfos as $packageInfo){
				$packageCodeCardInfo = array();
				$packageCodeCardInfo['package_card_id'] = $cardId;
				$packageCodeCardInfo['item_id'] = $packageInfo['item_id'];
				$packageCodeCardInfo['item_name'] = $packageInfo['item_name'];
				$packageCodeCardInfo['item_counts'] = $packageInfo['item_counts'];
				$packageCodeCardInfo['item_left_counts'] = $packageInfo['item_counts'];
				$packageCodeCardInfo['item_type'] = $packageInfo['item_type'];
				$ret = $packageCardInfoDao->addPackageCardInfo($packageCodeCardInfo);
				if (!$ret){
					$packageInfoDao->rollback();
					return Tool_Util::returnJson('', 3);
				}
			}
		}
		if ($settlement == '会员卡支付'){
			$ret = self::handleMemCardInfo($userId, $allCost);
		}
		if ($ret < 0){
			//todo:如果插入详情不成功,需要删除刚插入的信息
			$packageInfoDao->rollback();
			return Tool_Util::returnJson('扣款失败', 1);
		}
		else {
			$packageInfoDao->commit();
			$tempId = 'HcwsV1zkXB_HTuHVmoI2odseV3_9ofhcRlGLDocs1V8';
			self::sendSettlementMsg($userId, $tempId, $settlement, $allCost);
			return Tool_Util::returnJson('', 0);
		}
	}
	//扣除会员卡
	public static function handleMemCardInfo($userId, $settlementAmount){
	
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		if (empty($userInfo)){
			return -1;
			//return Tool_Util::returnJson('', 2, "无法获取到用户信息");
		}
		$memberCardBalance = $userInfo['member_card_balance'];
		if ($memberCardBalance < $settlementAmount){
			return -2;
			//return Tool_Util::returnJson('', 2, "会员卡余额不足,请充值");
		}
		$memberCardBalance = $memberCardBalance - $settlementAmount;
		$fileds = array('member_card_balance' => $memberCardBalance);
		$ret = $userInfoDao->updateUserInfo($fileds, $userId);
		return 0;
	}
	//结算消息发送
	public static function sendSettlementMsg($userId, $tempId, $settlement, $settlementAmount){
		$userInfoDao = new Dao_UserInfo();
		$userInfo = $userInfoDao->getUserInfoByUserId($userId);
		if (empty($userInfo)){
			return -1;
		}
		$openId = $userInfo['wechat_num'];
		if (empty($openId)){
			return -2;
		}

		$template = array(
				'touser' => $openId,
				'template_id' => $tempId,
				'url' => '',
				'topcolor' => '#7B68EE',
				'data' => array(
					'first' => array('value' => '敬爱的'.$userInfo['name'].',您购买套餐卡成功',
					'color' => '#173177',
					),
				"keyword1" => array(
					"value" => $userInfo['name'],
					"color" => "#173177"
					),
				"keyword2" => array(
					"value" => "套餐卡",
					"color" =>"#173177"
					),
				"keyword3" => array(
					"value" => date('y-m-d H:i:s',time()),
					"color" => "#173177"
					),
				"keyword4" => array(
					"value" => $settlement.":".$settlementAmount,
					"color" => "#173177"
					),
				"keyword5" => array(
					"value" => $settlementAmount,
					"color" => "#173177"
					),
				"remark" => array(
					"value" =>"谢谢您的惠顾",
					"color" => "#173177"
					)

				)
			);

		Bd_Log::warning("template:".json_encode($template));
		$result = Tool_WeiXin::sendTempMsg($template);
		Bd_Log::warning("template:".json_encode($result));
		return $result;
	}
}
