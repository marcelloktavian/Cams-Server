<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' SUPPLIER';
		//-------------------fungsi buat bikin nomer---------------------	
		include("../../include/koneksi.php");
 
		function getincrementnumber2()
		{
		$q = mysql_fetch_array( mysql_query('select id_cust from tblsupplier order by id_cust desc limit 0,1'));
	
		$kode=substr($q['id_cust'], -4);
		$bulan=substr($q['id_cust'], -7,2);
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
		$id="MS/".$temp."/".str_pad($temp2, 4, 0, STR_PAD_LEFT);	
		return $id;
		}	
	$id_pkb = getnewnotrxwait2();			
        
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="supplier_form" method="post" action="<?php echo BASE_URL ?>pages/master/supplier.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM tblsupplier WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
			<label for="id_supp" class="ui-helper-reset label-control">KODE SUPPLIER</label>	
            <div class="ui-corner-all form-control">
			    <input value="<?php echo isset($row['id_cust']) ? $row['id_cust'] : ''; ?>" type="text" type="text" id="idsupp" name="idsupp">	
            </div>
			
            <label for="supplier_name" class="ui-helper-reset label-control">Nama Supplier (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['namaperusahaan']) ? $row['namaperusahaan'] : ''; ?>" type="text" class="required" id="nama" name="nama">
            </div>
			
			<label for="alamat" class="ui-helper-reset label-control">Alamat (*)</label>
            <div class="ui-corner-all form-control">
                <textarea class="required" id="alamat" name="alamat"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>
            </div>
			
			<label for="telp1" class="ui-helper-reset label-control">Telepon 1 (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['telp1']) ? $row['telp1'] : ''; ?>" type="text" class="required number" id="telp1" name="telp1">
            </div>
			
			<label for="telp2" class="ui-helper-reset label-control">Telepon 2</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['telp2']) ? $row['telp2'] : ''; ?>" type="text" id="telp2" name="telp2">
            </div>
			
			<label for="fax" class="ui-helper-reset label-control">FAX</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['fax']) ? $row['fax'] : ''; ?>" type="text" id="fax" name="fax">
            </div>
			
			<label for="contactperson" class="ui-helper-reset label-control">Contact Person (*)</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['contactperson']) ? $row['contactperson'] : ''; ?>" type="text" class="required"  id="contactperson" name="contactperson">
            </div>
			
			<label for="hp" class="ui-helper-reset label-control">Handphone</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['HP']) ? $row['HP'] : ''; ?>" type="text" class="" id="hp" name="hp">
            </div>
			
			<label for="email" class="ui-helper-reset label-control">Email</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['email']) ? $row['email'] : ''; ?>" type="text" class="" id="email" name="email">
            </div>
			
        </form>
		(*) wajib diisi
    </div>
</div>