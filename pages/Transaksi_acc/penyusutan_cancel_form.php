<style>
  .penyusutan-field-full{
    height: 15px;
    width: 200px;
  }

  .penyusutan-select-field-full{
    width: 212px;
  }

  .penyusutan-select-field-quarter{
    width: 62px;
  }

  .penyusutan-select-field-half{
    width: 100px;
  }

  .penyusutan-field-3quarter{
    height: 15px;
    width: 135px;
  }

  .penyusutan-field-half{
    height: 15px;
    width: 96px;
  }

  .penyusutan-width{
    width: 200px;
  }

  .penyusutan-disabled{
    background-color: #b6b4ac;
  }
</style>

<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    Pemberhentian Aset
  </div>

  <div class="ui-widget-content ui-corner-bottom">
    <form id="penyusutan_add_from" method="post" action="pages/Transaksi_acc/penyusutan.php?action=cancel_aset_proses" class="ui-helper-clear-fix">

      <label for="nama-aset" class="ui-helper-reset label-control">Nama Aset</label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full penyusutan-disabled" type="text" id="nama-aset" name="nama-aset" readonly value="<?= $_GET['aset'] ?>" />
      </div>

      <label for="tanggal-pemberhentian-aset" class="ui-helper-reset label-control">Tanggal Pemberhentian</label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full" type="date" id="tanggal-pemberhentian-aset" name="tanggal-pemberhentian-aset" />
      </div>

      <label for="akun-pemberhentian-aset" class="ui-helper-reset label-control">Akun Pemberhentian</label></label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full" type="text" id="akun-pemberhentian-aset" name="akun-pemberhentian-aset" /> (Debet)
      </div>

      <label for="nilai-sisa-aset" class="ui-helper-reset label-control">Nilai Sisa Aset</label></label>
      <div class="ui-corner-all form-control">
        <input class="penyusutan-field-full penyusutan-disabled" type="text" id="nilai-sisa-aset" name="nilai-sisa-aset" value="<?= floor($_GET['sisa']) ?>" readonly />
      </div>

      <label for="nilai-pemberhentian-aset" class="ui-helper-reset label-control">Nilai Pemberhentian</label></label>
      <div class="ui-corner-all form-control">
        <input type="hidden" id="acuan-pemberhentian-aset" name="acuan-pemberhentian-aset" value="<?= floor($_GET['sisa']) ?>">
        <input class="required penyusutan-field-full penyusutan-disabled" type="text" id="nilai-pemberhentian-aset" name="nilai-pemberhentian-aset" value="<?= floor($_GET['sisa']) ?>" readonly /> (Inc Tax)
      </div>

      <label for="ppn-pemberhentian-aset" class="ui-helper-reset label-control">PPN Pemberhentian</label></label>
      <div class="ui-corner-all form-control">
        <select  class="required penyusutan-select-field-half" id="ppn-pemberhentian-aset" name="ppn-pemberhentian-aset">
          <option value='0'>Tidak</option>
          <option value='1'>Manual</option>
          <option value='2' selected>Otomatis 11%</option>
        </select>
        <input class="penyusutan-field-half penyusutan-disabled" type="text" id="nilai-ppn-pemberhentian" name="nilai-ppn-pemberhentian" readonly />
      </div>

      <br />
      * Diharapkan agar tidak menekan tombol save lebih dari satu kali.
      <br />
      * Proses ini akan menghentikan penyusutan dan akumulasi penyusutan aset <b><?= $_GET['aset'] ?></b> dimulai pada bulan tanggal pemberhentian.
    </form>
  </div>
</div>

<script>
  const ppnStatus = document.getElementById('ppn-pemberhentian-aset');
  const ppnValue = document.getElementById('nilai-ppn-pemberhentian');

  const pemberhentianValue = document.getElementById('nilai-pemberhentian-aset');
  const acuanPemberhentianValue = document.getElementById('acuan-pemberhentian-aset');

  function ppnAwal(){
    ppnValue.classList.add('penyusutan-disabled');
    ppnValue.readOnly=true;
    ppnValue.value=Math.floor(acuanPemberhentianValue.value*0.11);
    pemberhentianValue.value=parseInt(acuanPemberhentianValue.value)+parseInt(ppnValue.value);
  }

  ppnStatus.addEventListener('change',()=>{
    if(ppnStatus.value == "1"){
      ppnValue.classList.remove('penyusutan-disabled');
      ppnValue.readOnly=false;
      pemberhentianValue.value=parseInt(acuanPemberhentianValue.value)+parseInt(ppnValue.value);
    } else if(ppnStatus.value == "0"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=0;
      pemberhentianValue.value=parseInt(acuanPemberhentianValue.value);
    } else if(ppnStatus.value == "2"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=Math.floor(pemberhentianValue.value*0.11);
      pemberhentianValue.value=parseInt(acuanPemberhentianValue.value)+parseInt(ppnValue.value);
    }
  });

  ppnValue.addEventListener('keyup', ()=>{
    pemberhentianValue.value=parseInt(acuanPemberhentianValue.value)+parseInt(ppnValue.value);
  });

  pemberhentianValue.addEventListener('change', ()=>{
    if(ppnStatus.value == "2"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=Math.floor(pemberhentianValue.value*0.11);
    }
  });

  $(document).ready(function(){
    $('#akun-pemberhentian-aset').autocomplete("pages/Transaksi_acc/penyusutan_akun.php?req=pembelian", {width: 400});
    ppnAwal();
  });
</script>