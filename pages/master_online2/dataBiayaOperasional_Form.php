<div class="ui-widget ui-form">
    <div class="ui-widget-header ui-corner-top padding5">
        <?php
        error_reporting(0);
            require "../../include/config.php";
            $action = strtoupper($_GET['action']);
            echo $action .' Biaya Operasional';
        ?>
    </div>
    <div class="ui-widget-content ui-corner-bottom">
        <form id="dataSupplier_form" method="post" action="<?php echo BASE_URL ?>pages/master_online/dataBiayaOperasional.php?action=process" class="ui-helper-clearfix">
            <?php
                if(strtolower($_GET['action']) == 'edit') {
                    echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';
					$id = $_GET['id'];
                    $select = $db->prepare("SELECT * FROM mst_operasional WHERE id = ? ");
                    $select->execute(array($id));
                    $row = $select->fetch(PDO::FETCH_ASSOC);
    
                }
            ?>

            <!-- Nama Biaya -->
            <label for="Nama" class="ui-helper-reset label-control">Nama Biaya</label>
            <div class="ui-corner-all form-control">
                <input value="<?php echo isset($row['namaoperasional']) ? $row['namaoperasional'] : ''; ?>" type="text" class="required" id="nama" name="nama">
            </div>

            <!-- Keterangan -->
            <label for="Nama" class="ui-helper-reset label-control">Keterangan</label>
            <div class="ui-corner-all form-control">
                <textarea id="keterangan" name="keterangan" rows="5"><?php echo isset($row['keterangan']) ? $row['keterangan'] : ''; ?></textarea> 
            </div>

        </form>
    </div>
</div>