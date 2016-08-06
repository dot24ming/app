<?php 
class Tool_Authority {
	// 页面权限控制
	static $pageAuth = array(
        // 没有登录就能访问
		1 => array('/sample', '/login', '/nouth', '/logcheck', '/product', '/contact', '/addstore','/searchstore', '/mobilelogin', '/mobilehome', '/mobileaddcar', '/mobileadd', '/mobileeditcar', '/mobileedit', '/mobileinfo', '/mobilelist', '/mobilequery', '/mobileindex', '/mobilebuilding', '/mobileserviceselect', '/mobileservicesearch', '/testforwx', '/mobileserviceform', '/mobileservicelist', '/mobileqrcode', '/mobileservicedetail', '/mobilestocksearch'),


		// 无需特定权限，只要登录就能访问
		0 => array(
			'/mobileuserrequirementquery',
			'/indexuserrequirement',
			'/mobileuserrequirementadd',
			'/mobileuserrequirementinfo',
			'/mobileserviceselect',
			'/testforwx',
			'/report',
		),

		1001 => array('/addcustomer'),
		1002 => array('/searchcustomer'),
		1003 => array('/modcustomer'),
		1004 => array('/discovercustomer'),
		1005 => array('/feedback'),
		1006 => array('/searchmessage'),

		2001 => array('/serviceformadd'),
		2002 => array('/serviceformsearch'),
		2003 => array('/instock', '/outstock', '/inventorystock', '/transferstock','/purchase','/quote', '/supplier', '/outstockdemand', '/itemstock'),
		3001 => array('/authority'),
		3002 => array('/service', '/servicetype'),
		3003 => array('/discovercustomer', '/pushmessage', '/feedback', '/searchmessage'),
		4001 => array(),

	);

	// api权限控制
	static $apiAuth = array(
		// api接口
		1 => array('/api/sample', '/api/addstore','/api/selstore','/api/modstorestatus','/api/insstore', '/api/changestatus','/api/getcity','/api/getdistrict','/api/getbrand','/api/getseries', '/api/getprovince', '/api/matchgoods', '/api/matchsupplier', '/api/getcategorylist', '/api/matchcar', '/api/checkadminname', '/api/mobilecheckcaruser', '/api/goodsstoragelist', '/api/goodsshipmentlist', '/api/goodsinventorylist', '/api/goodspurchaselist', '/api/goodsinventoryinfo', '/api/goodspurchaseinfo', '/api/goodsshipmentinfo', '/api/goodsstorageinfo', '/api/mobilegetseries', '/api/mobilesetuserinfo', '/api/mobilesetcarinfo','/api/checkgoodsnumber', '/api/mobileservicesearch', '/api/mobileserviceselect', '/api/mobileserviceformsearch', '/api/mobilesubsearch', '/api/mobileserviceformload', '/api/addserviceinfo', '/api/mobilegetemployee', '/api/membercharge', '/api/clientbusiness', '/api/packagesearch', '/api/packagecardsearch', '/api/packageadd', '/api/packagecardadd', '/api/reportsearch', '/api/reportbalancesearch','/api/memberbuybusiness','/api/packagebuybusiness','/api/membercomhistory','/api/packagecomhistory', '/api/qrcode','/api/selcustomer', '/api/reportbalancesearchload', '/api/reportsearchload', '/api/quickserviceformsearch','/api/quickstock','/api/getgoodsdetail'),

		// 无需特定权限，只要登录就能访问
		0 => array(
			'/api/logout',
			'/api/insinstock',
			'/api/insoutstock',
			'/api/insinventory',
			'/api/instransfer', 
			'/api/inspurchase',
			'/api/insquote',
			'/api/outstockdemand',
			'/api/outstockdemanddetail',
			'/api/outstockdemandverify',
			'/api/serviceinstockdemand',
			'/api/serviceoutstockdemand',

			'/api/instock',
			'/api/outstock',
			'/api/inventorystock',
			'/api/transferstock', 
			'/api/purchase',
			'/api/quote',

			'/api/addsupplier',
			'/api/verify',
			'/api/relatemycar',

			'/api/mobilecheckcaruser',
			'/api/mobilesetuserinfo',

			'/api/goodsstoragelist', 
			'/api/goodsshipmentlist', 
			'/api/goodsinventorylist', 
			'/api/goodspurchaselist', 
			'/api/goodsinventoryinfo', 
			'/api/goodspurchaseinfo', 
			'/api/goodsshipmentinfo', 
			'/api/goodsstorageinfo',
			'/api/goodsstoragedel',
			'/api/goodsshipmentdel',
			'/api/goodsinventorydel',
			'/api/goodstransferdel',
			'/api/goodstransferupdate',
			'/api/goodsshipmentupdate',
			'/api/goodsstorageupdate',
			'/api/goodsinventoryupdate',
			'/api/adduserrequirement',
			'/api/goodslist',
			'/api/goodstransferlist',
			'/api/goodstransferinfo',
			'/api/goodsquoteinfo',
			'/api/goodsquotelist',
			'/api/addgoodsinfo',
			'/api/addwarehouse',
			'/api/warehouselist',
			'/api/supplierlist',
			'/api/supplierinfo',
			'/api/supplierupdate',
			'/api/mobilesubmitservice',
			'/api/instockexport',
			'/api/outstockexport',
			'/api/inventoryexport',
			'/api/transferexport',
		),

		1001 => array('/api/inscustomer','/api/getprovince','/api/seluser'),
		1002 => array(), ///api/selcustomer
		1003 => array('/api/modcustomer','/api/modcar','/api/delcar'),
		1004 => array('/api/trackinsurance','/api/trackcarvalid','/api/trackuservalid','/api/trackpeccancy','/api/tracksale'),
		1005 => array('/api/feedback'),
		1006 => array('/api/selmessage'),

		2001 => array(),
		2002 => array(),
		2003 => array(),
		3001 => array('/api/addadmin', '/api/deleteadmin', '/api/adminauthoritylist', '/api/modifyadminauthority'),
		3002 => array('/api/serviceformsearch', '/api/serviceformaddsubmit', '/api/servicelist', '/api/queryservicetype', '/api/queryservice', '/api/brandlist', '/api/serieslist', '/api/addservicetype', '/api/addservice', '/api/adddepartment', '/api/addsuper', '/api/modifyservice'),
		3003 => array('/api/feedback','/api/trackinsurance','/api/trackcarvalid','/api/trackuservalid','/api/trackpeccancy','/api/tracksale','/api/inscustomer','/api/getprovince','/api/getcity','/api/getdistrict','/api/getbrand','/api/getseries','/api/seluser','/api/selcustomer','/api/selmessage','/api/modcustomer', '/api/queryownerbycarnum', '/api/queryownerbycharge', '/api/queryownerbyservice', '/api/queryownerbycar', '/api/sendphonemessage', '/api/inscustomerex'),

	); 
	
	// 子tab的权限控制	
	static $tabAuth = array(
		1001 => array(),
		1002 => array(),
		1003 => array(),
		1004 => array('/api/trackinsurance','/api/trackcarvalid','/api/trackuservalid','/api/trackpeccancy','/api/tracksale'),
		1005 => array(),
		1006 => array(),

		2001 => array(),
		2002 => array(),
		2003 => array(),
		3001 => array('addadmin', 'authoritymanage'),
		3002 => array('addservice', 'queryservice', 'addservicetype', 'queryservicetype'),
		3003 => array('series', 'service', 'charge', 'carnum'),

		// 无需特定权限，只要登录就能访问
		0 => array(),
	);	
}
