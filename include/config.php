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
	// define("trb2breturn", 91);
	// define("arb2b", 92);

	// define("SummaryDeliveryB2B", 94);
	// define("stb2bdo", 95);
	// define("b2bsorpt", 96);
	// define("b2bcomp", 97);

	// define("pemohonpo", 99);
	// define("supplier", 100);
	// define("produkpo", 101);

	// define("po", 103);
	// define("poapproval", 104);

	// define("MUTASIMASUK", 106);
	// define("MUTASIKELUAR", 107);
	// define("INVENTORY", 108);

	// define("MUTASIMASUK_COMP", 110);
	// define("MUTASIKELUAR_COMP", 111);
	// define("INVENTORY_COMP", 112);

	// define("DataUser", 114);
	// define("UserGroup", 115);
	// define("GroupAkses", 116);
	// define("statusToko", 117);

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
	// define("trb2breturn", 91);
	// define("arb2b", 92);

	// define("SummaryDeliveryB2B", 94);
	// define("stb2bdo", 95);
	// define("b2bsorpt", 96);
	// define("b2bcomp", 97);
	// define("b2bretur", 98);
	// define("b2bcompretur", 99);

	// define("pemohonpo", 101);
	// define("supplier", 102);
	// define("produkpo", 10);

	// define("po", 105);
	// define("poapproval", 106);

	// define("MUTASIMASUK", 108);
	// define("MUTASIKELUAR", 109);
	// define("INVENTORY", 110);

	// define("MUTASIMASUK_COMP", 112);
	// define("MUTASIKELUAR_COMP", 113);
	// define("INVENTORY_COMP", 114);

	// define("DataUser", 116);
	// define("UserGroup", 117);
	// define("GroupAkses", 118);
	// define("statusToko", 119);

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
	// define("aplist", 17);	
	// define("TutupBuku", 18);	

	// define("TaxesRpt", 20);	
	// define("cashReceipt", 21);
	// define("jurnal", 22);
	// define("bukubesar", 23);
	// define("arsipjurnal", 24);
	// define("arsipbukubesar", 25);
	
	// define("departemen", 27);
	// define("jabatan", 28);
	// define("pendapatan", 29);
	// define("potongan", 30);
	// define("karyawan", 31);

	// define("penggajian", 33);
	// define("transpendapatan", 34);
	// define("transpotongan", 35);

	// define("Address", 37);
	// define("Category", 38);
	// define("Colour", 39);
	// define("Keranjang", 40);
	// define("Dropshipper", 41);
	// define("ExpeditionCategory", 42);
	// define("Expedition", 43);
	// define("Products", 44);
	// define("ProductsComposition", 45);

	// define("ImportCAMOU", 47);
	// define("PreSALES", 48);
	// define("ImportCredit", 49);
	// define("PreSALESCredit", 50);

	// define("DropshipperDeposit", 52);
	// define("DepositTransaction", 53);
	// define("OnlineSales", 54);
	// define("OnlineCredit",55);
	// define("PendingOrder", 56);
	// define("CancelOrder", 57);
	// define("OnlineDelivery",58);
	// define("OnlineBackDate",59);
	// define("ArchiveOrder", 60);
	// define("OnlineReturn", 61);
	// define("UnpaidOnline", 62);
	
	// define("OnlineSummary", 64);
	// define("SummaryCash", 65);
	// define("SummaryCredit", 66);
	// define("DropshipperStatistik", 67);
	// define("SalesOnlineDropshipper",68);
	// define("Bill", 69);
	// define("rptexpedition", 70);
	// define("PrintOrder", 71);
	// define("ProductSold", 72);
	// define("Omset", 73);
	// define("TroubleOrder", 74);
	
	// define("ImportCustomerProduct", 76);

	// define("CompositionProducts", 78);
	// define("B2BProducts", 79);
	// define("B2BProductsGroup", 80);
	// define("B2BCustomer", 81);
	// define("B2BExpedition",82);
	// define("B2BSalesman", 83);

	// define("AddSalesB2B", 85);
	// define("SalesB2B", 86);
	// define("ConfirmedSales", 87);
	// define("DeliveryOrderB2B", 88);
	// define("ArchiveOrderB2B", 89);
	// define("trb2breturn", 90);
	// define("arb2b", 91);

	// define("SummaryDeliveryB2B", 93);
	// define("stb2bdo", 94);
	// define("b2bsorpt", 95);
	// define("b2bcomp", 96);
	// define("b2bretur", 97);
	// define("b2bcompretur", 98);

	// define("pemohonpo", 100);
	// define("supplier", 101);
	// define("produkpo", 102);

	// define("po", 104);
	// define("poapproval", 105);
	// define("archivepo", 106);

	// define("MUTASIMASUK", 108);
	// define("MUTASIKELUAR", 109);
	// define("INVENTORY", 110);

	// define("MUTASIMASUK_COMP", 112);
	// define("MUTASIKELUAR_COMP", 113);
	// define("INVENTORY_COMP", 114);

	// define("DataUser", 116);
	// define("UserGroup", 117);
	// define("GroupAkses", 118);
	// define("statusToko", 119);

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
	// define("aplist", 17);	
	// define("TutupBuku", 18);	

	// define("TaxesRpt", 20);	
	// define("cashReceipt", 21);
	// define("jurnal", 22);
	// define("bukubesar", 23);
	// define("arsipjurnal", 24);
	// define("arsipbukubesar", 25);
	
	// define("departemen", 27);
	// define("jabatan", 28);
	// define("pendapatan", 29);
	// define("potongan", 30);
	// define("karyawan", 31);

	// define("penggajian", 33);
	// define("transpendapatan", 34);
	// define("transpotongan", 35);

	// define("Address", 37);
	// define("Category", 38);
	// define("Colour", 39);
	// define("Keranjang", 40);
	// define("Dropshipper", 41);
	// define("ExpeditionCategory", 42);
	// define("Expedition", 43);
	// define("Products", 44);
	// define("ProductsComposition", 45);

	// define("ImportCAMOU", 47);
	// define("PreSALES", 48);
	// define("ImportCredit", 49);
	// define("PreSALESCredit", 50);

	// define("DropshipperDeposit", 52);
	// define("DepositTransaction", 53);
	// define("OnlineSales", 54);
	// define("OnlineCredit",55);
	// define("PendingOrder", 56);
	// define("CancelOrder", 57);
	// define("OnlineReturn", 58);
	// define("UnpaidOnline", 59);
	
	// define("OnlineSummary", 61);
	// define("SummaryCash", 62);
	// define("SummaryCredit", 63);
	// define("DropshipperStatistik", 64);
	// define("SalesOnlineDropshipper",65);
	// define("Bill", 66);
	// define("rptexpedition", 67);
	// define("PrintOrder", 68);
	// define("ProductSold", 69);
	// define("Omset", 70);
	// define("TroubleOrder", 71);
	// define("OnlineDelivery",72);
	// define("OnlineBackDate",73);
	// define("ArchiveOrder", 74);
	
	// define("ImportCustomerProduct", 76);

	// define("CompositionProducts", 78);
	// define("B2BProducts", 79);
	// define("B2BProductsGroup", 80);
	// define("B2BCustomer", 81);
	// define("B2BExpedition",82);
	// define("B2BSalesman", 83);

	// define("AddSalesB2B", 85);
	// define("SalesB2B", 86);
	// define("ConfirmedSales", 87);
	// define("DeliveryOrderB2B", 88);
	// define("ArchiveOrderB2B", 89);
	// define("trb2breturn", 90);
	// define("arb2b", 91);

	// define("SummaryDeliveryB2B", 93);
	// define("stb2bdo", 94);
	// define("b2bsorpt", 95);
	// define("b2bcomp", 96);
	// define("b2bretur", 97);
	// define("b2bcompretur", 98);

	// define("pemohonpo", 100);
	// define("supplier", 101);
	// define("produkpo", 102);

	// define("po", 104);
	// define("poapproval", 105);
	// define("archivepo", 106);

	// define("MUTASIMASUK", 108);
	// define("MUTASIKELUAR", 109);
	// define("INVENTORY", 110);

	// define("MUTASIMASUK_COMP", 112);
	// define("MUTASIKELUAR_COMP", 113);
	// define("INVENTORY_COMP", 114);

	// define("DataUser", 116);
	// define("UserGroup", 117);
	// define("GroupAkses", 118);
	// define("statusToko", 119);

	
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
	// define("aplist", 17);	
	// define("TutupBuku", 18);	

	// define("TaxesRpt", 20);	
	// define("cashReceipt", 21);
	// define("jurnal", 22);
	// define("bukubesar", 23);
	// define("arsipjurnal", 24);
	// define("arsipbukubesar", 25);
	// define("arsipaplist", 26);
	// define("arsipapolnlist", 27);
	// define("arsipb2blist", 28);
	
	// define("departemen", 30);
	// define("jabatan", 31);
	// define("pendapatan", 32);
	// define("potongan", 33);
	// define("karyawan", 34);

	// define("penggajian", 36);
	// define("transpendapatan", 37);
	// define("transpotongan", 38);

	// define("Address", 40);
	// define("Category", 41);
	// define("Colour", 42);
	// define("Keranjang", 43);
	// define("Dropshipper", 44);
	// define("ExpeditionCategory", 45);
	// define("Expedition", 46);
	// define("Products", 47);
	// define("ProductsComposition", 48);

	// define("ImportCAMOU", 50);
	// define("PreSALES", 51);
	// define("ImportCredit", 52);
	// define("PreSALESCredit", 53);

	// define("DropshipperDeposit", 55);
	// define("DepositTransaction", 56);
	// define("OnlineSales", 57);
	// define("OnlineCredit",58);
	// define("PendingOrder", 59);
	// define("CancelOrder", 60);
	// define("OnlineReturn", 61);
	// define("UnpaidOnline", 62);
	
	// define("OnlineSummary", 64);
	// define("SummaryCash", 65);
	// define("SummaryCredit", 66);
	// define("DropshipperStatistik", 67);
	// define("SalesOnlineDropshipper",68);
	// define("Bill", 69);
	// define("rptexpedition", 70);
	// define("PrintOrder", 71);
	// define("ProductSold", 72);
	// define("Omset", 73);
	// define("TroubleOrder", 74);
	// define("OnlineDelivery",75);
	// define("OnlineBackDate",76);
	// define("ArchiveOrder", 77);
	
	// define("ImportCustomerProduct", 79);

	// define("CompositionProducts", 81);
	// define("B2BProducts", 82);
	// define("B2BProductsGroup", 83);
	// define("B2BCustomer", 84);
	// define("B2BExpedition",85);
	// define("B2BSalesman", 86);

	// define("AddSalesB2B", 88);
	// define("SalesB2B", 89);
	// define("ConfirmedSales", 90);
	// define("DeliveryOrderB2B", 91);
	// define("ArchiveOrderB2B", 92);
	// define("trb2breturn", 93);
	// define("arb2b", 94);
	// define("arb2blist", 95);

	// define("SummaryDeliveryB2B", 97);
	// define("stb2bdo", 98);
	// define("b2bsorpt", 99);
	// define("b2bcomp", 100);
	// define("b2bretur", 101);
	// define("b2bcompretur", 102);

	// define("pemohonpo", 104);
	// define("supplier", 105);
	// define("produkpo", 106);

	// define("po", 108);
	// define("poapproval", 109);
	// define("archivepo", 110);

	// define("MUTASIMASUK", 112);
	// define("MUTASIKELUAR", 113);
	// define("INVENTORY", 114);

	// define("MUTASIMASUK_COMP", 116);
	// define("MUTASIKELUAR_COMP", 117);
	// define("INVENTORY_COMP", 118);

	// define("DataUser", 120);
	// define("UserGroup", 121);
	// define("GroupAkses", 122);
	// define("statusToko", 123);

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
	// define("aplist", 17);	
	// define("arb2blist", 18);
	// define("TutupBuku", 19);	

	// define("TaxesRpt", 21);	
	// define("cashReceipt", 22);
	// define("jurnal", 23);
	// define("bukubesar", 24);
	// define("arsipjurnal", 25);
	// define("arsipbukubesar", 26);
	// define("arsipaplist", 27);
	// define("arsipapolnlist", 28);
	// define("arsipb2blist", 29);
	
	// define("departemen", 31);
	// define("jabatan", 32);
	// define("pendapatan", 33);
	// define("potongan", 34);
	// define("karyawan", 35);

	// define("penggajian", 37);
	// define("transpendapatan", 38);
	// define("transpotongan", 39);

	// define("Address", 41);
	// define("Category", 42);
	// define("Colour", 43);
	// define("Keranjang", 44);
	// define("Dropshipper", 45);
	// define("ExpeditionCategory", 46);
	// define("Expedition", 47);
	// define("Products", 48);
	// define("ProductsComposition", 49);

	// define("ImportCAMOU", 51);
	// define("PreSALES", 52);
	// define("ImportCredit", 53);
	// define("PreSALESCredit", 54);

	// define("DropshipperDeposit", 56);
	// define("DepositTransaction", 57);
	// define("OnlineSales", 58);
	// define("OnlineCredit",59);
	// define("PendingOrder", 60);
	// define("CancelOrder", 61);
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
	// define("OnlineDelivery",76);
	// define("OnlineBackDate",77);
	// define("ArchiveOrder", 78);
	
	// define("ImportCustomerProduct", 80);

	// define("CompositionProducts", 82);
	// define("B2BProducts", 83);
	// define("B2BProductsGroup", 84);
	// define("B2BCustomer", 85);
	// define("B2BExpedition",86);
	// define("B2BSalesman", 87);

	// define("AddSalesB2B", 89);
	// define("SalesB2B", 90);
	// define("ConfirmedSales", 91);
	// define("DeliveryOrderB2B", 92);
	// define("ArchiveOrderB2B", 93);
	// define("trb2breturn", 94);
	// define("arb2b", 95);

	// define("SummaryDeliveryB2B", 97);
	// define("stb2bdo", 98);
	// define("b2bsorpt", 99);
	// define("b2bcomp", 100);
	// define("b2bretur", 101);
	// define("b2bcompretur", 102);

	// define("pemohonpo", 104);
	// define("supplier", 105);
	// define("produkpo", 106);

	// define("po", 108);
	// define("poapproval", 109);
	// define("archivepo", 110);

	// define("MUTASIMASUK", 112);
	// define("MUTASIKELUAR", 113);
	// define("INVENTORY", 114);

	// define("MUTASIMASUK_COMP", 116);
	// define("MUTASIKELUAR_COMP", 117);
	// define("INVENTORY_COMP", 118);

	// define("DataUser", 120);
	// define("UserGroup", 121);
	// define("GroupAkses", 122);
	// define("statusToko", 123);

	// define("laporanperiodeolnb2b", 2);
	// define("oroverall", 3);

	// define("Supplier", 5);
	// define("Taxes", 6);
	// define("KategoriBiaya", 7);
	// define("JenisBiaya", 8);
	// define("mst_COA", 9);
	// define("SettingAkun", 10);

	// define("JurnalManual", 12);		
	// define("BiayaOperasional", 13);		
	// define("ImportMutation", 14);	
	// define("CRTertunda", 15);	
	// define("PaymentCheck", 16);	
	// define("ReturnConfirmed", 17);
	// define("aplist", 18);	
	// define("arb2blist", 19);
	// define("TutupBuku", 20);	

	// define("TaxesRpt", 22);	
	// define("cashReceipt", 23);
	// define("jurnal", 24);
	// define("bukubesar", 25);
	// define("arsipjurnal", 26);
	// define("arsipbukubesar", 27);
	// define("arsipaplist", 28);
	// define("arsipapolnlist", 29);
	// define("arsipb2blist", 30);
	
	// define("departemen", 32);
	// define("jabatan", 33);
	// define("pendapatan", 34);
	// define("potongan", 35);
	// define("karyawan", 36);

	// define("penggajian", 38);
	// define("transpendapatan", 39);
	// define("transpotongan", 40);

	// define("Address", 42);
	// define("Category", 43);
	// define("Colour", 44);
	// define("Keranjang", 45);
	// define("Dropshipper", 46);
	// define("ExpeditionCategory", 47);
	// define("Expedition", 48);
	// define("Products", 49);
	// define("ProductsComposition", 50);

	// define("ImportCAMOU", 52);
	// define("PreSALES", 53);
	// define("ImportCredit", 54);
	// define("PreSALESCredit", 55);

	// define("DropshipperDeposit", 57);
	// define("DepositTransaction", 58);
	// define("OnlineSales", 59);
	// define("OnlineCredit", 60);
	// define("PendingOrder", 61);
	// define("CancelOrder", 62);
	// define("OnlineReturn", 63);
	// define("UnpaidOnline", 64);
	
	// define("OnlineSummary", 66);
	// define("SummaryCash", 67);
	// define("SummaryCredit", 68);
	// define("DropshipperStatistik", 69);
	// define("SalesOnlineDropshipper", 70);
	// define("Bill", 71);
	// define("rptexpedition", 72);
	// define("PrintOrder", 73);
	// define("ProductSold", 74);
	// define("Omset", 75);
	// define("TroubleOrder", 76);
	// define("OnlineDelivery",77);
	// define("OnlineBackDate",78);
	// define("ArchiveOrder", 79);
	
	// define("ImportCustomerProduct", 81);

	// define("CompositionProducts", 83);
	// define("B2BProducts", 84);
	// define("B2BProductsGroup", 85);
	// define("B2BCustomer", 86);
	// define("B2BExpedition",87);
	// define("B2BSalesman", 88);

	// define("AddSalesB2B", 90);
	// define("SalesB2B", 91);
	// define("ConfirmedSales", 92);
	// define("DeliveryOrderB2B", 93);
	// define("ArchiveOrderB2B", 94);
	// define("trb2breturn", 95);
	// define("arb2b", 96);

	// define("SummaryDeliveryB2B", 98);
	// define("stb2bdo", 99);
	// define("b2bsorpt", 100);
	// define("b2bcomp", 101);
	// define("b2bretur", 102);
	// define("b2bcompretur", 103);

	// define("pemohonpo", 105);
	// define("supplier", 106);
	// define("produkpo", 107);

	// define("po", 109);
	// define("poapproval", 110);
	// define("archivepo", 111);

	// define("MUTASIMASUK", 113);
	// define("MUTASIKELUAR", 114);
	// define("INVENTORY", 115);

	// define("MUTASIMASUK_COMP", 117);
	// define("MUTASIKELUAR_COMP", 118);
	// define("INVENTORY_COMP", 119);

	// define("DataUser", 121);
	// define("UserGroup", 122);
	// define("GroupAkses", 123);
	// define("statusToko", 124);

	// define("laporanperiodeolnb2b", 2);
	// define("oroverall", 3);

	// define("Supplier", 5);
	// define("Taxes", 6);
	// define("KategoriBiaya", 7);
	// define("JenisBiaya", 8);
	// define("mst_COA", 9);
	// define("SettingAkun", 10);

	// define("JurnalManual", 12);		
	// define("BiayaOperasional", 13);		
	// define("ImportMutation", 14);	
	// define("CRTertunda", 15);	
	// define("PaymentCheck", 16);	
	// define("aplist", 17);	
	// define("ReturnConfirmed", 18);
	// define("arb2blist", 19);
	// define("TutupBuku", 20);	

	// define("TaxesRpt", 22);	
	// define("cashReceipt", 23);
	// define("jurnal", 24);
	// define("bukubesar", 25);
	// define("arsipjurnal", 26);
	// define("arsipbukubesar", 27);
	// define("arsipaplist", 28);
	// define("arsipapolnlist", 29);
	// define("arsipb2blist", 30);
	
	// define("departemen", 32);
	// define("jabatan", 33);
	// define("pendapatan", 34);
	// define("potongan", 35);
	// define("karyawan", 36);

	// define("penggajian", 38);
	// define("transpendapatan", 39);
	// define("transpotongan", 40);

	// define("Address", 42);
	// define("Category", 43);
	// define("Colour", 44);
	// define("Keranjang", 45);
	// define("Dropshipper", 46);
	// define("ExpeditionCategory", 47);
	// define("Expedition", 48);
	// define("Products", 49);
	// define("ProductsComposition", 50);

	// define("ImportCAMOU", 52);
	// define("PreSALES", 53);
	// define("ImportCredit", 54);
	// define("PreSALESCredit", 55);

	// define("DropshipperDeposit", 57);
	// define("DepositTransaction", 58);
	// define("OnlineSales", 59);
	// define("OnlineCredit", 60);
	// define("rptexpedition", 61);
	// define("OnlineDelivery",62);
	// define("ArchiveOrder", 63);
	// define("PendingOrder", 64);
	// define("CancelOrder", 65);
	// define("OnlineReturn", 66);
	// define("UnpaidOnline", 67);
	
	// define("OnlineSummary", 69);
	// define("SummaryCash", 70);
	// define("SummaryCredit", 71);
	// define("DropshipperStatistik", 72);
	// define("SalesOnlineDropshipper", 73);
	// define("Bill", 74);
	// define("PrintOrder", 75);
	// define("ProductSold", 76);
	// define("Omset", 77);
	// define("TroubleOrder", 78);
	// define("OnlineBackDate",79);
	
	// define("ImportCustomerProduct", 81);

	// define("CompositionProducts", 83);
	// define("B2BProducts", 84);
	// define("B2BProductsGroup", 85);
	// define("B2BCustomer", 86);
	// define("B2BExpedition",87);
	// define("B2BSalesman", 88);

	// define("AddSalesB2B", 90);
	// define("SalesB2B", 91);
	// define("ConfirmedSales", 92);
	// define("DeliveryOrderB2B", 93);
	// define("ArchiveOrderB2B", 94);
	// define("trb2breturn", 95);
	// define("arb2b", 96);

	// define("SummaryDeliveryB2B", 98);
	// define("stb2bdo", 99);
	// define("b2bsorpt", 100);
	// define("b2bcomp", 101);
	// define("b2bretur", 102);
	// define("b2bcompretur", 103);

	// define("pemohonpo", 105);
	// define("supplier", 106);
	// define("produkpo", 107);

	// define("po", 109);
	// define("poapproval", 110);
	// define("archivepo", 111);

	// define("MUTASIMASUK", 113);
	// define("MUTASIKELUAR", 114);
	// define("INVENTORY", 115);

	// define("MUTASIMASUK_COMP", 117);
	// define("MUTASIKELUAR_COMP", 118);
	// define("INVENTORY_COMP", 119);

	// define("DataUser", 121);
	// define("UserGroup", 122);
	// define("GroupAkses", 123);
	// define("statusToko", 124);

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
	// define("aplist", 16);	
	// define("ReturnConfirmed", 17);
	// define("arb2blist", 18);
	// define("TutupBuku", 19);	

	// define("TaxesRpt", 21);	
	// define("cashReceipt", 22);
	// define("jurnal", 23);
	// define("bukubesar", 24);
	// define("arsipjurnal", 25);
	// define("arsipbukubesar", 26);
	// define("oroverall", 27);
	// define("arsipaplist", 28);
	// define("arsipapolnlist", 29);
	// define("arsipb2blist", 30);
	
	// define("departemen", 32);
	// define("jabatan", 33);
	// define("pendapatan", 34);
	// define("potongan", 35);
	// define("karyawan", 36);

	// define("penggajian", 38);
	// define("transpendapatan", 39);
	// define("transpotongan", 40);

	// define("Address", 42);
	// define("Category", 43);
	// define("Colour", 44);
	// define("Keranjang", 45);
	// define("Dropshipper", 46);
	// define("ExpeditionCategory", 47);
	// define("Expedition", 48);
	// define("Products", 49);
	// define("ProductsComposition", 50);

	// define("ImportCAMOU", 52);
	// define("PreSALES", 53);
	// define("ImportCredit", 54);
	// define("PreSALESCredit", 55);

	// define("DropshipperDeposit", 57);
	// define("DepositTransaction", 58);
	// define("OnlineSales", 59);
	// define("OnlineCredit", 60);
	// define("rptexpedition", 61);
	// define("OnlineDelivery",62);
	// define("ArchiveOrder", 63);
	// define("PendingOrder", 64);
	// define("CancelOrder", 65);
	// define("OnlineReturn", 66);
	// define("UnpaidOnline", 67);
	
	// define("OnlineSummary", 69);
	// define("SummaryCash", 70);
	// define("SummaryCredit", 71);
	// define("DropshipperStatistik", 72);
	// define("SalesOnlineDropshipper", 73);
	// define("Bill", 74);
	// define("PrintOrder", 75);
	// define("ProductSold", 76);
	// define("Omset", 77);
	// define("TroubleOrder", 78);
	// define("OnlineBackDate",79);
	
	// define("ImportCustomerProduct", 81);

	// define("CompositionProducts", 83);
	// define("B2BProducts", 84);
	// define("B2BProductsGroup", 85);
	// define("B2BCustomer", 86);
	// define("B2BExpedition",87);
	// define("B2BSalesman", 88);

	// define("AddSalesB2B", 90);
	// define("SalesB2B", 91);
	// define("ConfirmedSales", 92);
	// define("DeliveryOrderB2B", 93);
	// define("ArchiveOrderB2B", 94);
	// define("trb2breturn", 95);
	// define("arb2b", 96);

	// define("SummaryDeliveryB2B", 98);
	// define("stb2bdo", 99);
	// define("b2bsorpt", 100);
	// define("b2bcomp", 101);
	// define("b2bretur", 102);
	// define("b2bcompretur", 103);

	// define("pemohonpo", 105);
	// define("supplier", 106);
	// define("produkpo", 107);

	// define("po", 109);
	// define("poapproval", 110);
	// define("archivepo", 111);

	// define("MUTASIMASUK", 113);
	// define("MUTASIKELUAR", 114);
	// define("INVENTORY", 115);

	// define("MUTASIMASUK_COMP", 117);
	// define("MUTASIKELUAR_COMP", 118);
	// define("INVENTORY_COMP", 119);

	// define("DataUser", 121);
	// define("UserGroup", 122);
	// define("GroupAkses", 123);
	// define("statusToko", 124);

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
	define("TutupBuku", 19);	

	define("TaxesRpt", 21);	
	define("cashReceipt", 22);
	define("jurnal", 23);
	define("bukubesar", 24);
	define("arsipjurnal", 25);
	define("arsipbukubesar", 26);
	define("oroverall", 27);
	define("arsipaplist", 28);
	define("arsipapolnlist", 29);
	define("arsipb2blist", 30);
	
	define("departemen", 32);
	define("jabatan", 33);
	define("pendapatan", 34);
	define("potongan", 35);
	define("karyawan", 36);

	define("penggajian", 38);
	define("transpendapatan", 39);
	define("transpotongan", 40);

	define("Address", 42);
	define("Category", 43);
	define("Colour", 44);
	define("Keranjang", 45);
	define("Dropshipper", 46);
	define("ExpeditionCategory", 47);
	define("Expedition", 48);
	define("Products", 49);
	define("ProductsComposition", 50);

	define("ImportCAMOU", 52);
	define("PreSALES", 53);
	define("ImportCredit", 54);
	define("PreSALESCredit", 55);

	define("DropshipperDeposit", 57);
	define("DepositTransaction", 58);
	define("OnlineSales", 59);
	define("OnlineCredit", 60);
	define("rptexpedition", 61);
	define("OnlineDelivery",62);
	define("ArchiveOrder", 63);
	define("PendingOrder", 64);
	define("CancelOrder", 65);
	define("OnlineReturn", 66);
	define("UnpaidOnline", 67);
	
	define("OnlineSummary", 69);
	define("SummaryCash", 70);
	define("SummaryCredit", 71);
	define("DropshipperStatistik", 72);
	define("SalesOnlineDropshipper", 73);
	define("Bill", 74);
	define("PrintOrder", 75);
	define("ProductSold", 76);
	define("Omset", 77);
	define("TroubleOrder", 78);
	define("OnlineBackDate",79);
	
	define("ImportCustomerProduct", 81);

	define("CompositionProducts", 83);
	define("B2BProducts", 84);
	define("B2BProductsGroup", 85);
	define("B2BCustomer", 86);
	define("B2BExpedition",87);
	define("B2BSalesman", 88);

	define("AddSalesB2B", 90);
	define("SalesB2B", 91);
	define("ConfirmedSales", 92);
	define("DeliveryOrderB2B", 93);
	define("trb2bpiutangPembayaran", 94);
	define("trb2breturn", 95);
	define("arb2b", 96);

	define("SummaryDeliveryB2B", 98);
	define("stb2bdo", 99);
	define("b2bsorpt", 100);
	define("ArchiveOrderB2B", 101);
	define("b2bcomp", 102);
	define("b2bretur", 103);
	define("b2bcompretur", 104);

	define("pemohonpo", 106);
	define("supplier", 107);
	define("produkpo", 108);

	define("po", 110);
	define("poapproval", 111);
	define("archivepo", 112);

	define("MUTASIMASUK", 114);
	define("MUTASIKELUAR", 115);
	define("INVENTORY", 116);

	define("MUTASIMASUK_COMP", 118);
	define("MUTASIKELUAR_COMP", 119);
	define("INVENTORY_COMP", 120);

	define("DataUser", 122);
	define("UserGroup", 123);
	define("GroupAkses", 124);
	define("statusToko", 125);

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
