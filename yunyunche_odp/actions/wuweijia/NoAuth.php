<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_Noauth extends Ap_Action_Abstract {

	public function execute() {
		echo "无权限";
		/*
		$tpl = Bd_TplFactory::getInstance("yunyunche_odp");
	    $tpl->assign('data', array("authorityList" => $permissions));
	    $tpl->display('admin/page/authority.tpl');
		*/
	}
}
