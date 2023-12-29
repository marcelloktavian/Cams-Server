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

  .penyusutan-select-field-half{
    width: 97px;
  }

  .penyusutan-disabled{
    background-color: #b6b4ac;
  }
</style>

<div class="ui-widget ui-form">
  <div class="ui-widget-header ui-corner-top padding5">
    Penambahan Aset
  </div>

  <div class="ui-widget-content ui-corner-bottom">
    <form id="penyusutan_add_from" method="post" action="pages/Transaksi_acc/penyusutan.php?action=tambah_aset" class="ui-helper-clear-fix">

      <label for="nama-aset" class="ui-helper-reset label-control">Nama Aset</label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full" type="text" id="nama-aset" name="nama-aset" />
      </div>

      <label for="tanggal-pembelian-aset" class="ui-helper-reset label-control">Tanggal Pembelian</label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full" type="date" id="tanggal-pembelian-aset" name="tanggal-pembelian-aset" placeholder="dd-mm-yyyy" />
      </div>

      <label for="durasi-penyusutan" class="ui-helper-reset label-control">Durasi Penyusutan</label>
      <div class="ui-corner-all form-control">
        <select  class="required penyusutan-select-field-full" id="durasi-penyusutan" name="durasi-penyusutan">
          <option value='48'>48</option>
          <option value='96'>96</option>
          <option value='240'>240</option>
          <option value='1'>Tax Amnesti</option>
        </select> (Bulan)
      </div>

      <label for="akun-pembelian-aset" class="ui-helper-reset label-control">Akun Pembelian</label></label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full" type="text" id="akun-pembelian-aset" name="akun-pembelian-aset" />
      </div>

      <label for="nilai-pembelian-aset" class="ui-helper-reset label-control">Nilai Aset</label></label>
      <div class="ui-corner-all form-control">
        <input class="required penyusutan-field-full" type="text" id="nilai-pembelian-aset" name="nilai-pembelian-aset" /> (DPP)
      </div>

      <label for="ppn-pembelian-aset" class="ui-helper-reset label-control">PPN Pembelian</label></label>
      <div class="ui-corner-all form-control">
        <select  class="required penyusutan-select-field-half" id="ppn-pembelian-aset" name="ppn-pembelian-aset">
          <option value='0'>Tidak</option>
          <option value='1'>Manual</option>
          <option value='2' selected>Otomatis 11%</option>
        </select>
        <input class="penyusutan-select-field-half penyusutan-disabled" type="text" id="nilai-ppn-aset" name="nilai-ppn-aset" readonly />
      </div>

      <label for="" class="ui-helper-reset label-control">Total Pembelian</label></label>
      <div class="ui-corner-all form-control">
        <input class="penyusutan-field-full penyusutan-disabled" type="text" id="total-pembelian" name="" readonly />
      </div>

      <label for="keterangan-penyusutan" class="ui-helper-reset label-control">Keterangan</label>
      <div class="ui-corner-all form-control">
        <textarea class="penyusutan-width" id="keterangan-penyusutan" name="keterangan-penyusutan"></textarea>
      </div>

      <label class="ui-helper-reset label-control">Tipe Biaya</label>
      <div class="ui-corner-all form-control">
        <div>
          <input type="radio" name="tipe-biaya" class="tipe-biaya" id="langsung" value="langsung" checked>
          <label for="langsung">Langsung</label>
        </div>
        <div>
          <input type="radio" name="tipe-biaya" class="tipe-biaya" id="tidak-langsung" value="tidak-langsung">
          <label for="tidak-langsung">Tidak Langsung</label>
        </div>
      </div>

      <br />
      * Data tidak akan disimpan bila nilai pembelian nol.
      <br />
      * Diharapkan agar tidak menekan tombol save lebih dari satu kali.
      <br />
      * Diharapkan agar tidak mengubah nama akun aset dan akumulasi pada coa.

    </form>
  </div>
</div>

<script>
  const ppnStatus = document.getElementById('ppn-pembelian-aset');
  const ppnValue = document.getElementById('nilai-ppn-aset');

  const nilaiAset = document.getElementById('nilai-pembelian-aset');
  const totalNilai = document.getElementById('total-pembelian');

  ppnStatus.addEventListener('change',()=>{
    if(ppnStatus.value == "1"){
      ppnValue.classList.remove('penyusutan-disabled');
      ppnValue.readOnly=false;
      totalNilai.value=parseInt(nilaiAset.value)+parseInt(ppnValue.value);
    } else if(ppnStatus.value == "0"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=0;
      totalNilai.value=parseInt(nilaiAset.value);
    } else if(ppnStatus.value == "2"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=Math.floor(nilaiAset.value*0.11);
      totalNilai.value=parseInt(nilaiAset.value)+parseInt(ppnValue.value);
    }
  });

  nilaiAset.addEventListener('keydown', ()=>{
    if(ppnStatus.value == "1"){
      ppnValue.classList.remove('penyusutan-disabled');
      ppnValue.readOnly=false;
      totalNilai.value=parseInt(nilaiAset.value)+parseInt(ppnValue.value);
    } else if(ppnStatus.value == "0"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=0;
      totalNilai.value=parseInt(nilaiAset.value);
    } else if(ppnStatus.value == "2"){
      ppnValue.classList.add('penyusutan-disabled');
      ppnValue.readOnly=true;
      ppnValue.value=Math.floor(nilaiAset.value*0.11);
      totalNilai.value=parseInt(nilaiAset.value)+parseInt(ppnValue.value);
    }
  });
  $(document).ready(function(){
    $('#akun-pembelian-aset').autocomplete("pages/Transaksi_acc/penyusutan_akun.php?req=pembelian", {width: 400});
    $(".tipe-biaya").change(() => {
      console.log($("input[name='tipe-biaya']:checked").val());
    })
  });
</script>