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
       $where = "WHERE b.deleted=0 AND b.type=2 and b.id <> 0 ";
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
	  $where = sprintf(" where b.deleted=0 AND b.type=2 and b.id <> 0 AND p.%s ".$operations[$_REQUEST["searchOper"]], $_REQUEST["searchField"], $value);
	
     }
//--------------end of searching--------------------		

        
		$sql_deposit = "Select b.id, b.id_cust,b.namaperusahaan,b.keterangan,b.alamat,b.telp1,b.hp,b.contactperson,jual.deposit as trdeposit from tblsupplier b left join (select id_customer,sum(ifnull(deposit,0)) as deposit from trdepositpb td group by id_customer ) as jual on b.id = jual.id_customer ".$where;
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
				$edit = '<a onclick="javascript:popup_form(\''.BASE_URL.'pages/pabrik/deposit_pelangganpb.php?action=edit&id='.$line['id'].'\',\'table_depositpelanggan\')" href="javascript:;">Edit</a>';
			else
				$edit = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Edit</a>';
        	
			if(in_array($_SESSION['user']['access'], $allowDelete))
				$delete = '<a onclick="javascript:link_ajax(\''.BASE_URL.'pages/pabrik/deposit_pelangganpb.php?action=delete&id='.$line['id'].'\',\'table_depositpelanggan\')" href="javascript:;">Delete</a>';
			else
				$delete = '<a onclick="javascript:custom_alert(\'Not Allowed\')" href="javascript:;">Delete</a>';
			
            $responce['rows'][$i]['id']   = $line['id'];
            $responce['rows'][$i]['cell'] = array(
                $line['id'],
                $line['id_cust'],
                $line['namaperusahaan'],                
                $line['alamat'],                
                $line['telp1'],                
                $line['contactperson'],                
                $line['hp'],                
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
		include 'deposit_pelangganpb_form.php';exit();
		exit;
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'edit') {
		include 'deposit_pelangganpb_form.php';exit();
		exit; 
	}
	elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'delete') {
		$stmt = $db->prepare("UPDATE tblsupplier SET deleted=? WHERE id=?");
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
		$where = " WHERE d.id_customer = '".$id."' ";
        $sql_sub="Select d.id_trans,d.kode,d.tgl_trans,d.id_customer,p.namaperusahaan,d.totalfaktur,d.tunai,d.transfer,d.deposit,d.keterangan,d.catatan from trdepositpb d left join tblsupplier p on d.id_customer=p.id".$where;
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
                $line['kode'],
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
<table id="table_depositpelangganpb"></table>
<div id="pager_table_depositpelangganpb"></div>
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

        $("#table_depositpelangganpb").jqGrid({
            url:'<?php echo BASE_URL.'pages/pabrik/deposit_pelangganpb.php?action=json'; ?>',
            /*postData: {
                'title': function() {return $('#sJudul').val(); },
                'sales_id': function() {return $('#sSales_id').val(); },
                'Name': function() {return $('#sCustomer').val(); },
                'summary_status': function() {return $('#sStatus').val(); },
            },*/
            datatype: "json",
            colNames:['ID','Kode','Nama','Alamat','Telepon','Contact Person','HandPhone','Deposit'/*,'Edit'*/],
            colModel:[
                {name:'id',index:'id', align:'right', width:15, searchoptions: {sopt:['cn']}},
                {name:'id_cust',index:'id_cust', width:25, searchoptions: {sopt:['cn']}},  
				{name:'namaperusahaan',index:'namaperusahaan', width:100, searchoptions: {sopt:['cn']}},                
                {name:'alamat',index:'alamat', width:180, searchoptions: {sopt:['cn']}},                
                {name:'telp1',index:'telp1', width:70, searchoptions: {sopt:['cn']}},                
                {name:'contactperson',index:'contactperson', width:70, searchoptions: {sopt:['cn']}},                
				{name:'hp',index:'hp', width:70, searchoptions: {sopt:['cn']}},             
                {name:'deposit',align:'right',index:'trdeposit', width:70, searchoptions: {sopt:['cn']}},
				//{name:'Edit',index:'edit', align:'center', width:50, sortable: false, search: false},
                //{name:'Delete',index:'delete', align:'center', width:50, sortable: false, search: false},
            ],
            rowNum:20,
            rowList:[20,30,40],
            pager: '#pager_table_depositpelangganpb',
            sortname: 'trdeposit',
            autowidth: true,
            height: '460',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Deposit Pelanggan Pabrik",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			subGrid : true,
            subGridUrl : '<?php echo BASE_URL.'pages/pabrik/deposit_pelangganpb.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Kode','Tgl','TotalFaktur','Tunai','Transfer','Keterangan','Catatan'], 
			            		width : [30,50,100,75,75,75,100,100],
			            		align : ['right','center','left','right','right','right','left','left'],
			            	} 
			            ],
        });
        $("#table_depositpelangganpb").jqGrid('navGrid','#pager_table_depositpelangganpb',{edit:false,add:false,del:false});
    })
</script>