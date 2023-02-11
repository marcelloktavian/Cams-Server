<?php
	session_start();	
	define('_HOST', 'localhost');
	define('_USER', 'root');
	define('_PASS', '');
	define('_DBSE', 'cams_db2021');
	
	define('BASE_URL', 'http://localhost/camou/app/cams/');

	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	
	if(!isset($_SESSION['cams_logged_in']) && "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" != BASE_URL.'login.php')
		header('location: login.php');
	
	$db = new PDO('mysql:host='._HOST.';dbname='._DBSE.';charset=utf8', _USER, _PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //Sengaja gk di tutup tag PHP nya

//define control panel
define("Address", 2);
define("Category", 3);
define("Colour", 4);
define("DataBiayaOperasional", 5);
define("Dropshipper", 6);
define("ExpeditionCategory",7);
define("Expedition", 8);
define("Keranjang", 9);
define("Products", 10);
define("ProductsComposition", 11);

define("ImportCAMOU", 13);
define("PreSALES", 14);
define("ImportCredit", 15);
define("PreSALESCredit", 16);

define("DropshipperDeposit", 18);
define("DepositTransaction", 19);
define("OnlineSales", 20);
define("OnlineCredit",21);
define("PendingOrder", 22);
define("CancelOrder", 23);
define("OnlineDelivery",24);
define("OnlineBackDate",25);
define("ArchiveOrder", 26);
define("OnlineReturn", 27);
define("ReturnConfirmed", 28);

define("BiayaOperasional", 30);

define("OnlineSummary", 32);
define("SummaryCash", 33);
define("SummaryCredit", 34);
define("DropshipperStatistik", 35);
define("SalesOnlineDropshipper",36);
define("UnpaidOnline", 37);
define("Bill", 38);
define("rptexpedition", 39);
define("PrintOrder", 40);
define("ProductSold", 41);
define("Omset", 42);
define("TroubleOrder", 43);

define("CompositionProducts", 45);
define("B2BProducts", 46);
define("B2BProductsGroup", 47);
define("B2BCustomer", 48);
define("B2BExpedition",49);
define("B2BSalesman", 50);

define("AddSalesB2B", 52);
define("SalesB2B", 53);
define("ConfirmedSales", 54);
define("DeliveryOrderB2B", 55);
define("ProductSoldCompositions", 56);

define("SummaryDeliveryB2B", 58);

define("MUTASIMASUK", 60);
define("MUTASIKELUAR", 61);
define("INVENTORY", 62);

define("MUTASIMASUK_COMP", 64);
define("MUTASIKELUAR_COMP", 65);
define("INVENTORY_COMP", 66);

define("DataPengguna", 68);
define("UserGroup", 69);
define("GroupAkses", 70);
define("statusToko", 71);

define("VIEW_POLICY", "VIEW;");
define("ADD_POLICY", "ADD;");
define("EDIT_POLICY", "EDIT;");
define("DELETE_POLICY", "DELETE;");
define("POST_POLICY", "POST;");
define("PRINT_POLICY", "PRINT;");
define("RETURN_POLICY", "RETURN;");
define("IMPORT_POLICY", "IMPORT;");

function is_show_menu($policy, $menu_id, $group_access)
{
	$show=false;
	// var_dump($group_access);
	// var_dump(count($group_access));

	for ($i=0; $i < count($group_access); $i++) { 
		if ($group_access[$i]['menu_id']==$menu_id) {
			if ($group_access[$i]['policy']=="") {
				$show=false;
			} else {
				$pos = strpos($group_access[$i]['policy'], $policy);
				$show=$pos==true;
			}
			break;
		}
	}
	// var_dump($show);	
	return $show;
}
