<?php
/**
 * @name Service_Page_Logcheck
 * @desc sample page service, 和action对应，组织页面逻辑，组合调用data service
 * @author 吴伟佳
 */
class Service_Page_InsCustomer {
    private $objServiceDataInsCustomer;
    public function __construct(){
        $this->objServiceDataInsCustomer = new Service_Data_InsCustomer();
    }
    //set注入
    public function __set($property,$value){
    	$this->$property = $value;
    }
    
    public function execute($arrInput){
        Bd_Log::debug('Logcheck page service called');
        $arrResult = array();
		$arrResult['errno'] = 0;
		$arrResult['data'] = '';
		$arrResult['ret'] = 0;
	   
 				
		try{
			//$intId = intval($arrInput['id']);
			if (isset($arrInput["user_id"]))
			{
				$strCustomerData["user_id"] = $arrInput["user_id"];
				$checkResult = $this->objServiceDataInsCustomer->doCheckUser($arrInput["user_id"]);
				if ($checkResult>0)
				{
					$strCustomerData['code'] = 0;
					$strCustomerData["codeMsg"] = "";
				}
				else
				{
					$strCustomerData['code'] = -1;
					$strCustomerData["codeMsg"] = "no user with this id";
				}
			}
			else
			{
				$strCustomerData = $this->objServiceDataInsCustomer->doInsCustomer($arrInput);
			}
			
			if ($strCustomerData['code'] == 0){
				$user_id = $strCustomerData['user_id'];
				$cars_entity = array();
				if(isset($arrInput['cars']))
				{
					$cars_entity = json_decode($arrInput['cars'],true);
				}
				if (count($cars_entity)>0 )
				{
					if ($user_id > 0)
					{
						$all_success = true;
						foreach($cars_entity as $car_input)
						{
							$strCarData = $this->objServiceDataInsCustomer->doInsCarInfo($car_input,$user_id);
							$arrResult['data'] = $strCarData['result'];
							$arrResult['ret'] = $strCarData['code'];
							$arrResult['errno'] = $strCarData['code'];
							$arrResult['info'] =  $strCarData['codeMsg'];

							if($strCarData['code']!==0)
							{
								$all_success = false;
							}
						}
						if($all_success!==true)
						{
							$arrResult['errno'] = 1;
						}
					}
					else{
						//删除用户 或者 保留用户关联
						$arrResult['data'] = 'user_id error';
						$arrResult['ret'] = -1;
						$arrResult['errno'] = 201;
						$arrResult['info'] =  $strCustomerData['codeMsg'];
					}
				}
				else
				{
					$arrResult['data'] = '';
					$arrResult['ret'] = 0;
					$arrResult['errno'] = 0;
					$arrResult['info'] =  'insert user succ';
				}
			}
			else{
				$arrResult['data'] = 'insert fail';
				$arrResult['ret'] = -1;
				$arrResult['errno'] = 201;
				$arrResult['info'] =  $strCustomerData['codeMsg'];
			}
			


		}catch(Exception $e){
			Bd_Log::warning($e->getMessage(), $e->getCode());
			$arrResult['data'] = $e->getMessage();
			$arrResult['errno'] = $e->getCode();
		}

	

		return $arrResult;
    }
}
