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

  if(isset($_GET['year_filter'])){
    $year_filter = $_GET['year_filter'];
  }
  else{
    $year_filter = date('Y');
  }

  $startDate = date("Y-m-01", strtotime($year_filter."-".$month_filter."-01"));
  $endDate = date("Y-m-t", strtotime($year_filter."-".$month_filter."-31"));

  $sqlb2b_query = "SELECT MONTH(tgl_trans) AS MONTH, SUM(totalfaktur) AS sum_totalfaktur FROM b2bdo WHERE deleted = 0 AND YEAR(tgl_trans) = '".$year_filter."' GROUP BY MONTH(tgl_trans)";

  $sqlolnso_query = "SELECT MONTH(lastmodified) AS MONTH, SUM(total)-SUM(exp_fee) AS sum_totalolnso FROM olnso WHERE deleted = 0 AND YEAR(lastmodified) = '".$year_filter."' GROUP BY MONTH(lastmodified)";

  $sqlolnreturn_query = "SELECT IFNULL(b.total,0) as `value`,a.num FROM (SELECT 1 as num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) a LEFT JOIN (SELECT SUM( o.total ) AS total,MONTH(o.lastmodified) as `month` FROM olnsoreturn o WHERE o.totalqty > 0 AND o.deleted = 0 AND o.state = '1' AND YEAR ( o.lastmodified ) = $year_filter GROUP BY MONTH(o.lastmodified)) b ON a.num = b.`month`";
  $sqlolnreturntotal_query = "SELECT SUM(x.value) as total_oln FROM (SELECT IFNULL(b.total,0) as `value`,a.num FROM (SELECT 1 as num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12) a LEFT JOIN (SELECT SUM( o.total ) AS total,MONTH(o.lastmodified) as `month` FROM olnsoreturn o WHERE o.totalqty > 0 AND o.deleted = 0 AND o.state = '1' AND YEAR ( o.lastmodified ) = $year_filter GROUP BY MONTH(o.lastmodified)) b ON a.num = b.`month`) x";
  
  $sqlb2breturn_query = "SELECT IFNULL(b.total,0) as `value`,a.num FROM ( SELECT 1 as num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 ) a LEFT JOIN (SELECT SUM( r.total ) AS total, MONTH(r.tgl_return) as `month` FROM b2breturn r WHERE r.post = '1' AND r.deleted = 0 AND YEAR ( r.tgl_return ) = $year_filter GROUP BY MONTH(r.tgl_return)) b ON a.num = b.`month`";
  $sqlb2breturntotal_query = "SELECT SUM(x.value) as total_b2b FROM ( SELECT IFNULL(b.total,0) as `value`,a.num FROM ( SELECT 1 as num UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 ) a LEFT JOIN (SELECT SUM( r.total ) AS total, MONTH(r.tgl_return) as `month` FROM b2breturn r WHERE r.post = '1' AND r.deleted = 0 AND YEAR ( r.tgl_return ) = $year_filter GROUP BY MONTH(r.tgl_return)) b ON a.num = b.`month` ) x";

  $sqlb2b = mysql_query($sqlb2b_query);
  $sqlolnso = mysql_query($sqlolnso_query);
  $returnoln = mysql_query($sqlolnreturn_query);
  $returnb2b = mysql_query($sqlb2breturn_query);
  $returnolntotal = mysql_fetch_array(mysql_query($sqlolnreturntotal_query))['total_oln'];
  $returnb2btotal = mysql_fetch_array(mysql_query($sqlb2breturntotal_query))['total_b2b'];

  $sqlb2b_total = mysql_query($sqlb2b_query);
  $sqlolnso_total = mysql_query($sqlolnso_query);

  $sqlb2b_month = "SELECT DAY(a.Date) AS `day`, IFNULL(b.sum_totalfaktur,0) AS sum_totalfaktur FROM (
      SELECT LAST_DAY('$endDate') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS DATE
      FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
      CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
      CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
  ) a 
  LEFT JOIN (SELECT DAY(tgl_trans) AS `day`, SUM(totalfaktur) AS sum_totalfaktur FROM b2bdo WHERE deleted = 0 AND MONTH(tgl_trans) = '$month_filter' AND YEAR(tgl_trans) = '$year_filter' GROUP BY DAY(tgl_trans)) AS b
    ON DAY(a.Date) = b.day WHERE a.Date BETWEEN '$startDate' AND LAST_DAY('$endDate') ORDER BY a.Date";

  
  $sqlolnso_month = "SELECT DAY(a.Date) AS `day`, IFNULL(b.sum_totalolnso,0) AS sum_totalolnso
  FROM (
      SELECT LAST_DAY('".$endDate."') - INTERVAL (a.a + (10 * b.a) + (100 * c.a)) DAY AS DATE
      FROM (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS a
      CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS b
      CROSS JOIN (SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9) AS c
      ) a 
      LEFT JOIN (SELECT DAY(lastmodified) AS `day`, SUM(total)-SUM(exp_fee) AS sum_totalolnso FROM olnso WHERE deleted = 0 AND MONTH(lastmodified) = '".$month_filter."' AND YEAR(lastmodified) = '".$year_filter."' GROUP BY DAY(lastmodified)) AS b
      ON DAY(a.Date) = b.day
      WHERE a.Date BETWEEN '".$startDate."' AND LAST_DAY('".$endDate."') ORDER BY a.Date";
  
  
  $sqlolnsoreturn_month = "SELECT DAY(a.date) as day,IFNULL(b.total,0) as total FROM (SELECT LAST_DAY( '$endDate' ) - INTERVAL (a.a + ( 10 * b.a ) + ( 100 * c.a )) DAY AS `date` FROM ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS a
		CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS b
		CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS c 
  ) a LEFT JOIN (
		SELECT SUM(o.total) as total,DAY(o.lastmodified) as day
		FROM
			olnsoreturn o 
		WHERE
			o.totalqty > 0 
			AND o.deleted = 0 
			AND o.state = '1'
			AND MONTH(o.lastmodified) = $month_filter
			AND YEAR(o.lastmodified) = $year_filter
		GROUP BY DAY(o.lastmodified)
		ORDER BY DAY(o.lastmodified) ) b ON DAY(a.date) = b.day WHERE a.DATE BETWEEN '$startDate' AND LAST_DAY( '$endDate' ) ORDER BY a.DATE;";

  $total_return_oln_month = "SELECT SUM(x.total) as total FROM ( SELECT DAY(a.date) as day,IFNULL(b.total,0) as total FROM (SELECT LAST_DAY( '$endDate' ) - INTERVAL (a.a + ( 10 * b.a ) + ( 100 * c.a )) DAY AS `date` FROM ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS a
		CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS b
		CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 ) AS c 
  ) a LEFT JOIN ( 
		SELECT SUM(o.total) as total,DAY(o.lastmodified) as day
		FROM
			olnsoreturn o 
		WHERE
			o.totalqty > 0 
			AND o.deleted = 0 
			AND o.state = '1'
			AND MONTH(o.lastmodified) = $month_filter
			AND YEAR(o.lastmodified) = $year_filter
		GROUP BY DAY(o.lastmodified)
		ORDER BY DAY(o.lastmodified) ) b ON DAY(a.date) = b.day WHERE a.DATE BETWEEN '$startDate' AND LAST_DAY( '$endDate' ) 
  ORDER BY a.DATE) x";

  
  $sqlreturnb2b_month = "SELECT DAY(a.date) as day,IFNULL(b.total,0) as total FROM ( SELECT LAST_DAY( '$startDate' ) - INTERVAL (a.a + ( 10 * b.a ) + ( 100 * c.a )) DAY AS `date` FROM ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
    ) AS a CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
		) AS b CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
		) AS  c 
  ) a LEFT JOIN ( SELECT SUM(r.total) as total,DAY(r.tgl_return) as day FROM b2breturn r WHERE r.post = '1' AND r.deleted = 0  AND MONTH ( r.tgl_return ) = $month_filter AND YEAR ( r.tgl_return ) = $year_filter GROUP BY DAY(r.tgl_return)
    ) b ON DAY(a.date) = b.`day`
  WHERE a.date BETWEEN '$startDate' AND LAST_DAY( '$endDate' ) ORDER BY a.date";

  $sqlreturnb2b_month_total = "SELECT SUM(x.total) as total FROM( SELECT DAY(a.date) as day,IFNULL(b.total,0) as total FROM ( SELECT LAST_DAY( '$startDate' ) - INTERVAL (a.a + ( 10 * b.a ) + ( 100 * c.a )) DAY AS `date` FROM ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
    ) AS a CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
		) AS b CROSS JOIN ( SELECT 0 AS a UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9 
		) AS  c 
  ) a LEFT JOIN ( SELECT SUM(r.total) as total,DAY(r.tgl_return) as day FROM b2breturn r WHERE r.post = '1' AND r.deleted = 0  AND MONTH ( r.tgl_return ) = $month_filter AND YEAR ( r.tgl_return ) = $year_filter GROUP BY DAY(r.tgl_return)
    ) b ON DAY(a.date) = b.`day`
  WHERE a.date BETWEEN '$startDate' AND LAST_DAY( '$endDate' ) ORDER BY a.date) x";

  $sqlnet = "SELECT IFNULL(oln.`month`,b2b.`month`) as month,(oln.netoln + b2b.netb2b ) as net FROM (
		SELECT a.`month`,IFNULL((a.sum_totalfaktur - IFNULL(b.total_r_b2b,0)),0	) as netb2b FROM (
    SELECT MONTH(tgl_trans) AS `month`, SUM(totalfaktur) AS sum_totalfaktur FROM b2bdo WHERE deleted = 0 AND YEAR(tgl_trans) = $year_filter GROUP BY MONTH(tgl_trans)
  ) a LEFT JOIN (
    SELECT MONTH(r.tgl_return) as `month`, SUM(r.total) as total_r_b2b FROM b2breturn r WHERE r.deleted = 0 AND r.post = 1 AND YEAR(r.tgl_return ) = $year_filter GROUP BY MONTH(r.tgl_return)
  ) b ON a.`month` = b.`month`
) b2b LEFT JOIN (
	SELECT a.`month`,(a.sum_totalolnso - b.total) as netoln FROM (
    SELECT MONTH(lastmodified) AS `month`, SUM(total)-SUM(exp_fee) AS sum_totalolnso FROM olnso WHERE deleted = 0 AND YEAR(lastmodified) = $year_filter GROUP BY MONTH(lastmodified)
  ) a JOIN (
    SELECT MONTH(lastmodified) as `month`,SUM(total) as total FROM olnsoreturn r WHERE deleted = 0 AND r.state = '1' AND YEAR(lastmodified) = $year_filter GROUP BY MONTH(lastmodified)
  ) b ON a.month = b.month
) oln ON oln.`month` = b2b.`month`";

  
  $sqlb2b_total_month = mysql_query($sqlb2b_month);
  $sqlolnso_total_month = mysql_query($sqlolnso_month);
  $net = mysql_query($sqlnet);

  $datanet = [];
  $total_net = 0;
  while($dt = mysql_fetch_array($net)) {
    $datanet[] = $dt['net'];
    $total_net += (int)$dt['net'];
  }
  
  $sqlb2b_month = mysql_query($sqlb2b_month);
  $sqlolnso_month = mysql_query($sqlolnso_month);
  $sqlolnsoreturn_month = mysql_query($sqlolnsoreturn_month);
  $sqlolb2breturn_month = mysql_query($sqlreturnb2b_month);

  $total_return_oln = mysql_fetch_array(mysql_query($total_return_oln_month))['total'];
  $total_return_b2b = mysql_fetch_array(mysql_query($sqlreturnb2b_month_total))['total'];

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
                    <td><select type="text" class="required" id="year_filter" name="year_filter">
                      <?php for($i = date('Y'); $i > 2021; $i -= 1){ ?>
                        <option value='<?= $i ?>'><?= $i ?></option>
                      <?php } ?>
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
              <p class="text-title">Perincian Penjualan B2B - OLN <?= date("F", strtotime("2023-".$month_filter."-01")); ?> <?= $year_filter ?></p><hr>
              <canvas id="penjualan-upper" style="width:calc(100% - 40px);height:250px;"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12">
          <div class="card ">
            <div class="card-body">
              <p class="text-title">Perbandingan Penjualan B2B - OLN <?= $year_filter ?></p><hr>
              <canvas id="penjualan-lower" style="width:calc(100% - 40px);height:250px"></canvas>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-xl-12">
          <div class="card ">
            <div class="card-body">
              <p class="text-title">Penjualan Netto Tahun <?= $year_filter ?></p><hr>
              <canvas id="penjualan-net" style="width:calc(100% - 40px);height:250px"></canvas>
            </div>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</div>

