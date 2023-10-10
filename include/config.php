<?php
	session_start();	
	define('_HOST', 'localhost');
	define('_USER', 'root');
	define('_PASS', '');
	define('_DBSE', 'cams_db2022');
	
	define('BASE_URL', 'http://localhost/cams/');

	define('IS_AJAX', isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
	
	if(!isset($_SESSION['cams_logged_in']) && "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" != BASE_URL.'login.php')
		header('location: login.php');
	
	$db = new PDO('mysql:host='._HOST.';dbname='._DBSE.';charset=utf8', _USER, _PASS);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

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
	define("aplist", 16);	
	define("ReturnConfirmed", 17);
	define("arb2blist", 18);
	define("penyusutan", 19);
	define("TutupBuku", 20);	

	define("TaxesRpt", 22);	
	define("cashReceipt", 23);
	define("jurnal", 24);
	define("bukubesar", 25);
	define("arsipjurnal", 26);
	define("arsipbukubesar", 27);
	define("oroverall", 28);
	define("arsipaplist", 29);
	define("arsipapolnlist", 30);
	define("arsipb2blist", 31);
	define("laporanpenyusutan", 32);
	
	define("departemen", 34);
	define("jabatan", 35);
	define("pendapatan", 36);
	define("potongan", 37);
	define("karyawan", 38);

	define("penggajian", 40);
	define("transpendapatan", 41);
	define("transpotongan", 42);

	define("Address", 44);
	define("Category", 45);
	define("Colour", 46);
	define("Keranjang", 47);
	define("Dropshipper", 48);
	define("ExpeditionCategory", 49);
	define("Expedition", 50);
	define("Products", 51);
	define("ProductsComposition", 52);

	define("ImportCAMOU", 54);
	define("PreSALES", 55);
	define("ImportCredit", 56);
	define("PreSALESCredit", 57);

	define("DropshipperDeposit", 59);
	define("DepositTransaction", 60);
	define("OnlineSales", 61);
	define("OnlineCredit", 62);
	define("rptexpedition", 63);
	define("OnlineDelivery",64);
	define("ArchiveOrder", 65);
	define("PendingOrder", 66);
	define("CancelOrder", 67);
	define("OnlineReturn", 68);
	define("UnpaidOnline", 69);
	
	define("OnlineSummary", 71);
	define("SummaryCash", 72);
	define("SummaryCredit", 73);
	define("DropshipperStatistik", 74);
	define("SalesOnlineDropshipper", 75);
	define("Bill", 76);
	define("PrintOrder", 77);
	define("ProductSold", 78);
	define("Omset", 79);
	define("TroubleOrder", 80);
	define("OnlineBackDate",81);
	
	define("ImportCustomerProduct", 83);

	define("CompositionProducts", 85);
	define("B2BProducts", 86);
	define("B2BProductsGroup", 87);
	define("B2BCustomer", 88);
	define("B2BExpedition",89);
	define("B2BSalesman", 90);

	define("AddSalesB2B", 92);
	define("SalesB2B", 93);
	define("ConfirmedSales", 94);
	define("DeliveryOrderB2B", 95);
	define("trb2bpiutangPembayaran", 96);
	define("trb2bkomisi", 97);
	define("trb2breturn", 98);
	define("arb2b", 99);

	define("SummaryDeliveryB2B", 101);
	define("stb2bdo", 102);
	define("b2bsorpt", 103);
	define("ArchiveOrderB2B", 104);
	define("b2bcomp", 105);
	define("b2bretur", 106);
	define("b2bcompretur", 107);

	define("pemohonpo", 109);
	define("supplier", 110);
	define("produkpo", 111);

	define("po", 113);
	define("poapproval", 114);
	define("archivepo", 115);

	define("MUTASIMASUK", 117);
	define("MUTASIKELUAR", 118);
	define("INVENTORY", 119);

	define("MUTASIMASUK_COMP", 121);
	define("MUTASIKELUAR_COMP", 122);
	define("INVENTORY_COMP", 123);

	define("DataUser", 125);
	define("UserGroup", 126);
	define("GroupAkses", 127);
	define("statusToko", 128);

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
