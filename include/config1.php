<?php
	session_start();	
	define('_HOST', 'localhost');
	define('_USER', 'root');
	define('_PASS', '');
	define('_DBSE', 'cams_db2022');
	
	define('BASE_URL', 'http://192.168.1.254/cams2022/');

	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	
	if(!isset($_SESSION['cams_logged_in']) && "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" != BASE_URL.'login.php')
		header('location: login.php');
	
	$db = new PDO('mysql:host='._HOST.';dbname='._DBSE.';charset=utf8', _USER, _PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //Sengaja gk di tutup tag PHP nya

//define control panel
// define("Address", 2);
// define("Category", 3);
// define("Colour", 4);
// define("DataBiayaOperasional", 5);
// define("Dropshipper", 6);
// define("ExpeditionCategory",7);
// define("Expedition", 8);
// define("Keranjang", 9);
// define("Products", 10);
// define("ProductsComposition", 11);

// define("ImportCAMOU", 13);
// define("PreSALES", 14);
// define("ImportCredit", 15);
// define("PreSALESCredit", 16);

// define("DropshipperDeposit", 18);
// define("DepositTransaction", 19);
// define("OnlineSales", 20);
// define("OnlineCredit",21);
// define("PendingOrder", 22);
// define("CancelOrder", 23);
// define("OnlineDelivery",24);
// define("OnlineBackDate",25);
// define("ArchiveOrder", 26);
// define("OnlineReturn", 27);
// define("ReturnConfirmed", 28);

// define("BiayaOperasional", 30);

// define("OnlineSummary", 32);
// define("SummaryCash", 33);
// define("SummaryCredit", 34);
// define("DropshipperStatistik", 35);
// define("SalesOnlineDropshipper",36);
// define("UnpaidOnline", 37);
// define("Bill", 38);
// define("rptexpedition", 39);
// define("PrintOrder", 40);
// define("ProductSold", 41);
// define("Omset", 42);
// define("TroubleOrder", 43);

// define("CompositionProducts", 45);
// define("B2BProducts", 46);
// define("B2BProductsGroup", 47);
// define("B2BCustomer", 48);
// define("B2BExpedition",49);
// define("B2BSalesman", 50);

// define("AddSalesB2B", 52);
// define("SalesB2B", 53);
// define("ConfirmedSales", 54);
// define("DeliveryOrderB2B", 55);
// define("ProductSoldCompositions", 56);

// define("SummaryDeliveryB2B", 58);

// define("MUTASIMASUK", 60);
// define("MUTASIKELUAR", 61);
// define("INVENTORY", 62);

// define("MUTASIMASUK_COMP", 64);
// define("MUTASIKELUAR_COMP", 65);
// define("INVENTORY_COMP", 66);

// define("DataUser", 68);
// define("UserGroup", 69);
// define("GroupAkses", 70);
// define("statusToko", 71);


//v2
// define("Taxes", 2);
// define("KategoriBiaya", 3);
// define("JenisBiaya", 4);
// define("COA", 5);
// define("SettingAkun", 6);

// define("BiayaOperasional", 8);	
// define("BiayaOperasional2", 9);	

// define("Address", 11);
// define("Category", 12);
// define("Colour", 13);
// define("Keranjang", 14);
// define("Dropshipper", 15);
// define("ExpeditionCategory",16);
// define("Expedition", 17);
// define("Products", 18);
// define("ProductsComposition", 19);

// define("ImportCAMOU", 21);
// define("PreSALES", 22);
// define("ImportCredit", 23);
// define("PreSALESCredit", 24);

// define("DropshipperDeposit", 26);
// define("DepositTransaction", 27);
// define("OnlineSales", 28);
// define("OnlineCredit",29);
// define("PendingOrder", 30);
// define("CancelOrder", 31);
// define("OnlineDelivery",32);
// define("OnlineBackDate",33);
// define("ArchiveOrder", 34);
// define("OnlineReturn", 35);
// define("ReturnConfirmed", 36);

// define("OnlineSummary", 38);
// define("SummaryCash", 39);
// define("SummaryCredit", 40);
// define("DropshipperStatistik", 41);
// define("SalesOnlineDropshipper",42);
// define("UnpaidOnline", 43);
// define("Bill", 44);
// define("rptexpedition", 45);
// define("PrintOrder", 46);
// define("ProductSold", 47);
// define("Omset", 48);
// define("TroubleOrder", 49);

// define("CompositionProducts", 51);
// define("B2BProducts", 52);
// define("B2BProductsGroup", 53);
// define("B2BCustomer", 54);
// define("B2BExpedition",55);
// define("B2BSalesman", 56);

// define("AddSalesB2B", 58);
// define("SalesB2B", 59);
// define("ConfirmedSales", 60);
// define("DeliveryOrderB2B", 61);
// define("ArchiveOrderB2B", 62);
// define("ProductSoldCompositions", 63);

// define("SummaryDeliveryB2B", 65);

// define("MUTASIMASUK", 67);
// define("MUTASIKELUAR", 68);
// define("INVENTORY", 69);

// define("MUTASIMASUK_COMP", 71);
// define("MUTASIKELUAR_COMP", 72);
// define("INVENTORY_COMP", 73);

// define("DataUser", 75);
// define("UserGroup", 76);
// define("GroupAkses", 77);
// define("statusToko", 78);

// define("VIEW_POLICY", "VIEW;");
// define("ADD_POLICY", "ADD;");
// define("EDIT_POLICY", "EDIT;");
// define("DELETE_POLICY", "DELETE;");
// define("POST_POLICY", "POST;");
// define("PRINT_POLICY", "PRINT;");
// define("RETURN_POLICY", "RETURN;");
// define("IMPORT_POLICY", "IMPORT;");


//define control panel
// define("Taxes", 2);
// define("KategoriBiaya", 3);
// define("JenisBiaya", 4);
// define("mst_COA", 5);
// define("COA", 6);
// define("SettingAkun", 7);

// define("BiayaOperasional", 9);	
// define("cashReceipt", 10);	

// define("Address", 12);
// define("Category", 13);
// define("Colour", 14);
// define("Keranjang", 15);
// define("Dropshipper", 16);
// define("ExpeditionCategory",17);
// define("Expedition", 18);
// define("Products", 19);
// define("ProductsComposition", 20);

// define("ImportCAMOU", 22);
// define("PreSALES", 23);
// define("ImportCredit", 24);
// define("PreSALESCredit", 25);

// define("DropshipperDeposit", 27);
// define("DepositTransaction", 28);
// define("OnlineSales", 29);
// define("OnlineCredit",30);
// define("PendingOrder", 31);
// define("CancelOrder", 32);
// define("OnlineDelivery",33);
// define("OnlineBackDate",34);
// define("ArchiveOrder", 35);
// define("OnlineReturn", 36);
// define("ReturnConfirmed", 37);

// define("OnlineSummary", 39);
// define("SummaryCash", 40);
// define("SummaryCredit", 41);
// define("DropshipperStatistik", 42);
// define("SalesOnlineDropshipper",43);
// define("UnpaidOnline", 44);
// define("Bill", 45);
// define("rptexpedition", 46);
// define("PrintOrder", 47);
// define("ProductSold", 48);
// define("Omset", 49);
// define("TroubleOrder", 50);

// define("ImportCustomerProduct", 52);

// define("CompositionProducts", 54);
// define("B2BProducts", 55);
// define("B2BProductsGroup", 56);
// define("B2BCustomer", 57);
// define("B2BExpedition",58);
// define("B2BSalesman", 59);

// define("AddSalesB2B", 61);
// define("SalesB2B", 62);
// define("ConfirmedSales", 63);
// define("DeliveryOrderB2B", 64);
// define("ArchiveOrderB2B", 65);
// define("ProductSoldCompositions", 66);

// define("SummaryDeliveryB2B", 68);

// define("MUTASIMASUK", 70);
// define("MUTASIKELUAR", 71);
// define("INVENTORY", 72);

// define("MUTASIMASUK_COMP", 74);
// define("MUTASIKELUAR_COMP", 75);
// define("INVENTORY_COMP", 76);

// define("DataUser", 78);
// define("UserGroup", 79);
// define("GroupAkses", 80);
// define("statusToko", 81);

// 	define("Supplier", 2);
// define("Taxes", 3);
// define("KategoriBiaya", 4);
// define("JenisBiaya", 5);
// define("mst_COA", 6);
// define("COA", 7);
// define("SettingAkun", 8);

// define("BiayaOperasional", 10);	
// define("cashReceipt", 11);	

// define("TaxesRpt", 13);	

// define("Address", 15);
// define("Category", 16);
// define("Colour", 17);
// define("Keranjang", 18);
// define("Dropshipper", 19);
// define("ExpeditionCategory",20);
// define("Expedition", 21);
// define("Products", 22);
// define("ProductsComposition", 23);

// define("ImportCAMOU", 25);
// define("PreSALES", 26);
// define("ImportCredit", 27);
// define("PreSALESCredit", 28);

// define("DropshipperDeposit", 30);
// define("DepositTransaction", 31);
// define("OnlineSales", 32);
// define("OnlineCredit",33);
// define("PendingOrder", 34);
// define("CancelOrder", 35);
// define("OnlineDelivery",36);
// define("OnlineBackDate",37);
// define("ArchiveOrder", 38);
// define("OnlineReturn", 39);
// define("ReturnConfirmed", 40);

// define("OnlineSummary", 42);
// define("SummaryCash", 43);
// define("SummaryCredit", 44);
// define("DropshipperStatistik", 45);
// define("SalesOnlineDropshipper",46);
// define("UnpaidOnline", 47);
// define("Bill", 48);
// define("rptexpedition", 49);
// define("PrintOrder", 50);
// define("ProductSold", 51);
// define("Omset", 52);
// define("TroubleOrder", 53);

// define("ImportCustomerProduct", 55);

// define("CompositionProducts", 57);
// define("B2BProducts", 58);
// define("B2BProductsGroup", 59);
// define("B2BCustomer", 60);
// define("B2BExpedition",61);
// define("B2BSalesman", 62);

// define("AddSalesB2B", 64);
// define("SalesB2B", 65);
// define("ConfirmedSales", 66);
// define("DeliveryOrderB2B", 67);
// define("ArchiveOrderB2B", 68);
// define("ProductSoldCompositions", 69);

// define("SummaryDeliveryB2B", 71);

// define("MUTASIMASUK", 73);
// define("MUTASIKELUAR", 74);
// define("INVENTORY", 75);

// define("MUTASIMASUK_COMP", 77);
// define("MUTASIKELUAR_COMP", 78);
// define("INVENTORY_COMP", 79);

// define("DataUser", 81);
// define("UserGroup", 82);
// define("GroupAkses", 83);
// define("statusToko", 84);

// define("Supplier", 2);
// define("Taxes", 3);
// define("KategoriBiaya", 4);
// define("JenisBiaya", 5);
// define("mst_COA", 6);
// define("COA", 7);
// define("SettingAkun", 8);

// define("BiayaOperasional", 10);	
// define("cashReceipt", 11);	

// define("TaxesRpt", 13);	

// define("Address", 15);
// define("Category", 16);
// define("Colour", 17);
// define("Keranjang", 18);
// define("Dropshipper", 19);
// define("ExpeditionCategory",20);
// define("Expedition", 21);
// define("Products", 22);
// define("ProductsComposition", 23);

// define("ImportCAMOU", 25);
// define("PreSALES", 26);
// define("ImportCredit", 27);
// define("PreSALESCredit", 28);

// define("DropshipperDeposit", 30);
// define("DepositTransaction", 31);
// define("OnlineSales", 32);
// define("OnlineCredit",33);
// define("PendingOrder", 34);
// define("CancelOrder", 35);
// define("OnlineDelivery",36);
// define("OnlineBackDate",37);
// define("ArchiveOrder", 38);
// define("OnlineReturn", 39);
// define("ReturnConfirmed", 40);

// define("OnlineSummary", 42);
// define("SummaryCash", 43);
// define("SummaryCredit", 44);
// define("DropshipperStatistik", 45);
// define("SalesOnlineDropshipper",46);
// define("UnpaidOnline", 47);
// define("Bill", 48);
// define("rptexpedition", 49);
// define("PrintOrder", 50);
// define("ProductSold", 51);
// define("Omset", 52);
// define("TroubleOrder", 53);

// define("ImportCustomerProduct", 55);

// define("CompositionProducts", 57);
// define("B2BProducts", 58);
// define("B2BProductsGroup", 59);
// define("B2BCustomer", 60);
// define("B2BExpedition",61);
// define("B2BSalesman", 62);

// define("AddSalesB2B", 64);
// define("SalesB2B", 65);
// define("ConfirmedSales", 66);
// define("DeliveryOrderB2B", 67);
// define("ArchiveOrderB2B", 68);
// define("ProductSoldCompositions", 69);

// define("SummaryDeliveryB2B", 71);
// define("stb2bdo", 72);
// define("b2bsorpt", 73);

// define("MUTASIMASUK", 75);
// define("MUTASIKELUAR", 76);
// define("INVENTORY", 77);

// define("MUTASIMASUK_COMP", 79);
// define("MUTASIKELUAR_COMP", 80);
// define("INVENTORY_COMP", 81);

// define("DataUser", 83);
// define("UserGroup", 84);
// define("GroupAkses", 85);
// define("statusToko", 86);

// define("Supplier", 2);
// define("Taxes", 3);
// define("KategoriBiaya", 4);
// define("JenisBiaya", 5);
// define("mst_COA", 6);
// define("COA", 7);
// define("SettingAkun", 8);

// define("BiayaOperasional", 10);	
// define("cashReceipt", 11);	
// define("ImportMutation", 12);	
// define("prebank", 13);	
// define("bank", 14);	

// define("TaxesRpt", 16);	

// define("Address", 18);
// define("Category", 19);
// define("Colour", 20);
// define("Keranjang", 21);
// define("Dropshipper", 22);
// define("ExpeditionCategory",23);
// define("Expedition", 24);
// define("Products", 25);
// define("ProductsComposition", 26);

// define("ImportCAMOU", 28);
// define("PreSALES", 29);
// define("ImportCredit", 30);
// define("PreSALESCredit", 31);

// define("DropshipperDeposit", 33);
// define("DepositTransaction", 34);
// define("OnlineSales", 35);
// define("OnlineCredit",36);
// define("PendingOrder", 37);
// define("CancelOrder", 38);
// define("OnlineDelivery",39);
// define("OnlineBackDate",40);
// define("ArchiveOrder", 41);
// define("OnlineReturn", 42);
// define("ReturnConfirmed", 43);

// define("OnlineSummary", 45);
// define("SummaryCash", 46);
// define("SummaryCredit", 47);
// define("DropshipperStatistik", 48);
// define("SalesOnlineDropshipper",49);
// define("UnpaidOnline", 50);
// define("Bill", 51);
// define("rptexpedition", 52);
// define("PrintOrder", 53);
// define("ProductSold", 54);
// define("Omset", 55);
// define("TroubleOrder", 56);

// define("ImportCustomerProduct", 58);

// define("CompositionProducts", 60);
// define("B2BProducts", 61);
// define("B2BProductsGroup", 62);
// define("B2BCustomer", 63);
// define("B2BExpedition",64);
// define("B2BSalesman", 65);

// define("AddSalesB2B", 67);
// define("SalesB2B", 68);
// define("ConfirmedSales", 69);
// define("DeliveryOrderB2B", 70);
// define("ArchiveOrderB2B", 71);
// define("ProductSoldCompositions", 72);

// define("SummaryDeliveryB2B", 74);
// define("stb2bdo", 75);
// define("b2bsorpt", 76);

// define("MUTASIMASUK", 78);
// define("MUTASIKELUAR", 79);
// define("INVENTORY", 80);

// define("MUTASIMASUK_COMP", 82);
// define("MUTASIKELUAR_COMP", 83);
// define("INVENTORY_COMP", 84);

// define("DataUser", 86);
// define("UserGroup", 87);
// define("GroupAkses", 88);
// define("statusToko", 89);


// define("Supplier", 2);
// define("Taxes", 3);
// define("KategoriBiaya", 4);
// define("JenisBiaya", 5);
// define("mst_COA", 6);
// define("COA", 7);
// define("SettingAkun", 8);

// define("BiayaOperasional", 10);		
// define("ImportMutation", 11);	
// define("CRTertunda", 12);	
// define("PaymentCheck", 13);	

// define("TaxesRpt", 15);	
// define("cashReceipt", 16);

// define("Address", 18);
// define("Category", 19);
// define("Colour", 20);
// define("Keranjang", 21);
// define("Dropshipper", 22);
// define("ExpeditionCategory",23);
// define("Expedition", 24);
// define("Products", 25);
// define("ProductsComposition", 26);

// define("ImportCAMOU", 28);
// define("PreSALES", 29);
// define("ImportCredit", 30);
// define("PreSALESCredit", 31);

// define("DropshipperDeposit", 33);
// define("DepositTransaction", 34);
// define("OnlineSales", 35);
// define("OnlineCredit",36);
// define("PendingOrder", 37);
// define("CancelOrder", 38);
// define("OnlineDelivery",39);
// define("OnlineBackDate",40);
// define("ArchiveOrder", 41);
// define("OnlineReturn", 42);
// define("ReturnConfirmed", 43);

// define("OnlineSummary", 45);
// define("SummaryCash", 46);
// define("SummaryCredit", 47);
// define("DropshipperStatistik", 48);
// define("SalesOnlineDropshipper",49);
// define("UnpaidOnline", 50);
// define("Bill", 51);
// define("rptexpedition", 52);
// define("PrintOrder", 53);
// define("ProductSold", 54);
// define("Omset", 55);
// define("TroubleOrder", 56);

// define("ImportCustomerProduct", 58);

// define("CompositionProducts", 60);
// define("B2BProducts", 61);
// define("B2BProductsGroup", 62);
// define("B2BCustomer", 63);
// define("B2BExpedition",64);
// define("B2BSalesman", 65);

// define("AddSalesB2B", 67);
// define("SalesB2B", 68);
// define("ConfirmedSales", 69);
// define("DeliveryOrderB2B", 70);
// define("ArchiveOrderB2B", 71);

// define("SummaryDeliveryB2B", 73);
// define("stb2bdo", 74);
// define("b2bsorpt", 75);
// define("ProductSoldCompositions", 76);

// define("MUTASIMASUK", 78);
// define("MUTASIKELUAR", 79);
// define("INVENTORY", 80);

// define("MUTASIMASUK_COMP", 82);
// define("MUTASIKELUAR_COMP", 83);
// define("INVENTORY_COMP", 84);

// define("DataUser", 86);
// define("UserGroup", 87);
// define("GroupAkses", 88);
// define("statusToko", 89);

// 	define("laporanperiodeolnb2b", 2);

// define("Supplier", 4);
// define("Taxes", 5);
// define("KategoriBiaya", 6);
// define("JenisBiaya", 7);
// define("mst_COA", 8);
// define("COA", 9);
// define("SettingAkun", 10);

// define("BiayaOperasional", 12);		
// define("ImportMutation", 13);	
// define("CRTertunda", 14);	
// define("PaymentCheck", 15);	

// define("TaxesRpt", 17);	
// define("cashReceipt", 18);

// define("settingtunjangan", 20);
// define("departemen", 21);
// define("karyawan", 22);

// define("wages", 24);
// define("income", 25);
// define("deduction", 26);

// define("Address", 28);
// define("Category", 29);
// define("Colour", 30);
// define("Keranjang", 31);
// define("Dropshipper", 32);
// define("ExpeditionCategory",33);
// define("Expedition", 34);
// define("Products", 35);
// define("ProductsComposition", 36);

// define("ImportCAMOU", 38);
// define("PreSALES", 39);
// define("ImportCredit", 40);
// define("PreSALESCredit", 41);

// define("DropshipperDeposit", 43);
// define("DepositTransaction", 44);
// define("OnlineSales", 45);
// define("OnlineCredit",46);
// define("PendingOrder", 47);
// define("CancelOrder", 48);
// define("OnlineDelivery",49);
// define("OnlineBackDate",50);
// define("ArchiveOrder", 51);
// define("OnlineReturn", 52);
// define("ReturnConfirmed", 53);

// define("OnlineSummary", 55);
// define("SummaryCash", 56);
// define("SummaryCredit", 57);
// define("DropshipperStatistik", 58);
// define("SalesOnlineDropshipper",59);
// define("UnpaidOnline", 60);
// define("Bill", 61);
// define("rptexpedition", 62);
// define("PrintOrder", 63);
// define("ProductSold", 64);
// define("Omset", 65);
// define("TroubleOrder", 66);

// define("ImportCustomerProduct", 68);

// define("CompositionProducts", 70);
// define("B2BProducts", 71);
// define("B2BProductsGroup", 72);
// define("B2BCustomer", 73);
// define("B2BExpedition",74);
// define("B2BSalesman", 75);

// define("AddSalesB2B", 77);
// define("SalesB2B", 78);
// define("ConfirmedSales", 79);
// define("DeliveryOrderB2B", 80);
// define("ArchiveOrderB2B", 81);

// define("SummaryDeliveryB2B", 83);
// define("stb2bdo", 84);
// define("b2bsorpt", 85);
// define("b2bcomp", 86);

// define("MUTASIMASUK", 88);
// define("MUTASIKELUAR", 89);
// define("INVENTORY", 90);

// define("MUTASIMASUK_COMP", 92);
// define("MUTASIKELUAR_COMP", 93);
// define("INVENTORY_COMP", 94);

// define("DataUser", 96);
// define("UserGroup", 97);
// define("GroupAkses", 98);
// define("statusToko", 99);

	//define control panel
define("laporanperiodeolnb2b", 2);

define("Supplier", 4);
define("Taxes", 5);
define("KategoriBiaya", 6);
define("JenisBiaya", 7);
define("mst_COA", 8);
define("COA", 9);
define("SettingAkun", 10);

define("BiayaOperasional", 12);		
define("ImportMutation", 13);	
define("CRTertunda", 14);	
define("PaymentCheck", 15);	

define("TaxesRpt", 17);	
define("cashReceipt", 18);

define("departemen", 20);
define("jabatan", 21);
define("pendapatan", 22);
define("potongan", 23);
define("karyawan", 24);

define("penggajian", 26);
define("transpendapatan", 27);
define("transpotongan", 28);

define("Address", 30);
define("Category", 31);
define("Colour", 32);
define("Keranjang", 33);
define("Dropshipper", 34);
define("ExpeditionCategory",35);
define("Expedition", 36);
define("Products", 37);
define("ProductsComposition", 38);

define("ImportCAMOU", 40);
define("PreSALES", 41);
define("ImportCredit", 42);
define("PreSALESCredit", 43);

define("DropshipperDeposit", 45);
define("DepositTransaction", 46);
define("OnlineSales", 47);
define("OnlineCredit",48);
define("PendingOrder", 49);
define("CancelOrder", 50);
define("OnlineDelivery",51);
define("OnlineBackDate",52);
define("ArchiveOrder", 53);
define("OnlineReturn", 54);
define("ReturnConfirmed", 55);

define("OnlineSummary", 57);
define("SummaryCash", 58);
define("SummaryCredit", 59);
define("DropshipperStatistik", 60);
define("SalesOnlineDropshipper",61);
define("UnpaidOnline", 62);
define("Bill", 63);
define("rptexpedition", 64);
define("PrintOrder", 65);
define("ProductSold", 66);
define("Omset", 67);
define("TroubleOrder", 68);

define("ImportCustomerProduct", 70);

define("CompositionProducts", 72);
define("B2BProducts", 73);
define("B2BProductsGroup", 74);
define("B2BCustomer", 75);
define("B2BExpedition",76);
define("B2BSalesman", 77);

define("AddSalesB2B", 79);
define("SalesB2B", 80);
define("ConfirmedSales", 81);
define("DeliveryOrderB2B", 82);
define("ArchiveOrderB2B", 83);

define("SummaryDeliveryB2B", 85);
define("stb2bdo", 86);
define("b2bsorpt", 87);
define("b2bcomp", 88);

define("MUTASIMASUK", 90);
define("MUTASIKELUAR", 91);
define("INVENTORY", 92);

define("MUTASIMASUK_COMP", 94);
define("MUTASIKELUAR_COMP", 95);
define("INVENTORY_COMP", 96);

define("DataUser", 98);
define("UserGroup", 99);
define("GroupAkses", 100);
define("statusToko", 101);

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
