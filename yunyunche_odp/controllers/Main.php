<?php
/**
 * @name Main_Controller
 * @desc 主控制器,也是默认控制器
 * @author 吴伟佳
 */
class Controller_Main extends Ap_Controller_Abstract {
	public $actions = array(
		'sample' => 'actions/Sample.php',
		'login' => 'actions/Login.php',
		'logcheck' => 'actions/Logcheck.php',
		'serviceadd' => 'actions/ServiceAdd.php',
		'serviceformsearch' => 'actions/ServiceformSearch.php',
		'serviceformadd' => 'actions/ServiceformAdd.php',
		'serviceitemadd' => 'actions/ServiceitemAdd.php',

		'addcustomer' => 'actions/AddCustomer.php',
		'inscustomer' => 'actions/InsCustomer.php',
		'feedback' => 'actions/Feedback.php',
		'feedbackstat' => 'actions/FeedbackStat.php',
		'selfeedback' => 'actions/SelFeedback.php',
		'searchcustomer' => 'actions/SearchCustomer.php',
		'selcustomer' => 'actions/SelCustomer.php',
		'seluser' => 'actions/SelUser.php',
		'modcustomer' => 'actions/ModCustomer.php',
		'discovercustomer' => 'actions/DiscoverCustomer.php',
		'trackcustomer' => 'actions/TrackCustomer.php',
		'trackinsurance' => 'actions/TrackInsurance.php',
		'trackcarvalid' => 'actions/TrackCarValid.php',
		'trackuservalid' => 'actions/TrackUserValid.php',
		'trackpeccancy' => 'actions/TrackPeccancy.php',
		'tracksale' => 'actions/TrackSale.php',
		'searchmessage' => 'actions/SearchMessage.php',
		'selmessage' => 'actions/SelMessage.php',
		'authority' => 'actions/Authority.php',
		'getprovince' => 'actions/GetProvince.php',
		'getcity' => 'actions/GetCity.php',
		'getdistrict' => 'actions/GetDistrict.php',
		'getbrand' => 'actions/GetBrand.php',
		'getseries' => 'actions/GetSeries.php',
		'addstore' => 'actions/AddStore.php',
		'searchstore' => 'actions/SearchStore.php',

		'itemstock' => 'actions/ItemStock.php',
		'instock' => 'actions/InStock.php',
		'outstock' => 'actions/OutStock.php',
		'inventorystock' => 'actions/InventoryStock.php',
		'transferstock' => 'actions/TransferStock.php',
		'outstockdemand' => 'actions/OutstockDemand.php',


		'service' => 'actions/Service.php',
		'servicetype' => 'actions/ServiceType.php',
		'pushmessage' => 'actions/PushMessage.php',
		'noauth' => 'actions/NoAuth.php',
		'contact' => 'actions/Contact.php',
		'product' => 'actions/Product.php',

		#ycx
		'mobilelogin' => 'actions/MobileLogin.php',
		'mobilehome' => 'actions/MobileHome.php',
		'mobileaddcar' => 'actions/MobileAddCar.php',
		'mobileadd' => 'actions/MobileAdd.php',
		'mobileeditcar' => 'actions/MobileEditCar.php',
		'mobileedit' => 'actions/MobileEdit.php',
		'mobileinfo' => 'actions/MobileInfo.php',
		'mobilelist' => 'actions/MobileList.php',
		'mobilequery' => 'actions/MobileQuery.php',
		'mobileindex' => 'actions/MobileIndex.php',
		'mobileserviceform' => 'actions/MobileServiceForm.php',
		'mobileservicedetail' => 'actions/MobileServiceDetail.php',
		'mobileservicelist' => 'actions/MobileServiceList.php',
		'mobileservicestatus' => 'actions/MobileServiceStatus.php',
		'mobileserviceselect' => 'actions/MobileServiceSelect.php',
		'mobilesubmitservice' => 'actions/MobileSubmitService.php',
		'mobileqrcode' => 'actions/MobileQrcode.php',
		'mobilebuilding' => 'actions/MobileBuilding.php',
		'mobilestocksearch' => 'actions/MobileStockSearch.php',

		'report' => 'actions/Report.php',

		#lilina
		'mobileuserrequirementquery' => 'actions/MobileUserRequirementQuery.php',
		'mobileuserrequirementadd' => 'actions/MobileUserRequirementAdd.php',
		'mobileuserrequirementinfo' => 'actions/MobileUserRequirementInfo.php',
		'purchase' => 'actions/Purchase.php',
		'quote' => 'actions/Quote.php',
		'supplier' => 'actions/Supplier.php',
		'testforwx' => 'actions/TestForWX.php',


		'sample' => 'actions/Sample.php',
	);
}
