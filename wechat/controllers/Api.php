<?php
/**
 * @name Api_Controller
 * @desc 主控制器,也是默认控制器
 * @author 
 */
class Controller_Api extends Ap_Controller_Abstract {
	public $actions = array(
		'sample' => 'actions/api/Sample.php',


		//wwj
		'relatemycar' => 'actions/api/RelateMyCar.php',
		'comment' => 'actions/api/Comment.php',
		'mobileserviceformsearch' => 'actions/api/MobileServiceFormSearch.php',
		'editmycar' => 'actions/api/EditMyCar.php',
		'employeeapply' => 'actions/api/EmployeeApply.php',
		'applylist' => 'actions/api/ApplyList.php',
		'employeeupdate' => 'actions/api/EmployeeUpdate.php',
		'employeeverify' => 'actions/api/EmployeeVerify.php',
		'addoutstockdemand' => 'actions/api/AddOutstockDemand.php',
		'outstockdemandverify' => 'actions/api/OutstockDemandVerify.php',
		'employeedelete' => 'actions/api/EmployeeDelete.php',
		//lilina
		'carillegalinfo' => 'actions/api/CarIllegalinfo.php',
		'callback' => 'actions/api/Callback.php',   // 客户端微信回调函数
		'servercallback' => 'actions/api/ServerCallback.php',   // 接车端微信回调函数
		'completeuserinfo' => 'actions/api/CompleteUserInfo.php',
		'qrcode' => 'actions/api/Qrcode.php',


		//ycx
		'delrelatemycar' => 'actions/api/DelRelateMyCar.php',
	

	);
}
