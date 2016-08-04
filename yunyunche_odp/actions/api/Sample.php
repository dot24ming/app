<?php
/**
 * @name Action_Sample
 * @desc sample api
 * @author 吴伟佳
 */
class Action_Sample extends Saf_Api_Base_Action {

    public function __execute(){
		echo "hello";
    }
	
    public function __render($arrRes){
    	echo json_encode($arrRes);
    }
	
	public function __value($arrRes){
		echo json_encode($arrRes);
	}
}
