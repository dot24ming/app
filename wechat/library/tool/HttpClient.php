<?php
/**
 * @name Wechat_Util
 * @desc APP公共工具类
 * @author 
 */
class Tool_HttpClient{
	static public function get($url, $param = array(), $timeout = 600) {
		$ch = curl_init();
		
		$url = self::genUrl($url, $param);

    	curl_setopt($ch, CURLOPT_URL, $url);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		//curl_setopt($ch, CURLOPT_HEADER, 0); 
		//curl_setopt($ch, CURLOPT_TIMEOUT, $timeout); 

		$output = curl_exec($ch);
    	curl_close($ch);
    	return $output;
	}

	static function genUrl($url, $param) {
		$queryStr = "";
		if (!empty($param) && is_array($param)) {
			$items = array();
			foreach ($param as $key => $item) {
				$items[] = "$key=".urlencode($item);
			}
			$queryStr = "?" . implode('&', $items);
		} 	
		$url .= $queryStr;
		return $url;
	}

	static function proxy($url, $param, $timeout) {
		$oriUrl = self::genUrl($url, $param);
		$result = self::get("http://115.29.104.45:9009/proxy/", array('url' => $oriUrl));
		return $result;
	}
}
