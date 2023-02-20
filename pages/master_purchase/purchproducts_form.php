<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    <?php
      $action = strtoupper($_GET['action']);
      echo $action.' Products';
    ?>
  </div>

  <div class="ui-widget-content ui-corner-bottom">
    <form id="purchproducts_form" method="post" action="<?= BASE_URL ?>pages/master_purchase/purchproducts.php?action=process" class="ui-helper-clear-fix">
      <?php if(strtolower($_GET['action']) == 'edit'){
        echo '<input value="'.$_GET['id'].'" type="hidden" class="required" id="id" name="id">';

        $select = $db->prepare('SELECT * FROM `mst_produk` WHERE id = :id');
        $select->execute(array(':id'=>$_GET['id']));

        $row    = $select->fetch(PDO::FETCH_ASSOC);
      } else {
        $row = 0;
      } ;?>
      <label for="produk_jasa" class="ui-helper-reset label-control">Produk / Jasa</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['produk_jasa']) ? $row['produk_jasa'] : '';?>" type="text" id="produk_jasa" name="produk_jasa" autocomplete="off">
      </div>

      <label for="supplier" class="ui-helper-reset label-control" hidden>Supplier</label>
      <div class="ui-corner-all form-control" hidden>
        <input value="<?php if(isset($row['id_supplier'])) {
          $getSupplier = $db->prepare('SELECT `vendor` FROM `mst_supplier` WHERE id=:id');
          $getSupplier->execute(array(':id'=>$row['id_supplier']));

          $supplier = $getSupplier->fetch(PDO::FETCH_ASSOC);
          echo $supplier['vendor'];
          } else {
            echo '';
          } ;?>" type="text" id="supplier" name="supplier" autocomplete="off">
      </div>

      <label for="tgl_quotation" class="ui-helper-reset label-control">Tanggal Quotation</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['tgl_quotation']) ? $row['tgl_quotation'] : '';?>" type="date" id="tgl_quotation" name="tgl_quotation" autocomplete="off">
      </div>

      <label for="satuan" class="ui-helper-reset label-control">Satuan</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['satuan']) ? $row['satuan']: '';?>" type="text" id="satuan" name="satuan" autocomplete="off">
      </div>

      <label for="harga" class="ui-helper-reset label-control">DPP/Unit</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['harga']) ? $row['harga'] : '';?>" type="text" id="harga" name="harga" autocomplete="off">
      </div>

      <label for="kategori" class="label-control">Kategori</label>
      <div class="ui-corner-all form-control">
        <select class="required" value="<?= isset($row['kategori']) ? strtolower($row['kategori']) : '';?>" id="kategori" name="kategori">
          <option  hidden value="<?= isset($row['kategori']) ? strtolower($row['kategori']) : ''; ?>">-choose(pilih)-</option>
          <option value="biaya" <?= (strtolower($row['kategori']) == "biaya") ? 'selected' : '' ?> <?= isset($row['kategori']) ? '' : 'selected'; ?>>Biaya</option>
          <option value="stock" <?= (strtolower($row['kategori']) == "stock") ? 'selected' : '' ?>>Stock</option>
          <option value="aset" <?= (strtolower($row['kategori']) == "aset") ? 'selected' : '' ?>>Aset</option>
        </select>
      </div>

      <label id="label_penyusutan" for="penyusutan" class="label-control">Bulan Penyusutan</label>
      <div id="container_penyusutan" class="ui-corner-all form-control">
        <input value="<?= isset($row['penyusutan']) ? $row['penyusutan'] : '';?>" type="text" id="penyusutan" name="penyusutan" autocomplete="off">
      </div>

      <label id="label_hpp" for="hpp" class="label-control">Mempengaruhi HPP</label>
      <div id="containter_hpp" class="ui-corner-all form-control">
        <select value="<?= $row['hpp'] ;?>" id="hpp" name="hpp">
          <option value="1">Iya</option>
          <option value="0" <?= isset($row['hpp']) ? '' : 'selected'; ?>>Tidak</option>
        </select>
      </div>
    </form>
  </div>
</div>

<script>

  $(document).ready(function(){
    $('#label_penyusutan').hide();
    $('#penyusutan').hide();

    <?= (strtolower($row['kategori']) == "aset") ? "$('#label_penyusutan').show(); $('#penyusutan').show(); $('#penyusutan').addClass('required');" : ''; ?>

    $('#kategori').change(function(){
      if($('#kategori').val()=='aset'){
        $('#label_penyusutan').show(); $('#penyusutan').show(); $('#penyusutan').addClass('required');
      } else {
        $('#label_penyusutan').hide(); $('#penyusutan').hide(); $('#penyusutan').removeClass('required'); $('#penyusutan').val('0');
      }
    });
  });
</script>