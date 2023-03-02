<?php $persen_ppn = 11 ;?>

<style>
  body {
    background-color: Moccasin;
  }

  tanggal{
    color: maroon;
    margin-left: 40px;
  }
</style>

<head>
  <title>ADD PURCHASE ORDER</title>

  <link rel="stylesheet" type="text/css" href="../../assets/css/styles.css" />
  <link rel="stylesheet" type="text/css" href="../../assets/css/jquery.autocomplete.css" />

  <script src="../../assets/js/jsbilangan.js" type="text/javascript"></script>
  <script type="text/javascript" src="../../assets/js/jquery-1.4.js"></script>
  <script type='text/javascript' src='../../assets/js/jquery.autocomplete.js'></script>
</head>

<body>
  <form id="po_add" name="po_add" action="" method="post">

    <input type="hidden" class="" name="persen_ppn" id="persen_ppn" value="0">

    <table width="100%">
      <tr>
        <td class="fontjudul">ADD PURCHASE ORDER</td>
        <td class="fontjudul">TOTAL QTY<input type="text" class="" name="total_qty" id="total_qty" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">TOTAL DPP<input type="text" class="" name="total_dpp_view" id="total_dpp_view" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" class="" name="total_dpp" id="total_dpp" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">PPN<input type="text" class="" name="ppn_view" id="ppn_view" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" class="" name="ppn" id="ppn" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul">GRAND TOTAL<input type="text" class="" name="grand_total_view" id="grand_total_view" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" class="" name="grand_total" id="grand_total" style="text-align: right; font-size: 30px; background-color: white; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
        <td class="fontjudul" style="width:18%;">GRAND TOTAL <span style="font-size:0.8em; font-weight: bold;">(+pengiriman)</span><input type="text" class="" name="grand_view" id="grand_view" style="text-align: right; font-size: 30px; background-color: white; width: 11em; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /><input type="hidden" class="" name="grand" id="grand" style="text-align: right; font-size: 30px; background-color: white; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" readonly /></td>
      </tr>
    </table>

    <hr />

    <table width="100%" cellpadding="0" cellspacing="0">
      <tr>
        <td class="fonttext">Pemohon</td>
        <td><input type="text" class="inputForm" name="pemohon" id="pemohon" /></td>
        <td class="fonttext">Supplier</td>
        <td><input type="text" class="inputForm" name="supplier" id="supplier" /></td>
      </tr>
      <tr>
        <td class="fonttext">Tanggal PO</td>
        <td><input type="date" class="inputForm" name="tanggal_po" id="tanggal_po" /></td>
        <td class="fonttext">Estimasi Pengiriman</td>
        <td><input type="date" class="inputForm" name="eta_pengiriman" id="eta_pengiriman" /></td>
      </tr>
      <tr>
        <td class="fonttext">Catatan</td>
        <td colspan="100%"><textarea type="text" class="inputForm" name="catatan" id="catatan" style="height: 40px; width: 320px;"></textarea></td>
      </tr>
      <tr height="1">
        <td colspan="100%"><hr /></td>
      </tr>
    </table>

    <table width="100%" id="po_detail">
      <thead>
        <tr>
          <td width="5%" class="fonttext">Kode</td>
          <td width="30%" class="fonttext">Produk / Jasa</td>
          <td width="15%" class="fonttext">Tanggal Quotation</td>
          <td width="30%" class="fonttext">Akun 1,5,6</td>
          <td width="30%" class="fonttext">Nama Akun</td>
          <td width="10%" class="fonttext">Qty</td>
          <td width="10%" class="fonttext">Satuan</td>
          <td width="15%" class="fonttext">DPP/Unit</td>
          <td width="15%" class="fonttext">Sub Total</td>
          <td width="5%" class="fonttext">Hapus</td>
        </tr>
      </thead>
    </table>

    <table width="100%">
      <tr>
        <td>
          <p><img src='../../assets/images/tambah_baris.png' id='baru' onClick='addNewRow1()'/></p>
        </td>
        <td>
          <p><img src='../../assets/images/simpan_cetak.png' value='Cetak' id='print' onClick='cetak()' /></p>
        </td>
        <td>
          <p><img src='../../assets/images/batal.png' id='baru' onClick='tutup()'/></p>
        </td>
        <td width="100%">
          <p class="fontjudul text-right">Biaya Pengiriman : <input type='text' id="pengiriman" name="pengiriman" style="text-align: right; font-size: 30px; background-color: white; width: 11em; height: 40px; border: 1px dotted #F30; border-radius: 4px; -moz-border-radius: 4px;" /></p>
        </td>
      </tr>
    </table>

  </form>
