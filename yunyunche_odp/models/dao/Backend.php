<?php 
class Dao_Backend {
		static public function getItems($plateNum, $engineNo, $frameNo) {
			Bd_Log::warning($plateNum.$engineNo.$frameNo);
			//$result = Tool_HttpClient::get("http://115.29.104.45:9009/illegalcheck/?plate_num=粤L39H41&frame_no=090995&engine_no=170662");
			$result = Tool_HttpClient::get("http://115.29.104.45:9009/illegalcheck/", 
				array(
					'plate_num' => $plateNum,
					'engine_no' => $engineNo,
					'frame_no' => $frameNo,
				));
			Bd_Log::warning($result);
			$retArr = json_decode($result, true);
			$historysNew = array();
			$totalScore = 0;
			$totalMoney = 0;
			$dateLine = date('Y-m-d', time()-2592000*3);
			//if ($ret['status'] == 2001){
				foreach($retArr['historys'] as $item){
					if ($item['occur_date'] < $dateLine){
						continue;
					}
					$totalScore = $totalScore + $item["fen"];
					$totalMoney = $totalMoney + $item["money"];
					array_push($historysNew, $item);
				}
				if (count($historysNew) == 0){
					$retArr['status'] = 2000;
					$retArr['total_score'] = 0;
					$retArr['total_money'] = 0;
					$retArr['historys'] = array();
					$retArr['count'] = 0;
				}
				else {
					$retArr['total_score'] = $totalScore;
					$retArr['total_money'] = $totalMoney;
					$retArr['historys'] = $historysNew;
					$retArr['count'] = count($historysNew);
				}
			//}
			return $retArr;
		}
}
