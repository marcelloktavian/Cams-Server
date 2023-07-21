<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js" integrity="sha512-ElRFoEQdI5Ht6kZvyzXhYG9NqjtkmlkfYk0wr6wHxU9JEHakS7UJZNeml5ALk+8IKlU6jDgMabC3vkumRokgJA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<style>
  .container-fluid{
    padding-right: var(--bs-gutter-x, 0.75rem);
    padding-left: var(--bs-gutter-x, 0.75rem);
    margin-right: auto;
    margin-left: auto;
    overflow: auto;
    width: calc(100% - 40px);
    height: calc(700px - 20px);
    padding: 0 20px 20px 20px;
  }
  .row {
    --bs-gutter-x: 1.5rem;
    --bs-gutter-y: 0;
    display: flex;
    flex-wrap: wrap;
    margin-top: calc(-1 * var(--bs-gutter-y));
    margin-right: calc(-0.5 * var(--bs-gutter-x));
    margin-left: calc(-0.5 * var(--bs-gutter-x));
  }
  .col-xl-3 {
    flex: 0 0 auto;
    width: 25%;
  }
  .col-md-3 {
    flex: 0 0 auto;
    width: 25%;
  }
  .col-xl-12 {
    flex: 0 0 auto;
    width: 100%;
  }
  .col-md-12 {
    flex: 0 0 auto;
    width: 100%;
  }
  .card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #F3F2F1;
    background-clip: border-box;
    border: 1px solid rgba(0, 0, 0, 0.125);
    border-radius: 0.25rem;
  }
  .card-body {
    flex: 1 1 auto;
    padding: 1rem 1rem;
  }
  .text-title{
    font-size: 18px;
  }
  .text-content{
    font-size: 16px;
    height: 250px;
    width: 100%;
  }
  .chart-container{
    display: flex;
    flex-direction: column;
  }
  .row{
    display: flex;
    flex-direction: row;
  }
  .col{
    display: flex;
    flex-direction: column;
  }
</style>

<?php
  require "include/koneksi.php"; 

  if(isset($_GET['month_filter'])){
    $month_filter = $_GET['month_filter'];
  }
  else{
    $month_filter = ltrim(date("m"),0);
  }

  $sqlb2b = "SELECT MONTH(tgl_trans) AS MONTH, SUM(totalfaktur) AS sum_totalfaktur FROM b2bdo WHERE deleted = 0 AND YEAR(tgl_trans) = YEAR(CURDATE()) GROUP BY MONTH(tgl_trans)";

  $sqlb2b = mysql_query($sqlb2b);

  $sqlolnso = "SELECT MONTH(lastmodified) AS MONTH, SUM(total)-SUM(exp_fee) AS sum_totalolnso FROM olnso WHERE deleted = 0 AND YEAR(lastmodified) = YEAR(CURDATE()) GROUP BY MONTH(lastmodified)";

  $sqlolnso = mysql_query($sqlolnso);

  $sqlb2b_month = "SELECT DAY(tgl_trans) AS `day`, SUM(totalfaktur) AS sum_totalfaktur FROM b2bdo WHERE deleted = 0 AND MONTH(tgl_trans) = '".$month_filter."' AND YEAR(tgl_trans) = YEAR(CURDATE()) GROUP BY DAY(tgl_trans)";

  $sqlb2b_month = mysql_query($sqlb2b_month);

  $sqlolnso_month = "SELECT DAY(lastmodified) AS `day`, SUM(total)-SUM(exp_fee) AS sum_totalolnso FROM olnso WHERE deleted = 0 AND MONTH(lastmodified) = '".$month_filter."' AND YEAR(lastmodified) = YEAR(CURDATE()) GROUP BY DAY(lastmodified)";

  $sqlolnso_month = mysql_query($sqlolnso_month);

?>

