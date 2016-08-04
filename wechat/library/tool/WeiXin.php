<?php
/**
 * @name Wechat_Util
 * @desc APP公共工具类
 * @author 
 */
class Tool_WeiXin {
	const QRCODE_URL = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=%s';
	const IMAGE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=%s';
	
	const DEFAULT_TEAM = 103;
	const EMPLOYEE_TEAM = 111;
	const CUSTOMER_TEAM = 110;
	const TEST_TEAM = 100;

	static function getOpenid() {
		if (Tool_Const::$openid) {
			return Tool_Const::$openid;
		}

		$openid = $_COOKIE['yunyunche_openid'];

		if ($openid) {
			Tool_Const::$openid = $openid;
			return $openid;
		}

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		
        $code = $arrInput['code'];
		if (!$code) {
			$refer = $_SERVER['HTTP_REFERER'];
			preg_match('/code=(.*)&/', $refer, $matches);
			$code = $matches[1];
		}

		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
		$data = 
            array(
				'appid' => Tool_Const::APPID, 
				'secret' => Tool_Const::SECRET, 
				'code' => $code, 
				'grant_type' => 'authorization_code'
			);
		$data = http_build_query($data);
		$cmd = "curl '$url?$data' -XPOST";
		$result = exec($cmd);
        $result = json_decode($result, true);
		$openid = $result['openid'];
		setcookie('yunyunche_openid', $openid, 0, "/", "www.yunyunche.cn");

        return $openid;			
	}	

	public static function getToken(&$openId) {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
			'appid' => Tool_Const::APPID, 
			'secret' => Tool_Const::SECRET, 
			'grant_type' => 'client_credential'
		);
		
		$response = Tool_Util::postForm($url, $data);
		return $response['access_token'];
	}
	

	public static function genQrUrl($str)
    {
        $token = self::getToken();
        $url = sprintf(self::QRCODE_URL, $token);
        $data = array(
            "action_name" => "QR_SCENE",
            "action_info" => array("scene" => array("scene_str" => $str)),
		);
		$data = json_encode($data);

		$cmd = "curl $url -d '$data'";
		$response = json_decode(exec($cmd), true);

        if (!is_array($response) || !isset($response['ticket'])) {
            return false;
        }
        return sprintf(self::IMAGE_URL, $response['ticket']);
	}

	public static function genLimitQrUrl($str) {
        $token = self::getToken();
        $url = sprintf(self::QRCODE_URL, $token);
        $data = array(
           "action_name" => "QR_LIMIT_STR_SCENE",
            "action_info" => array("scene" => array("scene_str" => $str)),
		);
		$data = json_encode($data);

		$cmd = "curl $url -d '$data'";
		$response = json_decode(exec($cmd), true);

        if (!is_array($response) || !isset($response['ticket'])) {
            return false;
        }
        return sprintf(self::IMAGE_URL, $response['ticket']);
	}

	public static function sendPicMessage($openId, $url, $title, $desc, $picUrl = ''){
		$oathUrl = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect', Tool_Const::APPID, $url);
		$data = array(
			"touser" => $openId,
    		"msgtype" => "news",
    		"news"=> array(
        		"articles" => array(
         			array(
             			"title" => urlencode($title),
             			"description" => urlencode($desc),
             			"url" => $oathUrl,
             			"picurl" => $picUrl
         			),
         		)
    		)
		);
		$data = urldecode(json_encode($data));
		$token = self::getToken();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
		$cmd = "curl $url -d '$data'";
		$response = exec($cmd);
		Bd_Log::warning(json_encode($response));
		return $response;
	}

	public static function moveToTeam($openId, $teamId) {
		$token = self::getToken();
		$data = array(
			'openid' => $openId,
			'to_groupid' => $teamId,
		);
		$data = json_encode($data);
		$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token;
		$cmd = "curl $url -d '$data'";
		$response = exec($cmd);
		return $response;
	}

	static function getOpenid_X() {
		if (Tool_Const::$openid_x) {
			return Tool_Const::$openid_x;
		}

		$openid = $_COOKIE['yunyunche_openid_x'];

		if ($openid) {
			Tool_Const::$openid_x = $openid;
			return $openid;
		}

		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		
        $code = $arrInput['code'];
		if (!$code) {
			$refer = $_SERVER['HTTP_REFERER'];
			preg_match('/code=(.*)&/', $refer, $matches);
			$code = $matches[1];
		}

		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
		$data = 
            array(
				'appid' => Tool_Const::APPID_X, 
				'secret' => Tool_Const::SECRET_X, 
				'code' => $code, 
				'grant_type' => 'authorization_code'
			);
		$data = http_build_query($data);
		$cmd = "curl '$url?$data' -XPOST";
		$result = exec($cmd);
        $result = json_decode($result, true);
		$openid = $result['openid'];
		setcookie('yunyunche_openid_x', $openid, 0, "/", "www.yunyunche.cn");

        return $openid;			
	}	

	public static function getToken_X(&$openId) {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
			'appid' => Tool_Const::APPID_X, 
			'secret' => Tool_Const::SECRET_X, 
			'grant_type' => 'client_credential'
		);
		
		$response = Tool_Util::postForm($url, $data);
		return $response['access_token'];
	}
	

	public static function genQrUrl_X($str)
    {
        $token = self::getToken_X();
        $url = sprintf(self::QRCODE_URL, $token);
        $data = array(
            "action_name" => "QR_SCENE",
            "action_info" => array("scene" => array("scene_str" => $str)),
		);
		$data = json_encode($data);

		$cmd = "curl $url -d '$data'";
		$response = json_decode(exec($cmd), true);

        if (!is_array($response) || !isset($response['ticket'])) {
            return false;
        }
        return sprintf(self::IMAGE_URL, $response['ticket']);
	}

	public static function genLimitQrUrl_X($str) {
        $token = self::getToken_X();
        $url = sprintf(self::QRCODE_URL, $token);
        $data = array(
           "action_name" => "QR_LIMIT_STR_SCENE",
            "action_info" => array("scene" => array("scene_str" => $str)),
		);
		$data = json_encode($data);

		$cmd = "curl $url -d '$data'";
		$response = json_decode(exec($cmd), true);

        if (!is_array($response) || !isset($response['ticket'])) {
            return false;
        }
        return sprintf(self::IMAGE_URL, $response['ticket']);
	}

	public static function sendPicMessage_X($openId, $url, $title, $desc, $picUrl = ''){
		$oathUrl = sprintf('https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect', Tool_Const::APPID_X, $url);
		$data = array(
			"touser" => $openId,
    		"msgtype" => "news",
    		"news"=> array(
        		"articles" => array(
         			array(
             			"title" => urlencode($title),
             			"description" => urlencode($desc),
             			"url" => $oathUrl,
             			"picurl" => $picUrl
         			),
         		)
    		)
		);
		$data = urldecode(json_encode($data));
		$token = self::getToken_X();
		$url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".$token;
		$cmd = "curl $url -d '$data'";
		$response = exec($cmd);
		Bd_Log::warning(json_encode($response));
		return $response;
	}

	public static function moveToTeam_X($openId, $teamId) {
		$token = self::getToken_X();
		$data = array(
			'openid' => $openId,
			'to_groupid' => $teamId,
		);
		$data = json_encode($data);
		$url = "https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=".$token;
		$cmd = "curl $url -d '$data'";
		$response = exec($cmd);
		return $response;
	}
}
