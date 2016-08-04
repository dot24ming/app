<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_Feedback {
    private $objServiceDataSelFeedback;
	private $objServiceDataFeedbackStat;
    public function __construct(){
        $this->objServiceDataSelFeedback = new Service_Data_SelFeedback();
		$this->objServiceDataFeedbackStat = new Service_Data_FeedbackStat();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
    
    public function execute($arrInput){
        Bd_Log::debug('Feedback page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
	   
 				
		try{
			$arrResult["data"] = array();

			$strData = $this->objServiceDataSelFeedback->doSelFeedback($arrInput);
			$arrResult['data']["feedback"] = $strData['result'];
			$arrResult['data']["feedback_count"] = $strData['count'];
			$feedback_code = $strData['code'];

			$gt_score = 3;
			$lt_score = 2;
			$strData = $this->objServiceDataFeedbackStat->doFeedbackStat($gt_score,$lt_score);
			$arrResult['data']['stat'] = array($strData['result'][0]+ $strData['result'][1],$strData['result'][0],$strData['result'][1]);
			$stat_code = $strData['code'];

			if($feedback_code!=0){
				$arrResult['errno'] = $feedback_code;
			}
			else{
				$arrResult['errno'] = $stat_code;
			}

		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['errno'] = $e->getCode();
		}

		return $arrResult;
    }
}
