<link rel="stylesheet" type="text/css" href="assets/css/jquery.autocomplete.css" />

<script type="text/javascript" src="assets/js/jquery.autocomplete.js"></script>

<style>
  .ac_results {
    z-index: 9999;
  }

  .hide {
    display: none;
  }

  .penyusutan-field-full {
    height: 15px;
    width: 200px;
  }

  .penyusutan-select-field-full {
    width: 212px;
  }

  .penyusutan-select-field-quarter {
    width: 62px;
  }

  .penyusutan-field-3quarter {
    height: 15px;
    width: 135px;
  }

  .penyusutan-field-half {
    height: 15px;
    width: 96px;
  }

  .penyusutan-width {
    width: 200px;
  }

  .penyusutan-select-field-half {
    width: 97px;
  }

  .penyusutan-disabled {
    background-color: #b6b4ac;
  }
</style>

<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    <?php
    $action = strtoupper($_GET['action']);
    echo $action . ' Products';
    ?>
  </div>

  <div class="ui-widget-content ui-corner-bottom">
    <form id="purchproducts_form" method="post" action="<?= BASE_URL ?>pages/master_purchase/purchproducts.php?action=process" class="ui-helper-clear-fix">
      <?php if (strtolower($_GET['action']) == 'edit') {
        echo '<input value="' . $_GET['id'] . '" type="hidden" class="required" id="id" name="id">';

        $select = $db->prepare('SELECT * FROM `mst_produk` WHERE id = :id');
        $select->execute(array(':id' => $_GET['id']));

        $row    = $select->fetch(PDO::FETCH_ASSOC);
      } else {
        $row = 0;
      }; ?>
      <label for="produk_jasa" class="ui-helper-reset label-control">Produk / Jasa</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['produk_jasa']) ? $row['produk_jasa'] : ''; ?>" type="text" id="produk_jasa" name="produk_jasa" autocomplete="off">
      </div>

      <label for="harga" class="ui-helper-reset label-control">Kategori Pembelian</label>
      <div class="ui-corner-all form-control">
        <select class="required" id="aset_produk" name="kode">
          <option value='0' selected>Biaya</option>
          <option value='1'>Harta Berwujud Kel. 1 (4 Tahun)</option>
          <option value='2'>Harta Berwujud Kel. 2 (8 Tahun)</option>
          <option value='5'>Harta Berwujud Kel. 5 (20 Tahun)</option>
          <option value='6'>Tax Amnesti</option>
        </select>
      </div>

      <label for="supplier" class="ui-helper-reset label-control" hidden>Supplier</label>
      <div class="ui-corner-all form-control" hidden>
        <input value="<?php if (isset($row['id_supplier'])) {
                        $getSupplier = $db->prepare('SELECT `vendor` FROM `mst_supplier` WHERE id=:id');
                        $getSupplier->execute(array(':id' => $row['id_supplier']));

                        $supplier = $getSupplier->fetch(PDO::FETCH_ASSOC);
                        echo $supplier['vendor'];
                      } else {
                        echo '';
                      }; ?>" type="text" id="supplier" name="supplier" autocomplete="off">
      </div>

      <label for="tgl_quotation" class="ui-helper-reset label-control">Tanggal Quotation</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['tgl_quotation']) ? $row['tgl_quotation'] : ''; ?>" type="date" id="tgl_quotation" name="tgl_quotation" autocomplete="off">
      </div>

      <label for="satuan" class="ui-helper-reset label-control">Satuan</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['satuan']) ? $row['satuan'] : ''; ?>" type="text" id="satuan" name="satuan" autocomplete="off">
      </div>

      <label for="harga" class="ui-helper-reset label-control">DPP/Unit</label>
      <div class="ui-corner-all form-control">
        <input class="required" value="<?= isset($row['harga']) ? $row['harga'] : ''; ?>" type="text" id="harga" name="harga" autocomplete="off">
      </div>

      <label id="acc_label" for="harga" class="ui-helper-reset label-control">Akun (1,5,6)</label>
      <div class="ui-corner-all form-control" id="acc">
        <input class="" value="<?= isset($row['id_akun']) ? $row['id_akun'] . ":" . $row['nomor_akun'] . " | " . $row['nama_akun'] : ''; ?>" type="text" id="akun_produk" name="akun_produk" autocomplete="off">
      </div>

      <div id="aktiva_form">

      </div>

    </form>
  </div>
</div>

