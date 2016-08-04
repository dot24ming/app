<?php
/**
 * @name Action_Login
 * @desc sample action, 和url对应
 * @author 吴伟佳
 */
class Action_TestForWX extends Ap_Action_Abstract {

	public function execute() {
		Bd_Log::warning(json_encode($_SERVER));
		$str = file_get_contents('php://input', "r");
		Bd_Log::warning($str);
		echo "test";
	}

}
