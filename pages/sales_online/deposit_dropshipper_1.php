<?php require_once '../../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];

        if(!$sidx) $sidx=1;
//searching _filter---------------------------------------------------------
       if ($_REQUEST["_search"] == "false") {
       $where = " WHERE d.deleted=0 ";
       } else {
       $operations = array(
        'eq' => "= '%s'",            // Equal
        'ne' => "<> '%s'",           // Not equal
        'lt' => "< '%s'",            // Less than
        'le' => "<= '%s'",           // Less than or equal
        'gt' => "> '%s'",            // Greater than
        'ge' => ">= '%s'",           // Greater or equal
        'bw' => "like '%s%%'",       // Begins With
        'bn' => "not like '%s%%'",   // Does not begin with
        'in' => "in ('%s')",         // In
        'ni' => "not in ('%s')",     // Not in
        'ew' => "like '%%%s'",       // Ends with
        'en' => "not like '%%%s'",   // Does not end with
        'cn' => "like '%%%s%%'",     // Contains
        'nc' => "not like '%%%s%%'", // Does not contain
        'nu' => "is null",           // Is null
        'nn' => "is not null"        // Is not null
    ); 
	
      $value = $_REQUEST["searchString"];
	  $where = sprintf(" where d.deleted=0 AND p.%s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		

        
		$sql_deposit = "Select d.id, d.nama,d.disc,d.type,d.note,jual.deposit as trdeposit from mst_dropshipper d left join (select id_dropshipper,sum(ifnull(deposit,0)) as deposit from olndeposit od group by id_dropshipper ) as jual on d.id = jual.id_dropshipper".$where;
		//var_dump($sql_deposit);
        $q = $db->query($sql_deposit);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql_deposit."
							 ORDER BY `".$sidx."` ".$sord."
							 LIMIT ".$start.", ".$limit);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
        foreach($data1 as $line) {
        	$allowEdit = array(1,2,3);
			$allowDelete = array(1,2,3);
			if(in_array($_SESSION['user']['access'], $allowEdit))
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/kasir/deposit_pelanggan.php?action=edit&id='.$line['id'].'\',\'table_depositpelanggan\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/kasir/deposit_pelanggan.php?action=delete&id='.$line['id'].'\',\'table_depositpelanggan\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['nama'],                
                $line['disc'],                
                $line['type'],                
                number_format($line['trdeposit'],0),                
				//$edit,
				//$delete,
            );
            $i++;
        }
        echo json_encode($responce);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
		include 'deposit_pelanggan_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'deposit_pelanggan_form.php';exit();
		exit; 
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE tblpelanggan SET deleted=? WHERE id=?");
		$stmt->execute(array(1, $_GET['id']));
		$affected_rows = $stmt->rowCount();
		if($affected_rows > 0) {
			$r['stat'] = 1;
			$r['message'] = 'Success';
		}
		else {
			$r['stat'] = 0;
			$r['message'] = 'Failed';
		}
		echo json_encode($r);
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'json_sub') {
	
		$id = $_GET['id'];
		$where = " WHERE d.id_dropshipper = '".$id."' ";
        $sql_sub="Select d.id_trans,d.kode,d.tgl_trans,d.id_customer,p.nama,d.totalfaktur,d.tunai,d.transfer,d.deposit,d.keterangan,d.catatan from olndeposit d left join mst_dropshipper p on d.id_dropshipper=p.id".$where;
		//var_dump($sql_sub);die;
		$q = $db->query($sql_sub);
		
		$count = $q->rowCount();
		
		$q = $db->query($sql_sub);
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
		
        $i=0;
        $responce = '';
        foreach($data1 as $line){
            $responce->rows[$i]['id']   = $line['id_trans'];
            $responce->rows[$i]['cell'] = array(
                $i+1,
                $line['id_trans'],
                 date('d-m-Y',strtotime($line['tgl_trans'])),
                 number_format($line['totalfaktur'],0),
                 number_format($line['tunai'],0),                
                 number_format($line['transfer'],0),                
                $line['keterangan'],           
                $line['catatan'],           
            );
            $i++;
        }
		echo json_encode($responce);
		exit;
       }
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		if(isset($_POST['id'])) {
			$stmt = $db->prepare("UPDATE tblpelanggan SET id_cust=?,namaperusahaan=?,deposit=?,keterangan=?,alamat=?,telp1=?,telp2=?,fax=?,contactperson=?,HP=?,email=?,deposit=?,user=?, lastmodified = NOW() WHERE id=?");
			$stmt->execute(array($_POST['idcust'],strtoupper($_POST['nama']),$_POST['deposit'],strtoupper($_POST['keterangan']),$_POST['alamat'],$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_POST['deposit'],$_SESSION['user']['username'], $_POST['id']));
			$affected_rows = $stmt->rowCount();
			if($affected_rows > 0) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}
		else {
			$stmt = $db->prepare("INSERT INTO tblpelanggan(`id_cust`,`namaperusahaan`,`deposit`,`keterangan`,`alamat`,`telp1`,`telp2`,`fax`,`contactperson`,`HP`,`email`,`user`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
			if($stmt->execute(array($_POST['idcust'],$_POST['deposit'],strtoupper($_POST['nama']),strtoupper($_POST['keterangan']),$_POST['alamat'],$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_SESSION['user']['username']))) {
				$r['stat'] = 1;
				$r['message'] = 'Success';
			}
			else {
				$r['stat'] = 0;
				$r['message'] = 'Failed';
			}
		}
		
		echo json_encode($r);
		exit;
	}
?>
<table id="table_depositpelanggan"></table>
<div id="pager_table_depositpelanggan"></div>
<!--<div class="btn_box">-->
<?php
/*
	$allow = array(1,2,3);
	if(in_array($_SESSION['user']['access'], $allow)) {
		echo '<button type="button" onclick="javascript:popup_form(\''.BASE_URL.'pages/kasir/deposit_pelanggan.php?action=add\',\'table_depositpelanggan\')" class="btn">Tambah</button>';
	}
*/	
?>
</div>
<script type="text/javascript">
    $(document).ready(function(){

        $("#table_depositpelanggan").jqGrid({
            url:'<?php echo BASE_URL.'pages/sales_online/deposit_dropshipper.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Nama','Disc','Type','Deposit'],
            colModel:[
                {name:'id',index:'id', align:'right', width:20, searchoptions: {sopt:['cn']}},
                {name:'nama',index:'nama', width:200, searchoptions: {sopt:['cn']}},  
				{name:'disc',index:'disc', width:50, searchoptions: {sopt:['cn']}},                
                {name:'type',index:'type', width:50, searchoptions: {sopt:['cn']}},                
                {name:'deposit',align:'right',index:'trdeposit', width:100, searchoptions: {sopt:['cn']}},
				
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_depositpelanggan',
            sortname: 'trdeposit',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Deposit Dropshipper",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/sales_online/deposit_dropshipper.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Tgl','TotalFaktur','Tunai','Transfer','Keterangan','Catatan'], 
			            		width : [30,100,100,75,75,75,100,100],
			            		align : ['right','center','center','right','right','right','left','left'],
			            	} 
			            ],
        });
        $("#table_depositpelanggan").jqGrid('navGrid','#pager_table_depositpelanggan',{edit:false,add:false,del:false});
    })
</script>