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
        echo json_encode($res);
    } 
    public static function returnJsonEx($array = '', $code = 0, $info = '', $total = 0, $totalIncome = 0.0, $totalPay = 0.0) {
        header('Content-Type: application/json; charset=UTF-8');
        $res = array(
            'status' => $code,
            'info' => $info,
			'data' => $array,
			'total' => $total,
			'total_income' => $totalIncome,
			'total_pay' => $totalPay,
        );  
        echo json_encode($res);
    } 

	public static function returnStr($str){
        header('Content-Type: application/json; charset=UTF-8');
		echo $str;
	}

	public static function returnFile($fileName, $downloadName){
		header("Content-type: application/octet-stream");
		header("Accept-Ranges: bytes");
		header("Accept-Length: ".filesize($fileName));
		header("Content-Disposition: attachment; filename=" . $downloadName);
		$file = fopen($fileName,"r"); 
		echo fread($file,filesize($fileName));
		fclose($file);
		@unlink($fileName);
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
		$tpl = Bd_TplFactory::getInstance("yunyunche_odp");
		if (empty($data) || !is_array($data)) {
			$data = array();
		}
		// 页面权限信息
		$authority = array(
			'page' => Tool_Const::$pageAuthes,
			'tab' => Tool_Const::$tabAuthes,
			'storeId' => Tool_Const::$storeId,
			'storename' => Tool_Const::$storeName,
			'username' => Tool_Const::$adminInfo['name'],
		);
		//var_dump($authority);
		//exit;
		$data = array_merge($authority, $data);
        $tpl->assign(array('data' => $data));

        $strRet = $tpl->fetch($strTpl);
        header('Content-Type: text/html; charset=UTF-8');
        echo $strRet;
	}

	public static function getToken() {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
			'appid' => Tool_Const::APPID, 
			'secret' => Tool_Const::SECRET, 
			'grant_type' => 'client_credential'
		);
		
		$response = Tool_Util::postForm($url, $data);
		return $response['access_token'];
	}
	public static function getToken_X() {
        $url = 'https://api.weixin.qq.com/cgi-bin/token';
        $data = array(
			'appid' => Tool_Const::APPID_X, 
			'secret' => Tool_Const::SECRET_X, 
			'grant_type' => 'client_credential'
		);
		
		$response = Tool_Util::postForm($url, $data);
		return $response['access_token'];
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
		//return sprintf(Dao_Base::TABLE_FORMAT, Tool_Const::$storeId, $table);
		return sprintf(Dao_Base::TABLE_FORMAT, 'model',$table);
	}

	public static function getOpenid() {
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

	public static function getOpenid_X() {
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
		setcookie('yunyunche_openid', $openid, 0, "/", "www.yunyunche.cn");

        return $openid;			
	}	


	public static function createExcel($sheetName, $titles, $datas){	
		$objPHPExcel = new PHPExcel();
		$objWriter = new PHPExcel_Writer_Excel2007($objPHPExcel);
		$objPHPExcel->setActiveSheetIndex(0);
		$objPHPExcel->getActiveSheet()->setTitle($sheetName);

		//$titles = array('plate_number' => '车牌号码');
		$index = 'A';
		$rowIndex = 1;
		if (!empty($titles)){
			foreach ($titles as $key => $value){
				$position = $index.$rowIndex;
				$objPHPExcel->getActiveSheet()->setCellValue($position, $value);
				$objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
				$objPHPExcel->getActiveSheet()->getStyle($position)->getFill()->getStartColor()->setARGB('FF808080');
				$index++;
			}
			$rowIndex++;
		}
		foreach ($datas as $record){
			$index = 'A';
			if (!empty($titles)){
				foreach ($titles as $key => $value){
					$position = $index.$rowIndex;
					$objPHPExcel->getActiveSheet()->setCellValue($position, $record[$key]);
					$index++;
				}
			}
			else{
				foreach ($record as $itemKey => $itemValue){
					$position = $index.$rowIndex;
					$objPHPExcel->getActiveSheet()->setCellValue($position, $itemValue);
					$index++;
				}
			}
			$rowIndex++;
		}
		$fileName = rand();
		$objWriter->save($fileName);
		return $fileName;
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
