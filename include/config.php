<?php
	session_start();	
	define('_HOST', 'localhost');
	define('_USER', 'app');
	define('_PASS', 'Aaaa1234');
	define('_DBSE', 'cams_db2022');
	
	$url = 'http://'.$_SERVER['HTTP_HOST'];

	define('BASE_URL', $url.'/');

	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	
	if(!isset($_SESSION['cams_logged_in']) && "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" != BASE_URL.'login.php')
		header('location: login.php');
	
	$db = new PDO('mysql:host='._HOST.';dbname='._DBSE.';charset=utf8', _USER, _PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //Sengaja gk di tutup tag PHP nya
	// define("laporanperiodeolnb2b", 2);

	// define("Supplier", 4);
	// define("Taxes", 5);
	// define("KategoriBiaya", 6);
	// define("JenisBiaya", 7);
	// define("mst_COA", 8);
	// define("SettingAkun", 9);

	// define("JurnalManual", 11);		
	// define("BiayaOperasional", 12);		
	// define("ImportMutation", 13);	
	// define("CRTertunda", 14);	
	// define("PaymentCheck", 15);	
	// define("TutupBuku", 16);	

	// define("TaxesRpt", 18);	
	// define("cashReceipt", 19);
	// define("jurnal", 20);
	// define("bukubesar", 21);

	// define("departemen", 23);
	// define("jabatan", 24);
	// define("pendapatan", 25);
	// define("potongan", 26);
	// define("karyawan", 27);

	// define("penggajian", 29);
	// define("transpendapatan", 30);
	// define("transpotongan", 31);

	// define("Address", 33);
	// define("Category", 34);
	// define("Colour", 35);
	// define("Keranjang", 36);
	// define("Dropshipper", 37);
	// define("ExpeditionCategory",38);
	// define("Expedition", 39);
	// define("Products", 40);
	// define("ProductsComposition", 41);

	// define("ImportCAMOU", 43);
	// define("PreSALES", 44);
	// define("ImportCredit", 45);
	// define("PreSALESCredit", 46);

	// define("DropshipperDeposit", 48);
	// define("DepositTransaction", 49);
	// define("OnlineSales", 50);
	// define("OnlineCredit",51);
	// define("PendingOrder", 52);
	// define("CancelOrder", 53);
	// define("OnlineDelivery",54);
	// define("OnlineBackDate",55);
	// define("ArchiveOrder", 56);
	// define("OnlineReturn", 57);
	
	// define("OnlineSummary", 59);
	// define("SummaryCash", 60);
	// define("SummaryCredit", 61);
	// define("DropshipperStatistik", 62);
	// define("SalesOnlineDropshipper",63);
	// define("UnpaidOnline", 64);
	// define("Bill", 65);
	// define("rptexpedition", 66);
	// define("PrintOrder", 67);
	// define("ProductSold", 68);
	// define("Omset", 69);
	// define("TroubleOrder", 70);
	// define("ReturnConfirmed", 71);
	
	// define("ImportCustomerProduct", 73);

	// define("CompositionProducts", 75);
	// define("B2BProducts", 76);
	// define("B2BProductsGroup", 77);
	// define("B2BCustomer", 78);
	// define("B2BExpedition",79);
	// define("B2BSalesman", 80);

	// define("AddSalesB2B", 82);
	// define("SalesB2B", 83);
	// define("ConfirmedSales", 84);
	// define("DeliveryOrderB2B", 85);
	// define("ArchiveOrderB2B", 86);

	// define("SummaryDeliveryB2B", 88);
	// define("stb2bdo", 89);
	// define("b2bsorpt", 90);
	// define("b2bcomp", 91);

	// define("pemohonpo", 93);
	// define("supplier", 94);
	// define("produkpo", 95);

	// define("po", 97);
	// define("poapproval", 98);
	// define("archivepo", 99);

	// define("MUTASIMASUK", 101);
	// define("MUTASIKELUAR", 102);
	// define("INVENTORY", 103);

	// define("MUTASIMASUK_COMP", 105);
	// define("MUTASIKELUAR_COMP", 106);
	// define("INVENTORY_COMP", 107);

	// define("DataUser", 109);
	// define("UserGroup", 110);
	// define("GroupAkses", 111);
	// define("statusToko", 112);

	// define("laporanperiodeolnb2b", 2);

	// define("Supplier", 4);
	// define("Taxes", 5);
	// define("KategoriBiaya", 6);
	// define("JenisBiaya", 7);
	// define("mst_COA", 8);
	// define("SettingAkun", 9);

	// define("JurnalManual", 11);		
	// define("BiayaOperasional", 12);		
	// define("ImportMutation", 13);	
	// define("CRTertunda", 14);	
	// define("PaymentCheck", 15);	
	// define("ReturnConfirmed", 16);
	// define("archivepo", 17);	
	// define("aplist", 18);	
	// define("TutupBuku", 19);	

	// define("TaxesRpt", 21);	
	// define("cashReceipt", 22);
	// define("jurnal", 23);
	// define("bukubesar", 24);

	// define("departemen", 26);
	// define("jabatan", 27);
	// define("pendapatan", 28);
	// define("potongan", 29);
	// define("karyawan", 30);

	// define("penggajian", 32);
	// define("transpendapatan", 33);
	// define("transpotongan", 34);

	// define("Address", 36);
	// define("Category", 37);
	// define("Colour", 38);
	// define("Keranjang", 39);
	// define("Dropshipper", 40);
	// define("ExpeditionCategory", 41);
	// define("Expedition", 42);
	// define("Products", 43);
	// define("ProductsComposition", 44);

	// define("ImportCAMOU", 46);
	// define("PreSALES", 47);
	// define("ImportCredit", 48);
	// define("PreSALESCredit", 49);

	// define("DropshipperDeposit", 51);
	// define("DepositTransaction", 52);
	// define("OnlineSales", 53);
	// define("OnlineCredit",54);
	// define("PendingOrder", 55);
	// define("CancelOrder", 56);
	// define("OnlineDelivery",57);
	// define("OnlineBackDate",58);
	// define("ArchiveOrder", 59);
	// define("OnlineReturn", 60);
	// define("UnpaidOnline", 61);
	
	// define("OnlineSummary", 63);
	// define("SummaryCash", 64);
	// define("SummaryCredit", 65);
	// define("DropshipperStatistik", 66);
	// define("SalesOnlineDropshipper",67);
	// define("Bill", 68);
	// define("rptexpedition", 69);
	// define("PrintOrder", 70);
	// define("ProductSold", 71);
	// define("Omset", 72);
	// define("TroubleOrder", 73);
	
	// define("ImportCustomerProduct", 75);

	// define("CompositionProducts", 77);
	// define("B2BProducts", 78);
	// define("B2BProductsGroup", 79);
	// define("B2BCustomer", 80);
	// define("B2BExpedition",81);
	// define("B2BSalesman", 82);

	// define("AddSalesB2B", 84);
	// define("SalesB2B", 85);
	// define("ConfirmedSales", 86);
	// define("DeliveryOrderB2B", 87);
	// define("ArchiveOrderB2B", 88);

	// define("SummaryDeliveryB2B", 90);
	// define("stb2bdo", 91);
	// define("b2bsorpt", 92);
	// define("b2bcomp", 93);

	// define("pemohonpo", 95);
	// define("supplier", 96);
	// define("produkpo", 97);

	// define("po", 99);
	// define("poapproval", 100);

	// define("MUTASIMASUK", 102);
	// define("MUTASIKELUAR", 103);
	// define("INVENTORY", 104);

	// define("MUTASIMASUK_COMP", 106);
	// define("MUTASIKELUAR_COMP", 107);
	// define("INVENTORY_COMP", 108);

	// define("DataUser", 110);
	// define("UserGroup", 111);
	// define("GroupAkses", 112);
	// define("statusToko", 113);

	// define("laporanperiodeolnb2b", 2);

	// define("Supplier", 4);
	// define("Taxes", 5);
	// define("KategoriBiaya", 6);
	// define("JenisBiaya", 7);
	// define("mst_COA", 8);
	// define("SettingAkun", 9);

	// define("JurnalManual", 11);		
	// define("BiayaOperasional", 12);		
	// define("ImportMutation", 13);	
	// define("CRTertunda", 14);	
	// define("PaymentCheck", 15);	
	// define("ReturnConfirmed", 16);
	// define("archivepo", 17);	
	// define("aplist", 18);	
	// define("TutupBuku", 19);	

	// define("TaxesRpt", 21);	
	// define("cashReceipt", 22);
	// define("jurnal", 23);
	// define("bukubesar", 24);
	// define("arsipjurnal", 25);
	// define("arsipbukubesar", 26);
	
	// define("departemen", 28);
	// define("jabatan", 29);
	// define("pendapatan", 30);
	// define("potongan", 31);
	// define("karyawan", 32);

	// define("penggajian", 34);
	// define("transpendapatan", 35);
	// define("transpotongan", 36);

	// define("Address", 38);
	// define("Category", 39);
	// define("Colour", 40);
	// define("Keranjang", 41);
	// define("Dropshipper", 42);
	// define("ExpeditionCategory", 43);
	// define("Expedition", 44);
	// define("Products", 45);
	// define("ProductsComposition", 46);

	// define("ImportCAMOU", 48);
	// define("PreSALES", 49);
	// define("ImportCredit", 50);
	// define("PreSALESCredit", 51);

	// define("DropshipperDeposit", 53);
	// define("DepositTransaction", 54);
	// define("OnlineSales", 55);
	// define("OnlineCredit",56);
	// define("PendingOrder", 57);
	// define("CancelOrder", 58);
	// define("OnlineDelivery",59);
	// define("OnlineBackDate",60);
	// define("ArchiveOrder", 61);
	// define("OnlineReturn", 62);
	// define("UnpaidOnline", 63);
	
	// define("OnlineSummary", 65);
	// define("SummaryCash", 66);
	// define("SummaryCredit", 67);
	// define("DropshipperStatistik", 68);
	// define("SalesOnlineDropshipper",69);
	// define("Bill", 70);
	// define("rptexpedition", 71);
	// define("PrintOrder", 72);
	// define("ProductSold", 73);
	// define("Omset", 74);
	// define("TroubleOrder", 75);
	
	// define("ImportCustomerProduct", 77);

	// define("CompositionProducts", 79);
	// define("B2BProducts", 80);
	// define("B2BProductsGroup", 81);
	// define("B2BCustomer", 82);
	// define("B2BExpedition",83);
	// define("B2BSalesman", 84);

	// define("AddSalesB2B", 86);
	// define("SalesB2B", 87);
	// define("ConfirmedSales", 88);
	// define("DeliveryOrderB2B", 89);
	// define("ArchiveOrderB2B", 90);

	// define("SummaryDeliveryB2B", 92);
	// define("stb2bdo", 93);
	// define("b2bsorpt", 94);
	// define("b2bcomp", 95);

	// define("pemohonpo", 97);
	// define("supplier", 98);
	// define("produkpo", 99);

	// define("po", 101);
	// define("poapproval", 102);

	// define("MUTASIMASUK", 104);
	// define("MUTASIKELUAR", 105);
	// define("INVENTORY", 106);

	// define("MUTASIMASUK_COMP", 108);
	// define("MUTASIKELUAR_COMP", 109);
	// define("INVENTORY_COMP", 110);

	// define("DataUser", 112);
	// define("UserGroup", 113);
	// define("GroupAkses", 114);
	// define("statusToko", 115);

	define("laporanperiodeolnb2b", 2);

	define("Supplier", 4);
	define("Taxes", 5);
	define("KategoriBiaya", 6);
	define("JenisBiaya", 7);
	define("mst_COA", 8);
	define("SettingAkun", 9);

	define("JurnalManual", 11);		
	define("BiayaOperasional", 12);		
	define("ImportMutation", 13);	
	define("CRTertunda", 14);	
	define("PaymentCheck", 15);	
	define("ReturnConfirmed", 16);
	define("archivepo", 17);	
	define("aplist", 18);	
	define("TutupBuku", 19);	

	define("TaxesRpt", 21);	
	define("cashReceipt", 22);
	define("jurnal", 23);
	define("bukubesar", 24);
	define("arsipjurnal", 25);
	define("arsipbukubesar", 26);
	
	define("departemen", 28);
	define("jabatan", 29);
	define("pendapatan", 30);
	define("potongan", 31);
	define("karyawan", 32);

	define("penggajian", 34);
	define("transpendapatan", 35);
	define("transpotongan", 36);

	define("Address", 38);
	define("Category", 39);
	define("Colour", 40);
	define("Keranjang", 41);
	define("Dropshipper", 42);
	define("ExpeditionCategory", 43);
	define("Expedition", 44);
	define("Products", 45);
	define("ProductsComposition", 46);

	define("ImportCAMOU", 48);
	define("PreSALES", 49);
	define("ImportCredit", 50);
	define("PreSALESCredit", 51);

	define("DropshipperDeposit", 53);
	define("DepositTransaction", 54);
	define("OnlineSales", 55);
	define("OnlineCredit",56);
	define("PendingOrder", 57);
	define("CancelOrder", 58);
	define("OnlineDelivery",59);
	define("OnlineBackDate",60);
	define("ArchiveOrder", 61);
	define("OnlineReturn", 62);
	define("UnpaidOnline", 63);
	
	define("OnlineSummary", 65);
	define("SummaryCash", 66);
	define("SummaryCredit", 67);
	define("DropshipperStatistik", 68);
	define("SalesOnlineDropshipper",69);
	define("Bill", 70);
	define("rptexpedition", 71);
	define("PrintOrder", 72);
	define("ProductSold", 73);
	define("Omset", 74);
	define("TroubleOrder", 75);
	
	define("ImportCustomerProduct", 77);

	define("CompositionProducts", 79);
	define("B2BProducts", 80);
	define("B2BProductsGroup", 81);
	define("B2BCustomer", 82);
	define("B2BExpedition",83);
	define("B2BSalesman", 84);

	define("AddSalesB2B", 86);
	define("SalesB2B", 87);
	define("ConfirmedSales", 88);
	define("DeliveryOrderB2B", 89);
	define("ArchiveOrderB2B", 90);
	define("trb2breturn", 91);
	define("arb2b", 92);

	define("SummaryDeliveryB2B", 94);
	define("stb2bdo", 95);
	define("b2bsorpt", 96);
	define("b2bcomp", 97);

	define("pemohonpo", 99);
	define("supplier", 100);
	define("produkpo", 101);

	define("po", 103);
	define("poapproval", 104);

	define("MUTASIMASUK", 106);
	define("MUTASIKELUAR", 107);
	define("INVENTORY", 108);

	define("MUTASIMASUK_COMP", 110);
	define("MUTASIKELUAR_COMP", 111);
	define("INVENTORY_COMP", 112);

	define("DataUser", 114);
	define("UserGroup", 115);
	define("GroupAkses", 116);
	define("statusToko", 117);

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
