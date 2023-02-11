<?php require_once '../../include/config.php' ?>
<?php
$group_acess = unserialize(file_get_contents("../../GROUP_ACCESS_CACHE".$_SESSION['user']['group_id']));
$allow_add = is_show_menu(ADD_POLICY, AddSalesB2B, $group_acess);

        if(isset($_GET['action']) && strtolower($_GET['action']) == 'add') {
        	include 'kasir_pelanggan_form.php';exit();
        	exit;
        }
        
        elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'get_pelanggan') {
	    //yang tampil hanya yang typenya=2 (toko) saja
        	$p = $db->query("SELECT * FROM mst_b2bcustomer p where p.deleted=0 ORDER BY p.nama asc");
        	$rows = $p->fetchAll(PDO::FETCH_ASSOC);
        	$response = array();
        	foreach ($rows as $r) { 
        		$response[] = array('key'=>$r['id'],'value'=>$r['nama']);
        	}
        	echo json_encode($response);
        	exit;
        }
        elseif(isset($_GET['action']) && strtolower($_GET['action']) == 'process') {
        	
        	if(isset($_POST['id'])) {
        		$stmt = $db->prepare("UPDATE tblpelanggan SET id_cust=?, namaperusahaan=?,alamat=?,keterangan=?,telp1=?,telp2=?,fax=?,contactperson=?,HP=?,email=?,user=?, lastmodified = NOW() WHERE id=?");
        		$stmt->execute(array($_POST['idcust'],$_POST['nama'],$_POST['alamat'],strtoupper($_POST['keterangan']),$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_SESSION['user']['username'], $_POST['id']));
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
		  //validasi bila ada input pelanggan yang double---------------
        		$cek = $db->prepare("SELECT * from tblpelanggan where namaperusahaan=?");
        		$cek->execute(array($_POST['nama']));
		  //--------------------------------------------------------------
        		$check_rows = $cek->rowCount();
        		if($check_rows > 0) {
				//$r['stat'] = 1;
        			$r['message'] = 'Ada nama yang sama???? Harap ganti nama';
        		}
        		else 
        		{
        			
			//Type pelanggan 1=Umum, 2=Toko		  
        			$stmt = $db->prepare("INSERT INTO tblpelanggan(`namaperusahaan`,`id_cust`,`alamat`,`keterangan`,`telp1`,`telp2`,`fax`,`contactperson`,`HP`,`email`,`user`,`type`,`lastmodified`) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");
        			
			//di kasir diinput pelanggan type=2
        			if($stmt->execute(array(strtoupper($_POST['nama']),$_POST['idcust'],$_POST['alamat'],strtoupper($_POST['keterangan']),$_POST['telp1'],$_POST['telp2'],$_POST['fax'],$_POST['contactperson'],$_POST['hp'],$_POST['email'],$_SESSION['user']['username'],2))) {
			//if($stmt->execute(array($_POST['nama'],$_SESSION['user']['username']))) {
        				$r['stat'] = 1;
        				$r['message'] = 'Success';
        				
        			}
        			else {
        				$r['stat'] = 0;
        				$r['message'] = 'Failed';
        			}
        		}
        	}	
        	echo json_encode($r);
        	exit;
        }
        ?>


        <div class="ui-widget ui-form">    
        	<div class="ui-widget-header ui-corner-top padding5">
        		INPUT SALES B2B
        	</div>
        	<div class="ui-widget-content ui-corner-bottom">
        		<form id="kasir" method="post" action="<?php echo BASE_URL ?>pages/kasir/kasir_id.php" class="ui-helper-clearfix">
        			<div class="ui-corner-all form-control">
        				<table>
        					<tr>
        						<td>CUSTOMER</td>				
        						<td>
        							<div class="ui-corner-all form-control">
        								<select name="pelanggan_id" id="pelanggan_id">
        									
        								</select>
        								<div id="loading" style="display:none;float:inline-end"><image src="./pages/kasir/loading.gif" /></div>
        							</div>
        						</td>
        						<td>
        							<div class="btn_box">
        								<button onclick="javascript:load_pelanggan()" class="btn" type="button">Refresh CUSTOMER</button>
        								<?php
        								if ($allow_add) {
        									?>
        									<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/master_b2b/b2bcustomer_detail.php')" class="btn" type="button">ADD CUSTOMER</button>
        									<?php
        								}
        								?>
        							</div>
        						</td>
        					</tr>
        					<tr>
        						<td colspan=2>
        							
        							<label for="" class="ui-helper-reset label-control">&nbsp;</label>
        							<div class="ui-corner-all form-control">
        								<?php
        								if ($allow_add) {
        									?>
        									<button onclick="javascript:window_open('<?php echo BASE_URL ?>pages/sales_b2b/trb2bsogrp_detail.php?id_cust='+$('#pelanggan_id').val())" class="btn" type="button">ADD SALES</button>
        									<?php
        								}
        								?>
        							</div>
        							
        						</td>
        					</tr>
        				</table>
        			</div>
        			<table id="table_pelanggankasir"></table>
        		</form>
        	</div>
        </div>
        <script type="text/javascript">

        	$(function () { 
        		
   /// YJS in action here....
   var loading = $("#loading");
   $(document).ajaxStart(function () {
   	loading.show();
   });

   $(document).ajaxStop(function () {
   	loading.hide();
   });
});		
        	
        	load_pelanggan = function (){
        		
        		list_pelanggan = document.getElementById('pelanggan_id');
        		$.ajax({
        			url:'<?=BASE_URL?>pages/sales_b2b/trb2bso_add.php?action=get_pelanggan',
        			success:function(result) {
        				$("#pelanggan_id").empty();
        				result = JSON.parse(result);
        				for (a in result) {					 
        					$('#pelanggan_id').append(new Option(result[a].value,result[a].key));
        				}
        				
        			}
        		});
        		
        		
        		
        		
        		
        		
        	}
        	
        	load_pelanggan();

        </script>