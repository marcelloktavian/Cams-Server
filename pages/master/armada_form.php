<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' ARMADA';
		//-------------------fungsi buat bikin nomer---------------------	
		include("../../include/koneksi.php");
 
		function getincrementnumber2()
		{
		$q = mysql_fetch_array( mysql_query('select id_ar from tblarmada order by id_ar desc limit 0,1'));
	
		$kode=substr($q['id_ar'], -4);
		$bulan=substr($q['id_ar'], -7,2);
		$bln_skrng=date('m');
		$num=(int)$kode;
			if($num==0 || $num==null || $bulan!=$bln_skrng)		
			{
			$temp = 1;
			}
			else
			{
			$temp=$num+1;
			}
		return $temp;
		}

		function getmonthyeardate2()
		{
		$today = date('ym');
		return $today;
		}

		function getnewnotrxwait2()
		{
		$temp=getmonthyeardate2();
		$temp2=getincrementnumber2();
		$id="MA/".$temp."/".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
		return $id;
		}	
	//$id_registrasi = getnewnotrxwait();
	$id_pkb = getnewnotrxwait2();			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master/armada.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id_ar" name="id_ar">';
					$select = $db->prepare('SELECT * FROM tblarmada WHERE id_ar = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            <label for="id_armada" class="ui-helper-reset label-control">ID ARMADA</label>
				
            <div class="ui-corner-all form-control">
                <input value="<?php echo"".$id_pkb; ?>" type="text" type="text" id="idarmada" name="idarmada">	
            </div>
			
			<label for="nama" class="ui-helper-reset label-control">Nama</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['Nama']) ? $row['Nama'] : ''; ?>" type="text" class="required" id="nama" name="nama">	
            </div>
			
			<label for="nopol" class="ui-helper-reset label-control">No.Polisi</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['NoPOL']) ? $row['NoPOL'] : ''; ?>" type="text" class="required" id="nopol" name="nopol">	
            </div>
			<label for="keterangan" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="keterangan" name="keterangan"><?php echo isset($row['Keterangan']) ? $row['Keterangan'] : ''; ?></textarea>	
            </div>
			
        </form>
    </div>
</div>