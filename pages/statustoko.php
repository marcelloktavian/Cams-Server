<?php require_once '../include/config.php' ?>
<?php
	if(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
		$statusnya = '';
		if ($_POST['status']=='Tutup') {
			$statusnya = 'Buka';
		} else {
			$statusnya = 'Tutup';
		}

		//insert tbl log
		$u = $db->prepare("INSERT INTO tbl_log_status VALUES(NULL,STR_TO_DATE(?,'%d/%m/%Y'),?,?,NOW())");
		$u->execute(array($_POST['datenow'],$statusnya, $_SESSION['user']['username']));


		if($statusnya == 'Tutup'){
			//get noakun dari setting
			$debetolnso = '';
			$kreditolnso = '';
			$debetolnsocr = '';
			$kreditolnsocr = '';
			$debetb2b = '';
			$kreditb2b = '';
			$debetop = '';
			$kreditop = '';

			$q = $db->query("SELECT a.* FROM setting_akun a");
			$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($data1 as $line) {
				if($line['id']==1){
					$debetolnso = $line['akun_debet'];
					$kreditolnso = $line['akun_kredit'];
				}else if($line['id']==2){
					$debetolnsocr = $line['akun_debet'];
					$kreditolnsocr = $line['akun_kredit'];
				}else if($line['id']==3){
					$debetb2b = $line['akun_debet'];
					$kreditb2b = $line['akun_kredit'];
				}else if($line['id']==4){
					$debetop = $line['akun_debet'];
					$kreditop = $line['akun_kredit'];
				}
			}

			//insert olnso sales
			$q = $db->query("SELECT so.*, dr.id as iddropshipper, dr.nama, IFNULL(dr.no_akun,'') as akun FROM olnso so
			LEFT JOIN `mst_dropshipper` dr ON dr.id=so.`id_dropshipper` 
			WHERE DATE(so.tgl_ship)=STR_TO_DATE('".$_POST['datenow']."','%d/%m/%Y') AND piutang = 0 AND so.deleted=0 ORDER BY so.id_trans ASC
			");
			$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
			foreach($data1 as $line) {
				$akundebet = $debetolnso;
				$akunkredit = $kreditolnso;
				if($line['akun'] != '' && $line['akun'] != null){
					$akunkredit = $line['akun'];
				}

				$d = $db->prepare("INSERT INTO journal_transaction VALUES(NULL, ?, ?, ?, ?, STR_TO_DATE(?,'%d/%m/%Y'), ?, '', ?, ?,'0')");
				$d->execute(array($line['iddropshipper'],'Dropshipper',$line['id_trans'],$line['tgl_trans'],$_POST['datenow'],$akundebet,$line['ref_kode'],$line['total']));

				$d = $db->prepare("INSERT INTO journal_transaction VALUES(NULL, ?, ?, ?, ?, STR_TO_DATE(?,'%d/%m/%Y'), ?, '', ?, '0',?)");
				$d->execute(array($line['iddropshipper'],'Dropshipper',$line['id_trans'],$line['tgl_trans'],$_POST['datenow'],$akunkredit,$line['ref_kode'],$line['total']));
			}
		}

		//update status toko
		$u = $db->prepare("UPDATE tbl_status SET status=?, user=?, lastmodified=NOW() WHERE id=?");
		$u->execute(array($statusnya, $_SESSION['user']['username'], $_POST['idstatus']));

		$affected_rows = $u->rowCount();
		// if($affected_rows > 0) {
		// 	$rp['status'] = 1;
		// 	$rp['message'] = 'Success';
		// }
		// else {
		// 	$rp['status'] = 0;
		// 	$rp['message'] = 'Failed';
		// }
		// echo json_encode($rp);
		if ($affected_rows > 0) {
			echo "<script type='text/javascript'>alert('Toko ".$statusnya."');window.location.href = '".BASE_URL."';</script>";
		} else {
			echo "<script type='text/javascript'>alert('Failed');</script>";
		}
		
		// exit;
	}else if(isset($_GET['action']) && strtolower($_GET['action']) == 'json') {
		$page  = $_GET['page'];
        $limit = $_GET['rows'];
        $sidx  = $_GET['sidx'];
        $sord  = $_GET['sord'];
		
        $page = isset($_GET['page'])?$_GET['page']:1; // get the requested page
        $limit = isset($_GET['rows'])?$_GET['rows']:10; // get how many rows we want to have into the grid
        $sidx = isset($_GET['sidx'])?$_GET['sidx']:'lastmodified'; // get index row - i.e. user click to sort
        $sord = isset($_GET['sord'])?$_GET['sord']:''; 
	
		$sql = " SELECT * FROM tbl_log_status a ";
		// var_dump($sql);
		// die;	
		$q = $db->query($sql);
		$count = $q->rowCount();
        
        $count > 0 ? $total_pages = ceil($count/$limit) : $total_pages = 0;
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit;
        if($start <0) $start = 0;

        $q = $db->query($sql." ORDER BY `".$sidx."` ".$sord." LIMIT ".$start.", ".$limit);
							//  var_dump($q);
							//  die;
		$data1 = $q->fetchAll(PDO::FETCH_ASSOC);
        $responce['page'] = $page;
        $responce['total'] = $total_pages;
        $responce['records'] = $count;
        $i=0;
		// footer
		$subtotal = 0;
		$ppn=0;
		$total=0;
		$qty=0;
		// ./././
        foreach($data1 as $line) {

        	$responce['rows'][$i]['id']   = $line['lastmodified'];
            $responce['rows'][$i]['cell'] = array(
				$line['tgl'],   
                $line['lastmodified'],                	
                $line['status'].' Toko',                	
                ucfirst($line['user']),                	
			);
            $i++;
		}
		echo json_encode($responce);
		
		exit;
	}

	//set status
	$id = '';
	$status = '';

	$getStat = $db->prepare("SELECT * FROM tbl_status LIMIT 1");
	$getStat->execute();
	$stat = $getStat->fetchAll();
	foreach ($stat as $stats) {
		$id = $stats['id'];
    	$status = $stats['status'];
	}

?>
<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        Status Toko
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="change_status_form" method="post" action="<?php echo BASE_URL ?>pages/statustoko.php?action=process" class="ui-helper-clearfix">
		<table>
			<tr>
				<td><label for="status" class="ui-helper-reset label-control">Tanggal </label></td>
				<td><div class="ui-corner-all form-control"><input value="" type="text" class="required datepicker"   id="datenow" name="datenow"></div></td>
			</tr>
			<tr>
				<td><label for="status" class="ui-helper-reset label-control">Status Toko </label></td>
				<td><div class="ui-corner-all form-control">
					<input type="text" id="status" name="status" value="<?= $status?>" readonly>
					<input type="hidden" id="idstatus" name="idstatus" value="<?= $id?>">
				</div></td>
			</tr>
			<tr>
				<td></td>
				<td><button class="btn" type="submit" name="ubahStatus" id="ubahStatus">Ubah Status</button></td>
			</tr>
		</table>
       	</form>
   	</div>
</div>

<table id="tablestatuscams"></table>
<div id="pager_statuscams"></div>

<script>
    $('#datenow').datepicker({
		dateFormat: "dd/mm/yy"
	});
	$( "#datenow" ).datepicker( 'setDate', '<?php echo date('d/m/Y')?>' );

	$(document).ready(function(){
        $("#tablestatuscams").jqGrid({
            url:'<?php echo BASE_URL.'pages/statustoko.php?action=json'; ?>',
            datatype: "json",
            colNames:['Tanggal','Lastmodified','Aksi','User'],
            colModel:[
                {name:'tgl',index:'tgl', width:50, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d", newformat:"d/m/Y"}, align:'center'},
				{name:'lastmodified',index:'lastmodified', width:50, searchoptions: {sopt:['cn']},formatter:"date", formatoptions:{srcformat:"Y-m-d H:i:s", newformat:"d/m/Y H:i:s"}, align:'center'},
				{name:'status',index:'status', align:'center', width:50, search:true, stype:'text', searchoptions:{sopt:['cn']}},
				{name:'user',align:'center',index:'user', width:50, search:true, stype:'text', searchoptions: {sopt:['cn']}},    
            ],
            rowNum:1000,
            rowList:[10,20,30,100,1000,10000],
            pager: '#pager_statuscams',
            sortname: 'lastmodified',
            autowidth: true,
			multiselect:false,
            height: '300',
            viewrecords: true,
            rownumbers: true,
            sortorder: "desc",
            caption:"Data Log Status Cams",
            ondblClickRow: function(rowid) {
                alert(rowid);
            },
			footerrow : false,
			userDataOnFooter : false,
            subGrid : false,
            subGridUrl : '<?php echo BASE_URL.'pages/Transaksi_Operasional/biayaOperasional.php?action=json_sub'; ?>',
            subGridModel: [
			            	{ 
			            		name : ['No','Cost','Satuan','Qty','Price@','Subtotal','PPN'], 
			            		width : [40,250,40,70,70,70,70],
			            		align : ['right','center','left','left','left','left','left'],
			            	} 
			            ],
						
            
        });
        $("#tablestatuscams").jqGrid('navGrid','#pager_statuscams',{edit:false,add:false,del:false,search:false});
    })
</script>