<?php 
class Action_Logout extends Ap_Action_Abstract {
	public function execute() {
		Service_Data_User::deleteCookie();
//		return Tool_Util::returnJson();
		header("Location: /login");
	}
}
