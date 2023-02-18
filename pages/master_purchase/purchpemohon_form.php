<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    <?php 
      $action = strtoupper($_GET['action']);
      echo $action.' Pemohon PO';
    ?>
  </div>

  <div class="ui-widget-content ui-corner-bottom">
    <form id="purhpemohon_form" method="post" action="<?= BASE_URL ?>pages/master_purchase/purchpemohon.php?action=process" class="ui-helper-clear-fix">
      <?php if(strtolower($_GET['action']) == 'edit'){
        echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';

        $select = $db->prepare('SELECT * FROM `mst_pemohon_po` WHERE id = :id');
        $select->execute(array(':id'=>$_GET['id']));

        $row    = $select->fetch(PDO::FETCH_ASSOC);
      } else {
        $row = 0;
      } ?>

      <label for="pemohon" class="ui-helper-reset label-control">Nama Pemohon</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['pemohon']) ? $row['pemohon'] : ''; ?>" type="text" id="pemohon" name="pemohon">
      </div>

      <label for="keterangan" class="ui-helper-reset label-control">Keterangan</label>
      <div class="ui-corner-all form-control">
        <textarea id="keterangan" name="keterangan"><?= isset($row['keterangan']) ? $row['keterangan'] : '';?></textarea>
      </div>

    </form>
  </div>
</div>