<script>

  const cty = document.getElementById('penjualan-upper');
  const ctx = document.getElementById('penjualan-lower');
  const ctn = document.getElementById('penjualan-net');

  <?php 
  $total_b2b = 0;
  $this_b2b = 0;
  $i = 0;
  while($row_b2b = mysql_fetch_array($sqlb2b_total)) {$total_b2b += $row_b2b['sum_totalfaktur']; if(($i+1) == trim($month_filter)){$this_b2b = $row_b2b['sum_totalfaktur'];} $i++;}

  $total_olnso = 0;
  $this_olnso = 0;
  $i = 0;
  while($row_olnso = mysql_fetch_array($sqlolnso_total)) {$total_olnso += $row_olnso['sum_totalolnso']; if(($i+1) == trim($month_filter)){$this_olnso = $row_olnso['sum_totalolnso'];} $i++;}
  ?>

  const labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November' ,'December'];
  const data = {
    labels: labels,
    datasets: [
      {
        label: 'B2B Sales : Rp <?= number_format($total_b2b,0) ?>',
        data: [<?php while($row_b2b = mysql_fetch_array($sqlb2b)) {?><?= $row_b2b['sum_totalfaktur'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgb(75, 192, 192)',
        tension: 0.1
      },
      {
        label: 'OLN Sales : Rp <?= number_format($total_olnso,0) ?>',
        data: [<?php while($row_olnso = mysql_fetch_array($sqlolnso)) {?><?= $row_olnso['sum_totalolnso'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(192, 75, 75)',
        backgroundColor: 'rgb(192, 75, 75)',
        tension: 0.1
      },
      {
        label: 'OLN Return : Rp <?= number_format($returnolntotal,0) ?>',
        data: [<?php while($row_olnsoreturn = mysql_fetch_array($returnoln)) {?><?= $row_olnsoreturn['value'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(75, 192, 75)',
        backgroundColor: 'rgb(75, 192, 75)',
        tension: 0.1
      },
      {
        label: 'B2B Return : Rp <?= number_format($returnb2btotal,0) ?>',
        data: [<?php while($row_b2breturn = mysql_fetch_array($returnb2b)) {?><?= $row_b2breturn['value'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(192, 192, 75)',
        backgroundColor: 'rgb(192, 192, 75)',
        tension: 0.1
      }
    ]
  };

  const options = {
    scales: {
      y: {
        beginAtZero: true
      }
    },
    plugins: {
      tooltip: {
        callbacks: {
          label: (context) => {
            const label = context.dataset.label;
            const value = context.parsed.y;
            const formattedValue = new Intl.NumberFormat('en-US', {}).format(value);
            if (label.includes("OLN Sales")) {
              return `OLN Sales : Rp ${formattedValue}`;
            } else if(label.includes("B2B Sales")) {
              return `B2B Sales : Rp ${formattedValue}`; 
            } else if(label.includes("OLN Return")) {
              return `OLN Return : Rp ${formattedValue}`;
            } else if(label.includes("B2B Return")) {
              return `B2B Return : Rp ${formattedValue}`;
            } else if(label.includes("Rp.")) {
              return `Rp. ${formattedValue}`
            }
          },
        },
      },
    },
  };

  new Chart(ctx, {
    type: 'bar',
    data: data,
    options: options
  });

  const labels_month = [<?php
  $startDate = date("Y-m-01", strtotime(date('Y')."-".$month_filter."-01"));
  $endDate = date("Y-m-t", strtotime(date('Y')."-".$month_filter."-01"));
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
        label: 'B2B Sales : Rp <?= number_format($this_b2b,0) ?>',
        data: [<?php while($row_b2b = mysql_fetch_array($sqlb2b_month)) {?><?= $row_b2b['sum_totalfaktur'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(75, 192, 192)',
        backgroundColor: 'rgb(75, 192, 192)',
        tension: 0.1
      },
      {
        label: 'OLN Sales : Rp <?= number_format($this_olnso,0) ?>',
        data: [<?php while($row_olnso = mysql_fetch_array($sqlolnso_month)) {?><?= $row_olnso['sum_totalolnso'] ?>,<?php } ?>],
        fill: false,
        borderColor: 'rgb(192, 75, 75)',
        backgroundColor: 'rgb(192, 75, 75)',
        tension: 0.1
      },
      {
        label: 'OLN Return : Rp <?= number_format($total_return_oln,0) ?>',
        data: [
          <?php while($row_return_oln = mysql_fetch_array($sqlolnsoreturn_month)) {?><?= $row_return_oln['total'] ?>,<?php } ?>
        ],
        fill: false,
        borderColor: 'rgb(75, 192, 75)',
        backgroundColor: 'rgb(75, 192, 75)',
        tension: 0.1
      },
      {
        label: 'B2B Return : Rp <?= number_format($total_return_b2b,0) ?>',
        data: [
          <?php while($row_return_b2b = mysql_fetch_array($sqlolb2breturn_month)) {?><?= $row_return_b2b['total'] ?>,<?php } ?>
        ],
        fill: false,
        borderColor: 'rgb(192, 192, 75)',
        backgroundColor: 'rgb(192, 192, 75)',
        tension: 0.1
      }
    ]
  };

  new Chart(cty, {
    type: 'line',
    data: data_month,
    options: options
  });


  const datanet = {
    labels: labels,
    datasets: [
      {
        label: 'Rp. <?= number_format($total_net,0) ?>',
        data: <?= json_encode($datanet) ?>,
        fill: false,
        borderColor: 'rgb(214, 42, 19)',
        backgroundColor: 'rgb(214, 42, 19)',
        tension: 0.1
      }
    ]
  };
  
  new Chart(ctn, {
    type: 'bar',
    data: datanet,
    options: options
  });
  
  function gridReloadTabs(){
    const month_filter = document.getElementById('month_filter').value;
    const year_filter = document.getElementById('year_filter').value;
    location.href = "<?php echo BASE_URL?>?month_filter="+month_filter+"&year_filter="+year_filter;
  }
  document.getElementById('month_filter').value = '<?= ltrim($month_filter,0) ?>';
  document.getElementById('year_filter').value = '<?= $year_filter ?>';
</script>