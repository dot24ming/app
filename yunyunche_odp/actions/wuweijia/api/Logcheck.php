<?php
/**
 * @name Action_Logcheck
 * @desc sample api
 * @author 吴伟佳
 */
class Action_Logcheck extends Saf_Api_Base_Action {

    public function __execute(){
    	$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['get'];
		$objServicePageLogcheckApi = new Service_Page_LogcheckApi();
		$arrPageInfo = $objServicePageLogcheckApi->execute($arrInput);

		$strOut = $arrOutput['data'];
		//echo $strOut;

		Bd_Log::addNotice('out', $arrOutput); 

        return $arrPageInfo;
    }
	
    public function __render($arrRes){
    	echo json_encode($arrRes);
    }
	
	public function __value($arrRes){
		echo json_encode($arrRes);
	}
}
