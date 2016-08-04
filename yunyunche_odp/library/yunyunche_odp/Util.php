<?php
/**
 * @name Yunyunche_odp_Util
 * @desc APP公共工具类
 * @author 吴伟佳
 */
class Yunyunche_odp_Util{
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
	
}
