<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo'PAID SUMMARY CASH';			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/summary_online/trolnso_sumcash.php?action=process_posting" class="ui-helper-clearfix">
        <?php
            $select = $db->prepare("SELECT p.*, dp.nama as dropshipper,DATE_FORMAT(p.tgl_trans, '%d/%m/%Y') as tanggal FROM `olnso` p LEFT JOIN mst_dropshipper dp ON dp.id=p.id_dropshipper WHERE p.id_trans=:id");
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);

            $tgl = $row['tanggal'];
        ?>

            <label for="kode" class="ui-helper-reset label-control">ID OLN</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['id_trans'] ?>" type="text" disabled>	
            </div>

            <label for="kode" class="ui-helper-reset label-control">Dropshipper</label>
            <div class="ui-corner-all form-control">
                <textarea disabled row=1><?php echo $row['dropshipper'] ?></textarea>
            </div>
            
            <label for="kode" class="ui-helper-reset label-control">OLN.DATE</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $tgl ?>" type="text" disabled>	
            </div>

            <label for="kode" class="ui-helper-reset label-control">Total</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo number_format($row['total'],0) ?>" type="text" disabled>	
            </div>

            <label for="kode" class="ui-helper-reset label-control">Pre Bank</label>
            <div class="ui-corner-all form-control">
            <input value="<?php echo $_GET['id'] ?>" type="hidden" id="id" name="id">	
            <select name="prebankselect" id="prebankselect" required>
                	<option value="" >-choose(pilih)-</option>
                	<?php
                        $query = $db->query("SELECT * FROM acc_prebank WHERE jumlah='".$row['total']."' AND SUBSTRING(periode,1,10) = '".$tgl."' ");

                        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach($rows as $r) {
                            echo '<option value="'.$r['id'].'">'.$r['keterangan'].' - Total : '.number_format($r['jumlah'],0).'</option>';
                        }

                	?>
                </select>
            </div>
            			
        </form>
    </div>
</div>
<script>
$(document).ready(function() { 
    $("#prebankselect").select2({
        width: '250px', 
        dropdownAutoWidth : true
    });           
});
</script>
<style>
 .select2-results__options{
    font-size:11px !important;
}
</style>