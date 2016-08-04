<?php 
class Action_QuickServiceFormSearch extends Ap_Action_Abstract {
	public function execute() {
		$arrRequest = Saf_SmartMain::getCgi();
		$arrInput = $arrRequest['get'];
		//var_dump($arrInput);
		//参数解析
		$sampleServiceName = $arrInput['sample_service_name'];
		$sampleServiceFormDao = new Dao_SampleServiceForm(Tool_Const::$storeId);
		if (empty($sampleServiceName)){
			$sampleServiceNames = array();
			$sampleServiceFormInfos = $sampleServiceFormDao->getSampleServiceForm();
			foreach ($sampleServiceFormInfos as $sampleServiceFormInfo){
				$sampleServiceName = $sampleServiceFormInfo['sample_service_name'];
				if (array_key_exists($sampleServiceNames, $sampleServiceName)){
					continue;
				}
				array_push($sampleServiceNames, $sampleServiceName);
			}

			return Tool_Util::returnJson($sampleServiceNames, 0, '获取名字');
		}
		$sampleServiceFormInfo = $sampleServiceFormDao->getSampleServiceFormByName($sampleServiceName);
		if (empty($sampleServiceFormInfo)){
			return Tool_Util::returnJson('', 1, '没有对应数据');
		}
		return Tool_Util::returnJson($sampleServiceFormInfo, 0, '开单成功');
	}
}
