<?php 
class Action_QueryService extends Ap_Action_Abstract {
	public function execute() {
		$isAdmin = Service_Data_User::isAdmin();
		if (!$isAdmin) {
			return Tool_Util::returnJson('', 1, '非管理员，无权限');
		}

		$arrRequest = Saf_SmartMain::getCgi();
        $arrInput = $arrRequest['post'];

		$serviceTypeId = intval($arrInput['serviceTypeId']);
		$departmentId = intval($arrInput['departmentId']);
		$serviceName = $arrInput['serviceName'];
		$start = intval($arrInput['start']);
		$end = intval($arrInput['end']);
		
		$serviceInfoDao = new Dao_ServiceInfo(Tool_Const::$storeId);
			
		$services = $serviceInfoDao->search($serviceTypeId, $departmentId, $serviceName, $start, $end, $total);	
		if (!empty($services) && is_array($services)) {
			$serviceTypeDao = new Dao_ServiceType(Tool_Const::$storeId);
			$typeInfoList = $serviceTypeDao->getAllType();
			$superInfoList = $serviceTypeDao->getParent();

			$superInfos = array();
			if (!empty($superInfoList) && is_array($superInfoList)) {
				foreach ($superInfoList as $superInfo) {
					$superInfos[$superInfo['id']] = $superInfo;
				}
			}

			$typeInfos = array();
			if (!empty($typeInfoList) && is_array($typeInfoList)) {	
				foreach ($typeInfoList as $typeInfo) {
					$typeInfos[$typeInfo['id']] = $typeInfo;	
					$typeInfos[$typeInfo['id']]['superName'] = $superInfos[$typeInfo['super_type_id']]['name'];
				}
			}

			$serviceList = array();
			foreach ($services as $service) {
				$serviceList[] = array(
					'serviceId' => $service['service_id'],
					'superName' => $typeInfos[$service['type']]['superName'],
					'typeName' => $typeInfos[$service['type']]['name'],
					'name' => $service['name'],
					'charge' => $service['charge'],
					'unit' => $service['unit'] ,
					'costPrice' => $service['cost_price'],
					'referencePrice' => $service['reference_price'],
					'guaranteePeriod' => $service['guarantee_period'],
					'pilgrimageTime' => $service['pilgrimage_time'],
				);	
			}
			return Tool_Util::returnJson(array('total' => $total, 'serviceList' => $serviceList));
		} else {
			return Tool_Util::returnJson(array('total' => 0, 'serviceList' => array()));
		}
	}
}
