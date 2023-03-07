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

	define("TaxesRpt", 17);	
	define("cashReceipt", 18);
	define("jurnal", 19);
	define("bukubesar", 20);

	define("departemen", 22);
	define("jabatan", 23);
	define("pendapatan", 24);
	define("potongan", 25);
	define("karyawan", 26);

	define("penggajian", 28);
	define("transpendapatan", 29);
	define("transpotongan", 30);

	define("Address", 32);
	define("Category", 33);
	define("Colour", 34);
	define("Keranjang", 35);
	define("Dropshipper", 36);
	define("ExpeditionCategory",37);
	define("Expedition", 38);
	define("Products", 39);
	define("ProductsComposition", 40);

	define("ImportCAMOU", 42);
	define("PreSALES", 43);
	define("ImportCredit", 44);
	define("PreSALESCredit", 45);

	define("DropshipperDeposit", 47);
	define("DepositTransaction", 48);
	define("OnlineSales", 49);
	define("OnlineCredit",50);
	define("PendingOrder", 51);
	define("CancelOrder", 52);
	define("OnlineDelivery",53);
	define("OnlineBackDate",54);
	define("ArchiveOrder", 55);
	define("OnlineReturn", 56);
	
	define("OnlineSummary", 58);
	define("SummaryCash", 59);
	define("SummaryCredit", 60);
	define("DropshipperStatistik", 61);
	define("SalesOnlineDropshipper",62);
	define("UnpaidOnline", 63);
	define("Bill", 64);
	define("rptexpedition", 65);
	define("PrintOrder", 66);
	define("ProductSold", 67);
	define("Omset", 68);
	define("TroubleOrder", 69);
	define("ReturnConfirmed", 70);
	
	define("ImportCustomerProduct", 72);

	define("CompositionProducts", 74);
	define("B2BProducts", 75);
	define("B2BProductsGroup", 76);
	define("B2BCustomer", 77);
	define("B2BExpedition",78);
	define("B2BSalesman", 79);

	define("AddSalesB2B", 81);
	define("SalesB2B", 82);
	define("ConfirmedSales", 83);
	define("DeliveryOrderB2B", 84);
	define("ArchiveOrderB2B", 85);

	define("SummaryDeliveryB2B", 87);
	define("stb2bdo", 88);
	define("b2bsorpt", 89);
	define("b2bcomp", 90);

	define("pemohonpo", 92);
	define("supplier", 93);
	define("produkpo", 94);

	define("po", 96);
	define("poapproval", 97);
	define("archivepo", 98);

	define("MUTASIMASUK", 100);
	define("MUTASIKELUAR", 101);
	define("INVENTORY", 102);

	define("MUTASIMASUK_COMP", 104);
	define("MUTASIKELUAR_COMP", 105);
	define("INVENTORY_COMP", 106);

	define("DataUser", 108);
	define("UserGroup", 109);
	define("GroupAkses", 110);
	define("statusToko", 111);

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