<script>
  $(document).ready(function() {
    $("#akun_produk").autocomplete("pages/master_purchase/COALov.php?", {
      width: 200
    });

    $("#akun_produk").result(function(event, data, formatted) {
      var nama = document.getElementById("akun_produk").value;
      for (var i = 0; i < nama.length; i++) {
        var id = nama.split(';');
        if (id[1] == "") continue;
        var id_pd = id[1];
      }

      $.ajax({
        url: 'pages/master_purchase/COALoVdet.php?id=' + id_pd,
        dataType: 'json',
        data: "nama=" + formatted,
        success: function(data) {
          var id = data.id;
          var noakun = data.noakun;
          var nama = data.nama;
          $("#akun_produk").val(id + ":" + noakun + " | " + nama);
        }
      });
    });

    const asetProduk = document.getElementById('aset_produk');

    asetProduk.addEventListener('change', (e) => {
      if (e.target.value != '0') {
        const elements = [
          // '<label for="durasi-penyusutan" class="ui-helper-reset label-control">Durasi Penyusutan</label> <div class="ui-corner-all form-control"> <select  class="required penyusutan-select-field-full" id="durasi-penyusutan" name="durasi-penyusutan" required><option value="48">48</option><option value="96">96</option><option value="240">240</option></select> (Bulan)</div>',
          //   `<label for="ppn-pembelian-aset" class="ui-helper-reset label-control">PPN Pembelian</label>
          //  <div class="ui-corner-all form-control">
          //   <select  class="required penyusutan-select-field-full" id="ppn-pembelian-aset" name="ppn-pembelian-aset" required>
          //     <option value="0">Tidak</option>
          //     <option value="1">Manual</option>
          //     <option value="2" selected>Otomatis 11%</option>
          //   </select>
          //  </div>`,
          //   `<label for="" class="ui-helper-reset label-control">Nilai</label></label>
          //   <div class="ui-corner-all form-control">
          //     <input class="penyusutan-field-full penyusutan-disabled" type="text" id="ppn" name="ppn" readonly />
          //   </div>
          //  `,
          `<label class="ui-helper-reset label-control">Tipe Biaya</label><div class="ui-corner-all form-control"><div><input type="radio" name="tipe-biaya" class="tipe-biaya" id="langsung" value="langsung" checked><label for="langsung">Langsung</label></div><div><input type="radio" name="tipe-biaya" class="tipe-biaya" id="tidak-langsung" value="tidak-langsung"><label for="tidak-langsung">Tidak Langsung</label></div></div>`
        ];

        if (!$("#aktiva_form").children().length) {
          elements.forEach((el) => {
            $("#aktiva_form").append(el);
          });
        }

        // const dpp = document.getElementById('harga');
        // const ppn = document.getElementById('ppn-pembelian-aset');
        // const valuePpn = document.getElementById('ppn');

        // ppn.addEventListener('change', () => {
        //   if (ppn.value == "1") {
        //     valuePpn.classList.remove('penyusutan-disabled');
        //     valuePpn.readOnly = false;
        //     valuePpn.value = '';
        //     valuePpn.focus()
        //   } else if (ppn.value == "0") {
        //     valuePpn.classList.add('penyusutan-disabled');
        //     valuePpn.readOnly = true;
        //     valuePpn.value = 0;
        //   } else if (ppn.value == "2") {
        //     valuePpn.classList.add('penyusutan-disabled');
        //     valuePpn.readOnly = true;
        //     valuePpn.value = Math.floor(dpp.value * 0.11);
        //   }
        // })

        // dpp.addEventListener('keyup', () => {
        //   console.log("change");
        //   if (ppn.value == "1") {
        //     valuePpn.classList.remove('penyusutan-disabled');
        //     valuePpn.readOnly = false;
        //     valuePpn.value = '';
        //     valuePpn.focus()
        //   } else if (ppn.value == "0") {
        //     valuePpn.classList.add('penyusutan-disabled');
        //     valuePpn.readOnly = true;
        //     valuePpn.value = 0;
        //   } else if (ppn.value == "2") {
        //     valuePpn.classList.add('penyusutan-disabled');
        //     valuePpn.readOnly = true;
        //     valuePpn.value = Math.floor(dpp.value * 0.11);
        //   }
        // })
        $("#akun_produk").val(null)
        $("#acc_label").hide()
        $("#acc").hide()
      } else {
        $("#acc_label").show()
        $("#acc").show()
        $("#aktiva_form").empty();
      }
    });
  });
</script>