</body>

<script type="text/javascript">
  var baris1 = 1;

  checkMax();

  // general function ------------------------
  function hitungsubtotal(idx){
    var value = parseInt($('#qty'+idx).val())*parseFloat($('#dpp'+idx).val());
    if(isNaN(value)){
      $('#sub_total'+idx).val("0");
    }
    else{
      $('#sub_total'+idx).val(value);
    }
    
  }

  function hitungorder(){
    var totalorder = 0;

    for (var i=1; i<=baris1;i++){
      var kode=$('#id'+i).val();
      if (kode != null && kode != ''){
        totalorder = totalorder + parseFloat($('#sub_total'+i).val());
      }
    }

    $('#total_dpp').val(parseFloat(totalorder));
    $('#total_dpp_view').val(intToIDR(parseFloat(totalorder)));

    hitungppn(); hitungdpp();
  }

  function hitungppn(){
    var totalppn = 0;
    for (var i=1; i<=baris1;i++){
      var kode=$('#id'+i).val();
      var pkp=$('#pkp'+i).val();
      if (kode != null && kode != '' && pkp == '1'){
        totalppn = totalppn + (parseFloat($('#sub_total'+i).val())*<?= $persen_ppn ?>/100);
        $('#persen_ppn').val(<?= $persen_ppn ?>);
      }
    }

    $('#ppn').val(parseFloat(totalppn));
    $('#ppn_view').val(intToIDR(parseFloat(totalppn)));
  }

  function hitungdpp(){
    $('#grand_total').val((parseFloat($('#total_dpp').val())+parseFloat($('#ppn').val())));
    $('#grand_total_view').val(intToIDR(parseFloat($('#total_dpp').val())+parseFloat($('#ppn').val())));
    $('#grand_view').val(intToIDR(parseFloat($('#grand_total').val())+parseFloat($('#pengiriman').val())));
  }

  function hitungqty(){
    var totalqty= 0;

    for (var i=1; i<=baris1;i++){
      var kode=$('#id'+i).val();
      if (kode != null && kode != ''){
        totalqty = totalqty + parseInt($('#qty'+i).val());
      }
    }

    $('#total_qty').val(parseInt(totalqty));
  }

  $('#pengiriman').keyup(function(){
    checkMax(); hitungdpp();
  });

  function checkMax(){
    if($("#pengiriman").val() == ''){
      $("#pengiriman").val(0);
    }
    else if($('#pengiriman').val() > 0){
      if(($('#pengiriman').val()).substring(0,1) == "0"){
        $('#pengiriman').val($('#pengiriman').val().substring(1));
      }
    }
  }

  function checkMax(idx){
    if($("#qty"+idx).val() == ''){
      $("#qty"+idx).val(0);
    }
    else if($('#qty'+idx).val() > 0){
      if(($('#qty'+idx).val()).substring(0,1) == "0"){
        $('#qty'+idx).val($('#qty'+idx).val().substring(1));
      }
    }

    if($("#pengiriman").val() == ''){
      $("#pengiriman").val(0);
    }
    else if($('#pengiriman').val() > 0){
      if(($('#pengiriman').val()).substring(0,1) == "0"){
        $('#pengiriman').val($('#pengiriman').val().substring(1));
      }
    }
  }

  function triggerqty(idx){
    $('#qty'+idx).keyup(function(){
      checkMax(idx); hitungqty(); setTimeout(()=>{hitungorder();}, 100); hitungsubtotal(idx);
    });
    $('#id'+idx).change(function(){
      hitungqty(); setTimeout(()=>{hitungorder();}, 100); hitungsubtotal(idx);
    });
    $('#sub_total'+idx).change(function(){
      hitungqty(); setTimeout(()=>{hitungorder();}, 100); 
    })
  }

  function intToIDR(val){
    return(val.toLocaleString("id-ID", {style:"currency", currency:"IDR"}));
  }

  function cetak(){
    var pesan           = "";
    var pemohon         = $('#po_add').find('input[name="pemohon"]').val();
    var supplier        = $('#po_add').find('input[name="supplier"]').val();
    var tanggal_po      = $('#po_add').find('input[name="tanggal_po"]').val();
    var eta_pengiriman  = $('#po_add').find('input[name="eta_pengiriman"]').val();
    var total_dpp       = $('#total_dpp').val();
    var grand_total     = $('#grand_total').val();

    if(pemohon == ''){
      pesan = 'Pemohon tidak boleh kosong\n';
    }
    else if(supplier == ''){
      pesan = 'Supplier tidak boleh kosong\n';
    }
    else if(eta_pengiriman== ''){
      pesan = 'Estimasi Pengiriman tidak boleh kosong\n';
    }
    else if(tanggal_po== ''){
      pesan = 'Tanggal PO tidak boleh kosong\n';
    }
    else if((parseInt(total_dpp) < 1) && (parseInt(grand_total) < 1)){
      pesan = 'Total tidak bisa nol\n';
    }

    if(pesan != ''){
      alert('Maaf, ada kesalahan pengisian Form : \n'+pesan);
      return false;
    } else {
      var answer = confirm("Mau simpan data dan cetak datanya ?");
      if(answer){
        $('#po_add').attr('action', "po_save.php?row="+baris1).submit();
      }
    }
  }

  function tutup(){
    window.close();
  }

  // add row generate ------------------------
  function generateKode(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="id"+index; idx.id="id"+index; return idx;
  }

  function generatePKP(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="pkp"+index; idx.id="pkp"+index; return idx;
  }

  function generateNomorAkun(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="nomorAkun"+index; idx.id="nomorAkun"+index; idx.size="15" ;return idx;
  }

  function generateIdAkun(index){
    var idx = document.createElement("input");
    idx.type="hidden"; idx.name="idAkun"+index; idx.id="idAkun"+index; return idx;
  }

  function generateNamaAkun(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="namaAkun"+index; idx.id="namaAkun"+index;  idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; idx.size="30"; return idx;
  }

  function generateProdukJasa(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="produk_jasa"+index; idx.id="produk_jasa"+index; idx.size="55"; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
  }

  function generateTanggalQuotation(index){
    var idx = document.createElement("input");
    idx.type="date"; idx.name="tanggal_quotation"+index; idx.id="tanggal_quotation"+index; idx.size="15"; idx.readOnly="readonly"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
  }

  function generateQuantity(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="qty"+index; idx.id="qty"+index; idx.value="0"; idx.style.textAlign = "right"; idx.size=15; return idx;
  }

  function generateDPPUnit(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="dpp"+index; idx.id="dpp"+index; idx.readOnly="readonly"; idx.style.textAlign = "right"; idx.style.backgroundColor="#dcdcdc "; idx.style.border="#4f4f4f dotted 1px"; return idx;
  }

  function generateSatuan(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="satuan"+index; idx.id="satuan"+index; idx.readOnly="readonly"; idx.style.textAlign = "center"; idx.className="disabled"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
  }

  function generateSubTotal(index){
    var idx = document.createElement("input");
    idx.type="text"; idx.name="sub_total"+index; idx.id="sub_total"+index; idx.readOnly="readonly"; idx.style.textAlign = "right"; idx.style.backgroundColor="#dcdcdc"; idx.style.border="#4f4f4f dotted 1px"; return idx;
  }

  function generateDelete(index){
    var idx = document.createElement("input");
    idx.type = "button"; idx.name = "del1"+index+""; idx.id = "del1"+index+""; idx.size = "10"; idx.value = "X"; idx.onclick = "delRow1("+index+")"; return idx;
  }

  function delRow1(index){
    var element = document.getElementById("t1"+index); element.remove(); hitungqty();
  }

  // products autocomplete ----------------------
    $("#pemohon").autocomplete("popemohon_list.php", {width: 400});
    $("#supplier").autocomplete("posupplier_list.php", {width: 400});

    var sup_q = "";
    $('#supplier').result(function(){
      var sup = ($('#supplier').val()).split(':');
      sup_q = sup[0];

      for(var i = 0; i<=baris1; i++){
        var element = $("#t1"+i);
        if(element != null){
          element.remove(); hitungqty(); hitungorder();
        }
      }
      baris1 = 1;
      addNewRow1();
    });

    function get_akun(a) {
			$("#nomorAkun" + a + "").autocomplete("COALov.php?", {
				width: 178
			});
			//   console.log('here'+a)  ;
			$("#nomorAkun" + a + "").result(function (event, data, formatted) {
				var nama = document.getElementById("nomorAkun" + a + "").value;
				for (var i = 0; i < nama.length; i++) {
					var id = nama.split(';');
					if (id[1] == "") continue;
					var id_pd = id[1];
				}
				// console.log(id_pd);
				$.ajax({
					url: 'COALoVdet.php?id=' + id_pd,
					dataType: 'json',
					data: "nama=" + formatted,
					success: function (data) {
						var id = data.id;
						$("#idAkun" + a + "").val(id);
						var noakun = data.noakun;
						$("#nomorAkun" + a + "").val(noakun);
						var nama = data.nama;
						$("#namaAkun" + a + "").val(nama);
					}
				});

			});
		}

    function get_products(a){
      $("#id"+a).autocomplete("poproduk_list.php?sup="+sup_q, {width: 400});
      $("#id"+a).result(function(event, data, formatted){
        var nama = $('#id'+a).val();
        var id = nama.split(':');
        var id_pd = id[0];

        $.ajax({
          url       : 'poproduk_lookup_detail.php?id='+id_pd,
          dataType  : 'json',
          data      : 'nama='+formatted,
          success   : function(data){
            var products = data.produk_jasa;
              $('#produk_jasa'+a).val(products);
            var tgl_quotation = data.tgl_quotation;
              $('#tanggal_quotation'+a).val(tgl_quotation);
            var idAkun = data.id_akun;
              $('#idAkun'+a).val(idAkun);
            var nomorAkun = data.nomor_akun;
              $('#nomorAkun'+a).val(nomorAkun);
            var namaAkun = data.nama_akun;
              $('#namaAkun'+a).val(namaAkun);
            var harga = data.harga;
              $('#dpp'+a).val(harga);
            var pkp = data.pkp;
              $('#pkp'+a).val(pkp);
            var satuan = data.satuan;
              $('#satuan'+a).val(satuan);
          }
        })
      });
    };

  // add new row -----------------------------
  addNewRow1();

  function addNewRow1(){
    var tbl = document.getElementById('po_detail');
    var row = tbl.insertRow(tbl.rows.length);
    row.id = 't1'+baris1;

    var td0 = document.createElement("td");
    var td1 = document.createElement("td");
    var td2 = document.createElement("td");
    var td3 = document.createElement("td");
    var td4 = document.createElement("td");
    var td5 = document.createElement("td");
    var td6 = document.createElement("td");
    var td7 = document.createElement("td");
    var td8 = document.createElement("td");
    var td9 = document.createElement("td");

    td0.appendChild(generateKode(baris1));
    td0.appendChild(generatePKP(baris1));
    td1.appendChild(generateProdukJasa(baris1));
    td2.appendChild(generateTanggalQuotation(baris1));
    td3.appendChild(generateIdAkun(baris1));
    td3.appendChild(generateNomorAkun(baris1));
    td4.appendChild(generateNamaAkun(baris1));
    td5.appendChild(generateQuantity(baris1));
    td6.appendChild(generateSatuan(baris1));
    td7.appendChild(generateDPPUnit(baris1));
    td8.appendChild(generateSubTotal(baris1));
    td9.appendChild(generateDelete(baris1));

    row.appendChild(td0);
    row.appendChild(td1);
    row.appendChild(td2);
    row.appendChild(td3);
    row.appendChild(td4);
    row.appendChild(td5);
    row.appendChild(td6);
    row.appendChild(td7);
    row.appendChild(td8);
    row.appendChild(td9);

    document.getElementById('del1'+baris1+'').setAttribute('onclick', 'delRow1('+baris1+')'); get_products(baris1); get_akun(baris1); triggerqty(baris1); hitungsubtotal(baris1);
    baris1++;
  }

</script>