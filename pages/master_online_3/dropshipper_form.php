<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo $action .' DROPSHIPPER';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/master_online/dropshipper.php?action=process" class="ui-helper-clearfix">
        	<?php
	        	if(strtolower($_GET['action']) == 'edit') {
					echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$select = $db->prepare('SELECT * FROM mst_dropshipper WHERE id = :id');
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);
				}
	        ?>
            
			<label for="name" class="ui-helper-reset label-control">Name</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="nama" name="nama"><?php echo isset($row['nama']) ? $row['nama'] : ''; ?></textarea>	
            </div>
			
			<label for="oln_id" class="ui-helper-reset label-control">OLN ID</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['oln_customer_id']) ? $row['oln_customer_id'] : ''; ?>" type="text" class="required" id="oln_customer_id" name="oln_customer_id">	
            </div>
			
			<label for="kabupaten" class="ui-helper-reset label-control">Address(Alamat)</label>
            <div class="ui-corner-all form-control">
                <textarea class="" id="alamat" name="alamat"><?php echo isset($row['alamat']) ? $row['alamat'] : ''; ?></textarea>	
            </div>
			
			<label for="telp" class="ui-helper-reset label-control">No Telp</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['no_telp']) ? $row['no_telp'] : ''; ?>" type="text" id="no_telp" name="no_telp">
            </div>

            <label for="phone" class="ui-helper-reset label-control">Phone</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['hp']) ? $row['hp'] : ''; ?>" type="text" class="required" id="hp" name="hp">
                * Wajib Diisi
            </div>
			
			<label for="disc" class="ui-helper-reset label-control">Disc</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['disc']) ? $row['disc'] : ''; ?>" type="text" class="required" id="disc" name="disc">	
            </div>
			
			<label for="tipe" class="ui-helper-reset label-control">Type</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['type']) ? $row['type'] : ''; ?>" type="text" class="required" id="tipe" name="tipe">	
            </div>

            <label for="noakun" class="ui-helper-reset label-control">No Akun</label>
            <div class="ui-corner-all form-control">
                <select id="noakun" name="noakun">
                <option value=''>-- Pilih No Akun --</option>
                <?php
                $sql_products = 'SELECT a.* FROM `mst_coa` a  ';

                $query = '';
                $countnya = 0;

                $q = $db->query($sql_products.' where a.deleted=0 ORDER BY noakun ASC');
                $data1 = $q->fetchAll(PDO::FETCH_ASSOC);
                foreach($data1 as $line) {
                    if ($countnya == 0) {
                        $query .= "select id, noakun, nama, jenis from mst_coa where id='".$line['id']."' ";
                    } else {
                        $query .= " UNION ALL select id, noakun, nama, jenis from mst_coa  where id='".$line['id']."' ";
                    }
                    $countnya++;
                    $q2 = $db->query("SELECT * FROM det_coa WHERE id_parent='".$line['id']."' ORDER by noakun ASC");
                    $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
                    foreach($data2 as $line2) {
                        $query .= " UNION ALL select '' as id, noakun, nama, '' as jenis from det_coa where id='".$line2['id']."' ";
                    }
                    
                }

                $q2 = $db->query($query);
                $data2 = $q2->fetchAll(PDO::FETCH_ASSOC);
                foreach($data2 as $line2) {
                    $selected = '';
                    if(isset($row['no_akun'])){
                        if($row['no_akun'] == $line2['noakun']){
                            $selected = 'selected';
                        }
                    }
                    echo "<option value='".$line2['noakun']."' $selected>".$line2['noakun']." - ".$line2['nama']."</option>";
                }
                ?>
                </select>
            </div>
			
        </form>
    </div>
</div>