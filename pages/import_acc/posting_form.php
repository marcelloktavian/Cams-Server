<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        	$action = strtoupper($_GET['action']);
			echo'POSTING PRE BANK';     			
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="armada_form" method="post" action="<?php echo BASE_URL ?>pages/import_acc/prebank.php?action=process_posting" class="ui-helper-clearfix">
        <?php
            $select = $db->prepare("SELECT * FROM `acc_prebank` p WHERE id=:id");
					$select->execute(array(':id' => $_GET['id']));
					$row = $select->fetch(PDO::FETCH_ASSOC);

            $ex = explode(" - ",$row['periode']);

            $periode1 = $ex[0];
            $exp1 = explode("/",$periode1);
            $p1 = $exp1[2].'/'.$exp1[1].'/'.$exp1[0];

            $periode2 = $ex[1];
            $exp2 = explode("/",$periode2);
            $p2 = $exp2[2].'/'.$exp2[1].'/'.$exp2[0];
        ?>

            <label for="kode" class="ui-helper-reset label-control">Periode</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['periode'] ?>" type="text" disabled>	
            </div>

            <label for="kode" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea disabled> <?php echo $row['keterangan'] ?></textarea>	
            </div>
            
            <label for="kode" class="ui-helper-reset label-control">Cabang</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo $row['cabang'] ?>" type="text" disabled>	
            </div>

            <label for="kode" class="ui-helper-reset label-control">Total</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo number_format($row['jumlah'],0) ?>" type="text" disabled>	
            </div>

            <label for="kode" class="ui-helper-reset label-control">ID OLN</label>
            <div class="ui-corner-all form-control">
            <input value="<?php echo $_GET['id'] ?>" type="hidden" id="id" name="id">	
            <select name="oln" id="oln" required>
                	<option value="" >-choose(pilih)-</option>
                	<?php
                        $query = $db->query("SELECT so.id_trans, dr.nama as dropshipper, total FROM olnso so left join mst_dropshipper dr on dr.id = so.id_dropshipper WHERE stbank=0 AND so.transfer <> '0' AND so.total='".$row['jumlah']."' AND date(so.tgl_trans) between '$p1' and '$p2' ORDER BY id_trans ASC");
                        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
                        foreach($rows as $r) {
                            echo '<option value="'.$r['id_trans'].'">'.$r['id_trans'].' - '.$r['dropshipper'].' - Total : '.number_format($r['total'],0).'</option>';
                        }

                	?>
                </select>
            </div>
            			
        </form>
    </div>
</div>
<script>
$(document).ready(function() { 
    $("#oln").select2({
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