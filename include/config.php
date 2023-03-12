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
	define("TutupBuku", 16);	

	define("TaxesRpt", 18);	
	define("cashReceipt", 19);
	define("jurnal", 20);
	define("bukubesar", 21);

	define("departemen", 23);
	define("jabatan", 24);
	define("pendapatan", 25);
	define("potongan", 26);
	define("karyawan", 27);

	define("penggajian", 29);
	define("transpendapatan", 30);
	define("transpotongan", 31);

	define("Address", 33);
	define("Category", 34);
	define("Colour", 35);
	define("Keranjang", 36);
	define("Dropshipper", 37);
	define("ExpeditionCategory",38);
	define("Expedition", 39);
	define("Products", 40);
	define("ProductsComposition", 41);

	define("ImportCAMOU", 43);
	define("PreSALES", 44);
	define("ImportCredit", 45);
	define("PreSALESCredit", 46);

	define("DropshipperDeposit", 48);
	define("DepositTransaction", 49);
	define("OnlineSales", 50);
	define("OnlineCredit",51);
	define("PendingOrder", 52);
	define("CancelOrder", 53);
	define("OnlineDelivery",54);
	define("OnlineBackDate",55);
	define("ArchiveOrder", 56);
	define("OnlineReturn", 57);
	
	define("OnlineSummary", 59);
	define("SummaryCash", 60);
	define("SummaryCredit", 61);
	define("DropshipperStatistik", 62);
	define("SalesOnlineDropshipper",63);
	define("UnpaidOnline", 64);
	define("Bill", 65);
	define("rptexpedition", 66);
	define("PrintOrder", 67);
	define("ProductSold", 68);
	define("Omset", 69);
	define("TroubleOrder", 70);
	define("ReturnConfirmed", 71);
	
	define("ImportCustomerProduct", 73);

	define("CompositionProducts", 75);
	define("B2BProducts", 76);
	define("B2BProductsGroup", 77);
	define("B2BCustomer", 78);
	define("B2BExpedition",79);
	define("B2BSalesman", 80);

	define("AddSalesB2B", 82);
	define("SalesB2B", 83);
	define("ConfirmedSales", 84);
	define("DeliveryOrderB2B", 85);
	define("ArchiveOrderB2B", 86);

	define("SummaryDeliveryB2B", 88);
	define("stb2bdo", 89);
	define("b2bsorpt", 90);
	define("b2bcomp", 91);

	define("pemohonpo", 93);
	define("supplier", 94);
	define("produkpo", 95);

	define("po", 97);
	define("poapproval", 98);
	define("archivepo", 99);

	define("MUTASIMASUK", 101);
	define("MUTASIKELUAR", 102);
	define("INVENTORY", 103);

	define("MUTASIMASUK_COMP", 105);
	define("MUTASIKELUAR_COMP", 106);
	define("INVENTORY_COMP", 107);

	define("DataUser", 109);
	define("UserGroup", 110);
	define("GroupAkses", 111);
	define("statusToko", 112);

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
