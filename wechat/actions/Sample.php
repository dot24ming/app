<?php
/**
 * @name Action_Sample
 * @desc sample action, 和url对应
 * @author 
 */
class Action_Sample extends Ap_Action_Abstract {

	public function execute() {
		var_dump($_SERVER);
	}

}