<div id="tabsContent" class="jqgtabs">
  <ul>
    <li>
      <a href="#tabs-1">Home</a>
    </li>
  </ul>

  <div id="tabs-1" style="font-size:12px;" class="sum_tabs">
    <div class="container-fluid">
      <?php
        echo"Hi ".$_SESSION['user']['username'];
        echo"<br/> Selamat datang di CAMOU System !<br><br>";
          if($_SESSION['user']['group_id'] == '1'){
      ?>
      <div class="row">
        <div class="ui-widget ui-form" style="margin-bottom:5px;">
          <div class="ui-widget-content ui-corner-all" style="padding: 0;">
            <from id="" method="" action="" class="ui-helper-clearifx"  style="display: flex; align-items: center;">
              <label for="" class="ui-helper-reset label-control">Filter Bulan Penjualan</label>
              <div class="ui-corner-all form-control">
                <table>
                  <tr>
                    <td><select type="text" class="required" id="month_filter" name="month_filter">
                      <option value="1">Januari</option>
                      <option value="2">Februari</option>
                      <option value="3">Maret</option>
                      <option value="4">April</option>
                      <option value="5">Mei</option>
                      <option value="6">Juni</option>
                      <option value="7">Juli</option>
                      <option value="8">Agustus</option>
                      <option value="9">September</option>
                      <option value="10">Oktober</option>
                      <option value="11">November</option>
                      <option value="12">Desember</option>
                    </select></td>
                    <td><div class="ui-corner-all">
                      <button onclick="gridReloadTabs()" class="btn" type="button">Cari</button>
                    </div></td>
                  </tr>
                </table>
              </div>
            </from>
          </div>
        </div>
        <div class="col-xl-12">
          <div class="card">
            <div class="card-body">
              <p class="text-title">Perincian Penjualan B2B - OLN <?= date("F", strtotime("2023-".$month_filter."-01")); ?></p><hr>
              <canvas id="penjualan-upper" style="width:calc(100% - 40px);height:250px;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12">
          <div class="card ">
            <div class="card-body">
              <p class="text-title">Perbandingan Penjualan B2B - OLN <?= date("Y") ?></p><hr>
              <canvas id="penjualan-lower" style="width:calc(100% - 40px);height:250px"></canvas>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<script>

  const ctx = document.getElementById('penjualan-lower');
  const cty = document.getElementById('penjualan-upper');

  const labels = ['Janurai', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'December'];
  const data = {
    labels: labels,
    datasets: [
      {
        label: 'B2B Sales',
        data: [<?php while($row_b2b = mysql_fetch_array($sqlb2b)) {?><?= $row_b2b['sum_totalfaktur'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgb(75, 192, 192)',
        tension: 0.1
      },{
        label: 'OLN Sales',
        data: [<?php while($row_olnso = mysql_fetch_array($sqlolnso)) {?><?= $row_olnso['sum_totalolnso'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(192, 75, 75)',
        backgroundColor: 'rgb(192, 75, 75)',
        tension: 0.1
      }
    ]
  };
  const options = {
    scales: {
      y: {
        beginAtZero: true
      }
    }
  };

  new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
  });

  const labels_month = [<?php
  $startDate = date("Y-m-01", strtotime("2023-".$month_filter."-01"));
  $endDate = date("Y-m-t", strtotime("2023-".$month_filter."-01"));
  $currentDate = strtotime($startDate); 
  $lastDate = strtotime($endDate);

  while ($currentDate <= $lastDate) {
    echo date("d", $currentDate) . ",";
    $currentDate = strtotime("+1 day", $currentDate);
  }?>];
  const data_month = {
    labels: labels_month,
    datasets: [
      {
        label: 'B2B Sales',
        data: [<?php while($row_b2b = mysql_fetch_array($sqlb2b_month)) {?><?= $row_b2b['sum_totalfaktur'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgb(75, 192, 192)',
        tension: 0.1
      },{
        label: 'OLN Sales',
        data: [<?php while($row_olnso = mysql_fetch_array($sqlolnso_month)) {?><?= $row_olnso['sum_totalolnso'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(192, 75, 75)',
        backgroundColor: 'rgb(192, 75, 75)',
        tension: 0.1
      }
    ]
  };

  new Chart(cty, {
    type: 'line',
    data: data_month,
    options: options
  });

  function gridReloadTabs(){
    const month_filter = document.getElementById('month_filter').value;

    location.href = "<?php echo BASE_URL?>?month_filter="+month_filter+"";
  }

  document.getElementById('month_filter').value = '<?= ltrim($month_filter,0) ?>';
</script>