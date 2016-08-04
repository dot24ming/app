<?php
/**
 * @name Action_Sample
 * @desc sample api
 * @author 
 */
class Action_CarIllegalinfo extends Saf_Api_Base_Action {

    public function __execute(){
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];

		$plateNum = Tool_Util::filter($arrInput['plate_num']);
		$carInfoDao = new Dao_CarInfo(Tool_Const::$storeId);	
		$carInfo = $carInfoDao->getInfoByPlateNumber($plateNum);

		if (empty($carInfo) || !is_array($carInfo) || empty($carInfo['frame_number']) || empty($carInfo['engine_number'])) {
			return Tool_Util::ReturnJson('', 128);
		}
		$frameNumber = $carInfo['frame_number'];
		$engineNumber = $carInfo['engine_number'];
	
		$backend = new Dao_Backend();
		$items = Dao_Backend::getItems($plateNum, $engineNumber, $frameNumber);
		//return Tool_Util::returnJson($items);
		Bd_Log::warning(json_encode($items));

		//$count = $items['count'];
		//if (empty($count) || !is_array($items['historys'])) {
		//	return Tool_Util::returnJson(array('total'=> 0, 'list' => array()));
		//}
		/*
		$totalNum = 0;
		$totalCost = 0;
		$totalScore = 0;
		foreach ($items['historys'] as $history) {
			$totalNum += 1;
			$totalCost += $history['money'];
			$totalScore += $history['fen'];
		}	

		$retArr = array(
			'total' => $items['count'],
			'list' => $items['historys'],
			'recent_insert_time' => date('Y-m-d', time()),
			'total_count' => $totalNum,
			'total_money' => $totalCost,
			'total_score' => $totalScore,
		);
		 */
		//return Tool_Util::returnJson($retArr);
		$items['recent_insert_time'] = date('Y-m-d', time());
		header('Content-Type: application/json; charset=UTF-8');
		echo json_encode($items);
		//return Tool_Util::returnJson($items);
	}
}
