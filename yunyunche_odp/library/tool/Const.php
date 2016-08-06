<?php 
class Tool_Const {
	static $username = '';

	static $pageAuthes = array();
	static $apiAuthes = array();
	static $tabAuthes = array();

	static $adminInfo = null;
	static $storeId = null;
	static $storeName = null;

	static $tab_type_map = array(
		'insurance'  => 0,  //保险相关
		'uservalid' => 1,   //驾驶证年审
		'carvalid'  => 2,    //车辆年审
		'peccancy'  => 3,   //车辆违章
		'sale'      => 4,   //项目售后
		'series'    => 5,   //车型推送
		'service'   => 6,   //服务项目
        'charge'    => 7,   //消费金额
		'carnum'    => 8,   //指定车牌		

	);

	static $pc_department_map = array(
		'receptionist' => 3,
		'storekeeper' => 4,
		'manager' => 5,
	);

	static $wise_department_map = array(
		'maintenance' => 1,
		'beauty' => 2,
		'sa' => 6,
		'other' => 7,
		);

	static $form_status_e2c = array(
		'reviewing' => '待审核',
		'reviewed' => '已审核',
		'rejected' => '审核未通过',
	);


	static $Instock_status = array(
		"reviewing" => "reviewing",
		"reviewed" => "reviewed",
		"rejected" => "rejected",
	);
	static $Outstock_status = array(
		"reviewing" => "reviewing",
		"reviewed" => "reviewed",
		"rejected" => "rejected",
	);
	static $Inventory_status = array(
		"reviewing" => "reviewing",
		"reviewed" => "reviewed",
		"rejected" => "rejected",
	);
	static $Transfer_status = array(
		"reviewing" => "reviewing",
		"reviewed" => "reviewed",
		"rejected" => "rejected",
	);
	static $Purchase_status = array(
		"reviewing" => "reviewing",
		"reviewed" => "reviewed",
		"rejected" => "rejected",
	);
	static $Quote_status = array(
		"reviewing" => "reviewing",
		"reviewed" => "reviewed",
		"rejected" => "rejected",
	);

	static $storage_type_e2c = array(
		'maintenance' => '维修入库',
		'purchase' => '日常采购',
		'inventory' => '盘盈入库',
		'transfer' => '调拨入库',
	);
	static $Instock_type = array(
		'maintenance' => 'maintenance',
		'purchase' => 'purchase',
		'inventory' => 'inventory',
		'transfer' => 'transfer',
	);

	static $shipment_type_e2c = array(
		'maintenance' => '维修出库',
		'sale' => '销售出库',
		'loss' => '损丢出库',
		'return' => '退货出库',
	);
	static $Outstock_type = array(
		'maintenance' => 'maintenance',
		'sale' => 'sale',
		'loss' => 'loss',
		'return' => 'return',
	);
	static $Purchase_type = array(
		'maintenance' => 'maintenance',
		'common' => 'common',
	);
	static $Settlement = array(
		'credit' => 'credit',
		'paid' => 'paid'
	);
	static $Verify_form_type =array(
		'instock' => 'model_goods_storage',
		'outstock' => 'model_goods_shipment',
		'inventory' => 'model_goods_inventory',
		'transfer' => 'model_goods_transfer',
		'purchase' => 'model_goods_purchase',
		'quote' => 'model_goods_quote',
	);
	static $Verify_form_info_type =array(
		'instock' => 'model_goods_storage_info',
		'outstock' => 'model_goods_shipment_info',
		'inventory' => 'model_goods_inventory_info',
		'transfer' => 'model_goods_transfer_info',
		'purchase' => 'model_goods_purchase_info',
		'quote' => 'model_goods_quote_info',
	);
	static $Verify_status_const = array(
		'instock' => 'storage_status',
		'outstock' => 'shipment_status',
		'inventory' => 'inventory_status',
		'transfer' => 'transfer_status',
		'purchase' => 'purchase_status',
		'quote' => 'quote_status',
	);
	static $Verify_id_const = array(
		'instock' => 'storage_id',
		'outstock' => 'shipment_id',
		'inventory' => 'inventory_id',
		'transfer' => 'transfer_id',
		'purchase' => 'purchase_id',
		'quote' => 'quote_id',
	);
	/**
 	* @name Wechat_Util
 	* @desc APP公共工具类
 	* @author 
 	*/
	public static $openid = '';

	//public static $storeId = 56;
	const TOKEN  = "dot24";

	//const APPID = "wxb1fe774d7aa2542b";
	//const SECRET = "92acc63145a1e76b9aa6df0af8bb5c98";
	//
	//云云车
	const APPID = "wx31ba7d3b639eb02c";
	const SECRET = "5fec6849044df3ee82c4f8de7001bbb9";
	//新时速
	const APPID_X = "wx4ff6f61b99094753";
	const SECRET_X = "8963a01fb691ba11595aad48e6ad6190";

	const QR_TYPE_CUSTOMER = 0;
	const QR_TYPE_EMPLOYEE = 1;
	const QR_TYPE_RELE = 2;

}
