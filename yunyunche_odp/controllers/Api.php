<?php
/**
 * @name Api_Controller
 * @desc 主控制器,也是默认控制器
 * @author 吴伟佳
 */
class Controller_Api extends Ap_Controller_Abstract {
	public $actions = array(
		'addadmin' =>'actions/api/AddAdmin.php',
		'deleteadmin' =>'actions/api/DeleteAdmin.php',
		'modifyadminauthority' => 'actions/api/ModifyAdminAuthority.php',
		'adminauthoritylist' => 'actions/api/AdminAuthorityList.php',
		'servicelist' => 'actions/api/ServiceList.php',
		'addservicetype' => 'actions/api/AddServiceType.php',
		'addservice' => 'actions/api/AddService.php',
		'addsuper' => 'actions/api/AddSuper.php',
		'adddepartment' => 'actions/api/AddDepartment.php',
		'queryservice' => 'actions/api/QueryService.php',
		'modifyservice' => 'actions/api/ModifyService.php',
		'deleteservice' => 'actions/api/DeleteService.php',
		'queryservicetype' => 'actions/api/QueryServiceType.php',
		'deleteservicetype' => 'actions/api/DeleteServiceType.php',
		'brandlist' => 'actions/api/BrandList.php',
		'serieslist' => 'actions/api/SeriesList.php',
		'queryownerbycar' => 'actions/api/QueryOwnerByCar.php',
		'queryownerbyservice' => 'actions/api/QueryOwnerByService.php',
		'queryownerbycharge' => 'actions/api/QueryOwnerByCharge.php',
		'queryownerbycarnum' => 'actions/api/QueryOwnerByCarNum.php',
		'serviceformaddgetcate' => 'actions/api/ServiceformAddGetCate.php',
		'serviceformaddsubmit' => 'actions/api/ServiceformAddSubmit.php',
		'serviceformsearch' => 'actions/api/ServiceformSearch.php',
		'logout' => 'actions/api/Logout.php',
		'addcustomer' => 'actions/api/AddCustomer.php',
		'inscustomer' => 'actions/api/InsCustomer.php',
		'inscustomerex' => 'actions/api/InsCustomerEx.php',
		'feedback' => 'actions/api/Feedback.php',
		'feedbackstat' => 'actions/api/FeedbackStat.php',
		'selfeedback' => 'actions/api/SelFeedback.php',
		'searchcustomer' => 'actions/api/SearchCustomer.php',
		'selcustomer' => 'actions/api/SelCustomer.php',
		'seluser' => 'actions/api/SelUser.php',
		'modcustomer' => 'actions/api/ModCustomer.php',
		'modcar' => 'actions/api/ModCar.php',
		'delcar' => 'actions/api/DelCar.php',
		'discovercustomer' => 'actions/api/DiscoverCustomer.php',
		'trackcustomer' => 'actions/api/TrackCustomer.php',
		'trackinsurance' => 'actions/api/TrackInsurance.php',
		'trackcarvalid' => 'actions/api/TrackCarValid.php',
		'trackuservalid' => 'actions/api/TrackUserValid.php',
		'trackpeccancy' => 'actions/api/TrackPeccancy.php',
		'tracksale' => 'actions/api/TrackSale.php',
		'searchmsg' => 'actions/api/SearchMessage.php',
		'selmessage' => 'actions/api/SelMessage.php',
		'getprovince' => 'actions/api/GetProvince.php',
		'getcity' => 'actions/api/GetCity.php',
		'getdistrict' => 'actions/api/GetDistrict.php',
		'getbrand' => 'actions/api/GetBrand.php',
		'getseries' => 'actions/api/GetSeries.php',
		'insstore' => 'actions/api/InsStore.php',
		'selstore' => 'actions/api/SelStore.php',
		'addstore' => 'actions/api/AddStore.php',
		'modstorestatus' => 'actions/api/ModStoreStatus.php',
		'sendphonemessage' => 'actions/api/SendPhoneMessage.php',
		'checkadminname' => 'actions/api/CheckAdminName.php',

		#wwj
		#insert
		'insinstock' => 'actions/api/InsInstock.php',
		'insoutstock' => 'actions/api/InsOutstock.php',
		'insinventory' => 'actions/api/InsInventory.php',
		'instransfer' => 'actions/api/InsTransfer.php',
		'inspurchase' => 'actions/api/InsPurchase.php',
		'insquote' => 'actions/api/InsQuote.php',
		'quickstock' => 'actions/api/QuickStock.php',
		#page model
		'instock' => 'actions/api/InStock.php',
		'outstock' => 'actions/api/OutStock.php',
		'inventorystock' => 'actions/api/InventoryStock.php',
		'transferstock' => 'actions/api/TransferStock.php',
		'purchase' => 'actions/api/Purchase.php',
		'quote' => 'actions/api/Quote.php',
		#common api
		'matchgoods' => 'actions/api/MatchGoods.php',
		'checkgoodsnumber' => 'actions/api/CheckGoodsNumber.php',
		'matchsupplier' => 'actions/api/MatchSupplier.php',
		'addsupplier' => 'actions/api/AddSupplier.php',
		#other
		'verify' => 'actions/api/Verify.php',
		'getcategorylist' => 'actions/api/GetCategoryList.php',
		'matchcar' => 'actions/api/MatchCar.php',
		#client
		'relatemycar' => 'actions/api/RelateMyCar.php',
		'outstockdemand' => 'actions/api/OutstockDemand.php',
		'outstockdemanddetail' => 'actions/api/OutstockDemandDetail.php',
		'outstockdemandverify' => 'actions/api/OutstockDemandVerify.php',
		'serviceinstockdemand' => 'actions/api/ServiceInstockDemand.php',
		'serviceoutstockdemand' => 'actions/api/ServiceOutstockDemand.php',
		'membercharge' => 'actions/api/MemberCharge.php',
		'clientbusiness' => 'actions/api/ClientBusiness.php',
		'memberbuybusiness' => 'actions/api/MemberBuyBusiness.php',
		'packagebuybusiness' => 'actions/api/PackageBuyBusiness.php',
		'membercomhistory' => 'actions/api/MemberComHistory.php',
		'packagecomhistory' => 'actions/api/PackageComHistory.php',
		'getgoodsdetail' => 'actions/api/GetGoodsDetail.php',
		'instockexport' => 'actions/api/InstockExport.php',
		'outstockexport' => 'actions/api/OutstockExport.php',
		'inventoryexport' => 'actions/api/InventoryExport.php',
		'transferexport' => 'actions/api/TransferExport.php',
	

		#ycx
		#'checkclient' => 'actions/api/CheckClient.php',
		'mobilegetseries' => 'actions/api/MobileGetSeries.php',
		'mobilecheckcaruser' => 'actions/api/MobileCheckCarUser.php',
		'mobilesetuserinfo' => 'actions/api/MobileSetUserInfo.php',
		'mobilesetcarinfo' => 'actions/api/MobileSetCarInfo.php',
		'mobileserviceform' => 'actions/api/MobileServiceForm.php',
		'mobilesubmitservice' => 'actions/api/MobileSubmitService.php',
		'mobileservicesearch' => 'actions/api/MobileServiceSearch.php',
		'mobileserviceselect' => 'actions/api/MobileServiceSelect.php',
		'mobileserviceformsearch' => 'actions/api/MobileServiceFormSearch.php',
		'mobilesubsearch' => 'actions/api/MobileSubSearch.php',
		'mobileserviceformload' => 'actions/api/MobileServiceFormLoad.php',
		'addserviceinfo' => 'actions/api/AddServiceInfo.php',
		'mobilegetemployee' => 'actions/api/MobileGetEmployee.php',
		'mobilegetname4' => 'actions/api/MobileGetSeries4.php',
		'mobilegetname5' => 'actions/api/MobileGetSeries5.php',
		'mobilegetname6' => 'actions/api/MobileGetSeries6.php',
		'mobilegetname7' => 'actions/api/MobileGetSeries7.php',

		'packagesearch' => 'actions/api/PackageSearch.php',
		'packageadd' => 'actions/api/PackageAdd.php',
		'packagecardsearch' => 'actions/api/PackageCardSearch.php',
		'packagecardadd' => 'actions/api/PackageCardAdd.php',

		'reportsearch' => 'actions/api/ReportSearch.php',
		'reportbalancesearch' => 'actions/api/ReportBalanceSearch.php',

		'reportbalancesearchload' => 'actions/api/ReportBalanceSearchLoad.php',
		'reportsearchload' => 'actions/api/ReportSearchLoad.php',

		'quickserviceformsearch' => 'actions/api/QuickServiceFormSearch.php',
		


		#lilina
		'goodsstoragelist' => 'actions/api/GoodsStorageList.php',
		'goodsshipmentlist' => 'actions/api/GoodsShipmentList.php',
		'goodsinventorylist' => 'actions/api/GoodsInventoryList.php',
		'goodspurchaselist' => 'actions/api/GoodsPurchaseList.php',
		'goodsinventoryinfo' => 'actions/api/GoodsInventoryInfo.php',
		'goodspurchaseinfo' => 'actions/api/GoodsPurchaseInfo.php',
		'goodsshipmentinfo' => 'actions/api/GoodsShipmentInfo.php',
		'goodsstorageinfo' => 'actions/api/GoodsStorageInfo.php',
		'goodsstoragedel' => 'actions/api/GoodsStorageDel.php',
		'goodsshipmentdel' => 'actions/api/GoodsShipmentDel.php',
		'goodsshipmentupdate' => 'actions/api/GoodsShipmentUpdate.php',
		'goodsstorageupdate' => 'actions/api/GoodsStorageUpdate.php',
		'adduserrequirement' => 'actions/api/AddUserRequirement.php',
		'goodslist' => 'actions/api/GoodsList.php',
		'goodstransferlist' => 'actions/api/GoodsTransferList.php',
		'goodstransferinfo' => 'actions/api/GoodsTransferInfo.php',
		'goodsquotelist' => 'actions/api/GoodsQuoteList.php',
		'goodsquoteinfo' => 'actions/api/GoodsQuoteInfo.php',
		'addgoodsinfo' => 'actions/api/AddGoodsInfo.php',
		'warehouselist' => 'actions/api/WarehouseList.php',
		'addhouse' => 'actions/api/AddWarehouse.php',
		'goodstransferupdate' => 'actions/api/GoodsTransferUpdate.php',
		'goodsinventoryupdate' => 'actions/api/GoodsInventoryUpdate.php',
		'goodsinventorydel' => 'actions/api/GoodsInventoryDel.php',
		'goodstransferdel' => 'actions/api/GoodsTransferDel.php',
		'supplierinfo' => 'actions/api/SupplierInfo.php',
		'supplierlist' => 'actions/api/SupplierList.php',
		'supplierupdate' => 'actions/api/SupplierUpdate.php',

		'sample' => 'actions/api/Sample.php',
	);
}
