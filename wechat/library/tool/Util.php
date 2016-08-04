<?php
/**
 * @name Tool_Util
 * @desc APP公共工具类
 * @author 吴伟佳
 */
class Tool_Util{
    public function getUtilMsg(){
        return 'GoodBye World!(from Yunyunche_odp_Util)';
    }

    public static function returnJson($array = '', $code = 0, $info = '') {
        header('Content-Type: application/json; charset=UTF-8');
        $res = array(
            'status' => $code,
            'info' => $info,
            'data' => $array,
		);
	  	Bd_Log::addNotice("returnJson:".json_encode($res));	
        echo json_encode($res);
    } 

    public static function keyFormat($params) {
        if (!is_array($params) || empty($params)) {
            return $params;
        }   

        foreach ($params as $key => $value) {
            $keys = explode('_', $key);
            if( !is_array($keys) || count($keys) == 0) {
                continue;
            }   

            $newKeys = array($keys[0]);
            for ($i = 1; $i < count($keys); $i ++) {
                $newKeys[] = ucwords($keys[$i]);
            }
            $key = implode('', $newKeys);
            $newParams[$key] = $value;
        }
        return $newParams;
    }
    
    // 过滤输入的字符串,防止sql注入 xss 攻击
    public static function filter($post) {
        if (!get_magic_quotes_gpc()) { // 判断magic_quotes_gpc是否为打开     
            $post = addslashes($post); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤     
        }     
        $post = nl2br($post); // 回车转换     
        $post= htmlspecialchars($post); // html标记转换        
        return $post;      
    }
	
	public static function displayTpl($data, $strTpl) {	
		$tpl = Bd_TplFactory::getInstance("wechat");
		if (empty($data) || !is_array($data)) {
			$data = array();
		}
		//$data = array_merge($authority, $data);
        $tpl->assign(array('data' => $data));
        $strRet = $tpl->fetch($strTpl);
        header('Content-Type: text/html; charset=UTF-8');
        echo $strRet;
	}

	//send sms to user
	// provider by http://www.cl2009.com/
	// 0.05 RMB per sms, pls be economy
	public static function postSMS($mobile,$data)
	{
		$sms_username="plghope";
		$sms_passwd="Ppp12345";
		$sms_url='http://222.73.117.158/msg/HttpBatchSendSM?';
		$retry = 3;
		$res = 0;  //0 success; -1 fail
		$result = array();
		//assemble post data	
		$post_data = array();
		$post_data['account'] = iconv('GB2312', 'GB2312',$sms_username);
		$post_data['pswd'] = iconv('GB2312', 'GB2312',$sms_passwd);
		$post_data['mobile'] = $mobile;
		$post_data['msg']=mb_convert_encoding("$data",'UTF-8', 'GB2312');
		$post_data['needstatus'] = "true";
		
		$o="";
		foreach ($post_data as $k=>$v)
		{
		   $o.= "$k=".urlencode($v)."&";
		}
		$post_data=substr($o,0,-1);
    
		//send sms by curl
    
		for($cnt=0; $cnt<$retry; $cnt++)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_URL,$sms_url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$curl_result = curl_exec($ch);
			
			//echo $curl_result;
			//parse result
			$tmp = explode("\n",$curl_result);
			//print_r($tmp);
			if( count($tmp) >0)
			{
				$tmp_status = $tmp[0];
				$tmp_2 =  explode(",",$tmp_status);
				//print_r($tmp_2);
				if( count($tmp_2) == 2)
				{
					$result["status"] = $tmp_2[1];
					
				}
			}	
			if( count($tmp) == 2)
			{
				$result["smsid"] = $tmp[1];
			}
			if($result["status"] == "0")
			{
				//print "send sms error, content:".$post_data."\n";
				$res = 0;
				break;
			}
			else
			{
				$res = -1;
			}	
			curl_close($ch);
		}
		return $res;
	}
	public static function getStoreTable($table) {
		return sprintf(Dao_Base::TABLE_FORMAT, Tool_Const::$storeId, $table);
		
	}
	public static function getOpenIdByCode($code){
		$appid = "wxb1fe774d7aa2542b";
		$appsecret = "92acc63145a1e76b9aa6df0af8bb5c98";
		$grant_type = "authorization_code";
		$ch = curl_init();
		$url = sprintf("https://api.weixin.qq.com/sns/oauth2/access_token?appid=%s&secret=%s&code=%s&grant_type=%s", $appid, $appsecret, $code, $grant_type);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	public static function postForm($url, $data = array(), $dataEncode = 0)
    {   
        if (!$dataEncode) {
            $formData = http_build_query($data);
        } else {
			foreach ($data as $key => &$value) {
				$value = urlencode($value);
			}
			$formData = urldecode(json_encode($data));
		}
		
		$cmd = "curl $url -d '$formData'";
		$response = exec($cmd);
        return json_decode($response, true);
    }
}
