<?php
/**
 * @name Action_Sample
 * @desc sample api
 * @author 
 */
class Action_Callback extends Saf_Api_Base_Action {
	public function __execute(){
		$content = file_get_contents("php://input");

		$jsonRes = (array)simplexml_load_string($content, 'SimpleXMLElement', LIBXML_NOCDATA);
		Bd_Log::warning(json_encode($jsonRes));

		$openId = $jsonRes['FromUserName'];
		$msgType = $jsonRes['MsgType'];
		$event = $jsonRes['Event'];
	
		Bd_Log::warning($event);
		switch ($event) {
			case 'subscribe':
				Bd_Log::warning('wuweijia');
				$key = $jsonRes['EventKey'];
				Bd_Log::warning($key);
				$str = preg_replace('/qrscene_/', '', $key);
				preg_match('/qrscene_([^_]*)_([^_]*)_([^_]*)/', $key, $matches);
				Bd_Log::warning($str);
				if ($matches) {
					$str = $matches[1];
					$storeId = $matches[2];
					$userId = $matches[3];
				} else {
					break;
				}
				Bd_Log::warning('wy'.$userId);
				//$str = Tool_Const::QR_TYPE_CUSTOMER;
				Bd_Log::warning('wx'.$str);

				// 添加为员工,给员工发送信息
				if ($str == Tool_Const::QR_TYPE_CUSTOMER) {
					Bd_Log::debug('request input', 0, $str);
					//Tool_WeiXin::sendPicMessage($openId,'http://www.baidu.com','ad','asd',$picUrl='');
					$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
        			$userInfo = $userInfoDao->getUserInfoByOpenId($openId);
					if (empty($userInfo)){
						Tool_WeiXin::sendPicMessage_X($openId,
						"http://www.yunyunche.cn/wx/relatemycar/storeId/".$storeId, 
						"申请成为客户", "申请成为客户", $picUrl = '');
						break;
					}

					Bd_Log::warning('xxxxxxx'.$userId);
        			$storeId = Tool_Const::$storeId;
        			$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
        			$carInfo = $carInfoDao->getInfoByOwnerId($userId);
					if (empty($carInfo)) {
						Tool_WeiXin::sendPicMessage_X($openId, 
							"http://www.yunyunche.cn/wx/relatemycar/storeId/".$storeId, 
							"申请成为客户", "申请成为客户", $picUrl = '');
					} else {

						$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
						$arr = array("wechat_num" => "");
						$ret = $userInfoDao->updateUserInfoByOpenId($arr, $openId);
						$arr = array("wechat_num" => $openId);
						$ret = $userInfoDao->updateOpenId($arr, $userId);
						if (!$ret){
							Tool_WeiXin::sendPicMessage_X($openId, 
								"http://www.yunyunche.cn/wx/mycar/storeId/".$storeId, 
								"您已是本店客户！", "查看详情", $picUrl = '');
						}

					}
				}	

				//关联员工信息
				if ($str == Tool_Const::QR_TYPE_RELE){
					$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
					$arr = array("wechat_num" => "");
					$ret = $userInfoDao->updateUserInfoByOpenId($arr, $openId);
					$arr = array("wechat_num" => $openId);
					$ret = $userInfoDao->updateOpenId($arr, $userId);
					if (!$ret){
						Tool_WeiXin::sendPicMessage_X($openId, 
							"http://www.yunyunche.cn/wx/mycar/storeId/".$storeId, 
							"您已是本店客户！", "查看详情", $picUrl = '');
					}
				}
				break;
			case 'SCAN':
				Bd_Log::warning('wuweijia');

				$eventKey = $jsonRes['EventKey'];
				Bd_Log::warning($eventKey);
				preg_match('/([^_]*)_([^_]*)_([^_]*)/', $eventKey, $matches);
				if ($matches) {
					$str = $matches[1];
					$storeId = $matches[2];
					$userId = $matches[3];
				} else {
					break;
				}

				// 添加为员工,给员工发送信息
				if ($str == Tool_Const::QR_TYPE_CUSTOMER) {
					//TODO no qr result

					Bd_Log::debug('request input', 0, $str);
					//Tool_WeiXin::sendPicMessage($openId,'http://www.baidu.com','ad','asd',$picUrl='');
					$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
        			$userInfo = $userInfoDao->getUserInfoByOpenId($openId);
					if (empty($userInfo)){
						Tool_WeiXin::sendPicMessage_X($openId,
						 "http://www.yunyunche.cn/wx/relatemycar/storeId/".$storeId, 
						 "申请成为客户", "申请成为客户", $picUrl = '');
						break;
					}

        			//$userId = $userInfo['user_id'];
        			$storeId = Tool_Const::$storeId;
        			$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);
        			$carInfo = $carInfoDao->getInfoByOwnerId($userId);
					if (empty($carInfo)) {

						Tool_WeiXin::sendPicMessage_X($openId, 
							"http://www.yunyunche.cn/wx/relatemycar/storeId/".$storeId, 
							"申请成为客户", "申请成为客户", $picUrl = '');
					} else {
						$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
						$arr = array("wechat_num" => "");
						$ret = $userInfoDao->updateUserInfoByOpenId($arr, $openId);
						$arr = array("wechat_num" => $openId);
						$ret = $userInfoDao->updateOpenId($arr, $userId);
						if (!$ret){
							Tool_WeiXin::sendPicMessage_X($openId, 
								"http://www.yunyunche.cn/wx/mycar/storeId/".$storeId, 
								"微信绑定成功", "查看详情", $picUrl = '');
							Tool_WeiXin::moveToTeam($openId, Tool_WeiXin::CUSTOMER_TEAM);
						}
					}
				}

				//关联员工信息
				if ($str == Tool_Const::QR_TYPE_RELE){
					$userInfoDao = new Dao_UserInfo(Tool_Const::$storeId);
					$arr = array("wechat_num" => "");
					$ret = $userInfoDao->updateUserInfoByOpenId($arr, $openId);
					$arr = array("wechat_num" => $openId);
					$ret = $userInfoDao->updateOpenId($arr, $userId);
					//if (!$ret){
						Tool_WeiXin::sendPicMessage_X($openId, 
							"http://www.yunyunche.cn/wx/mycar/storeId/".$storeId, 
							"微信绑定成功", "查看详情", $picUrl = '');
					//}
				}
				break;
			default:
				Bd_Log::warning('wuweijia1');
				break;
		}
	}
}

/*
 //验证token代码,需要时打开
class Action_Callback extends Ap_Action_Abstract {
	public function execute(){
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		$echoStr = $arrInput["echostr"];
		echo $echoStr;
		$signature = $arrInput["signature"];
		$timestamp = $arrInput["timestamp"];
		$nonce = $arrInput["nonce"];
	
		$token = 'dot24';
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}
}
*/
