<?php
		$param = array(
				'service_charge' => 1300,
				'other_charge' => 0,
				'car_no' => '粤B 58668',
				'user_id' => '张三',
				'cashier_id' => '李四',
				'all_charge' => 2000,
				'remark' => 'text info',
				'project' => array(
					array(
						'service_id' => 0,
						'operator_id' => '005',
						'create_time' => time(),
						'remark' => 'text info'
					),
					array(
						'service_id' => 1,
						'operator_id' => '005',
						'create_time' => time(),
						'remark' => 'text info'
					)
				)
			);
	print_r($param);
	$cp_info = array();
	$cp_info['service_charge'] = $param['service_charge'];
	$cp_info['other_charge'] = $param['other_charge'];
	$cp_info['car_no'] = $param['car_no'];
	$cp_info['user_id'] = $param['user_id'];
	$cp_info['cashier_id'] = $param['cashier_id'];
	$cp_info['all_charge'] = $param['all_charge'];
	$cp_info['remark'] = $param['remark'];
	print_r($cp_info);

	$maintenance_id = 123;
	for($i=0; $i < count($param['project']); $i++){
		$rec = array();
		$rec['maintenance_id'] = $maintenance_id;
		foreach($param['project'][$i] as $key=>$value){
			$rec[$key] = $value;
		}
		print_r($rec);
	}
	//echo date('Y-m-d G:i:s');

	$str = '{"a":1,"b":2,"c":3,"d":4,"e":5}';

	$ss = json_decode($str, true);

	var_dump($ss);
?>
