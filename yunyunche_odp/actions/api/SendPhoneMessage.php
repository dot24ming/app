<?php 
class Action_SendPhoneMessage extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$numbers = $arrInput['phoneNum'];	
		$content = Tool_Util::filter($arrInput['content']);

		$type = $arrInput['type'];

		foreach ($numbers as $number) 	
			$ret = Tool_Util::postSMS($number, $content);
			//$ret = 0;
			if ($ret === 0) {
				$daoPushHistory = new Dao_PushHistory();
				$daoPushHistory->addRecord($number, $content, Tool_Const::$tab_type_map[$type]);				
			} else {
				Bd_Log::warning("push [$content] to $number failed!");	
			}
		Tool_Util::returnJson();
	}
}
