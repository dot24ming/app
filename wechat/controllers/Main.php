<?php
/**
 * @name Main_Controller
 * @desc 主控制器,也是默认控制器
 * @author 
 */
class Controller_Main extends Ap_Controller_Abstract {
	public $actions = array(
		'sample' => 'actions/Sample.php',

		//wwj
		'relatemycar' => 'actions/RelateMyCar.php',
		'services' => 'actions/Services.php',
		'mycar' => 'actions/MyCar.php',
		'employeeapply' => 'actions/EmployeeApply.php',
		'applylist' => 'actions/ApplyList.php',
		//lilina
		'carillegalinfo' => 'actions/CarIllegaInfo.php',
		'complementuserinfo' => 'actions/ComplementUserInfo.php'
	);
}
