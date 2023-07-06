<script
src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
<style>
    .container-fluid{
        width: 100%;
        padding-right: var(--bs-gutter-x, 0.75rem);
        padding-left: var(--bs-gutter-x, 0.75rem);
        margin-right: auto;
        margin-left: auto;
        overflow: auto;
        width: 100%;
        height: 670px;
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
</style>
<div id="tabsContent" class="jqgtabs">
    <ul>
        <li>
            <a href="#tabs-1">Home</a>
        </li>
    </ul>
    <div id="tabs-1" style="font-size:12px;" class="sum_tabs">
        <br />
        <br />
        <div id="placeholder" style="width:auto;height:720px; ">
		<?php
        require "include/koneksi.php"; 

		echo"Hi ".$_SESSION['user']['username'];
		echo"<br/> Selamat datang di CAMOU System !<br><br>";
        if($_SESSION['user']['group_id'] == '1'){
            ?>
            <div class="container-fluid">
                        <div class="row">
                            <!-- <div class="col-xl-3 col-md-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Tanggal</p><hr>
                                        <p class="text-content"><?php echo date('d/m/Y'); ?></p>
                                </div>
                                </div>
                            </div> -->
                            <div class="col-xl-3 col-md-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Perbandingan Penjualan Online Harian</p><hr>
                                        <p class="text-content">
                                        <canvas id="ChartOnline" style="width:100%;height:100%;"></canvas>
                                        </p>
                                </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Perbandingan Penjualan B2B Harian</p><hr>
                                        <p class="text-content">
                                        <canvas id="ChartB2B" style="width:100%;height:100%;"></canvas>
                                        <?php 
                                           
                                        ?></p>
                                </div>
                                </div>
                            </div>
                             <div style='width: 50%'>
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Perbandingan Penjualan Bulanan</p><hr>
                                        <p class="text-content">
                                        <canvas id="ChartOnlineBulanan" style="width:100%;height:100%;"></canvas>
                                        </p>
                                </div>
                                </div>
                            </div>
                            <!-- <div class="col-xl-3 col-md-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Total Penjualan B2B (Bulanan)</p><hr>
                                        <p class="text-content">
                                        <canvas id="ChartB2BBulanan" style="width:100%;"></canvas>
                                        <?php 
                                           
                                        ?></p>
                                </div>
                                </div>
                            </div> -->
                           <!--  <div class="col-xl-3 col-md-3">
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Total Stok</p><hr>
                                        <p class="text-content"><?php 
                                            $sqlstok = "SELECT SUM(stok) AS total FROM inventory_balance WHERE deleted=0";
                                            $rowstok=mysql_fetch_array(mysql_query($sqlstok));
                                            echo number_format($rowstok['total'],0);
                                        ?></p>
                                </div>
                                </div>
                            </div> -->
                        </div>
                        <?php
                            $sqlskrng = "SELECT SUM(total) AS total FROM `biayaoperasional` WHERE MONTH(tanggal)='".date("m")."' AND YEAR(tanggal)='".date("Y")."'";
                            $rowskrng=mysql_fetch_array(mysql_query($sqlskrng));
                            $sqlkmrn = "SELECT SUM(total) AS total FROM `biayaoperasional` WHERE MONTH(tanggal)='".date("m",strtotime('-1 months'))."' AND YEAR(tanggal)='".date("Y",strtotime('-1 months'))."'";
                            $rowkmrn=mysql_fetch_array(mysql_query($sqlkmrn));
                        ?>
                        <!-- <div class="row">
                            <div class="col-xl-12 col-md-12">
                                <div class="card ">
                                    <div class="card-body">
                                        <p class="text-title">Perbandingan Total Biaya Bulan Berjalan<br>Total <?php echo date("F Y") ?> : <?php echo number_format($rowskrng['total'])?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Total <?php echo date("F Y",strtotime('-1 months')) ?> : <?php echo number_format($rowkmrn['total'])?></p><hr>
                                        <p class="text-content">
                                        <canvas id="ChartBiayaBulan" style="width:100%;height:100%;"></canvas>
                                        </p>
                                </div>
                                </div>
                            </div>
                        </div> -->

                    </div>
            <?php
        }
		?>
		</div>
		                
    </div>

</div>
<script>
function formatnum(bilangan){
    var number_string = bilangan.toString(),
    sisa    = number_string.length % 3,
    rupiah  = number_string.substr(0, sisa),
    ribuan  = number_string.substr(sisa).match(/\d{3}/g);
        
if (ribuan) {
    separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
}
return rupiah;
}

    //harian
    // var tgl = ["",""];
    <?php 
        $sqlonline = "SELECT IFNULL((select SUM(total-exp_fee) from olnso so where deleted=0 and date(so.lastmodified)=  STR_TO_DATE('".date("d/m/Y",strtotime('-1 months'))."','%d/%m/%Y')),0) as total1, IFNULL((select SUM(total-exp_fee) from olnso so where deleted=0 and date(so.lastmodified)=  STR_TO_DATE('".date("d/m/Y")."','%d/%m/%Y')),0) as total2 ";

        // var_dump($sql1);
        $rowonline=mysql_fetch_array(mysql_query($sqlonline));
    ?>
    var tglonline = [["<?php echo date("d F Y") ?>","<?php echo 'Total : '.number_format($rowonline['total2'],0) ?>"],["<?php echo date("d F Y",strtotime('-1 months')) ?>","<?php echo 'Total : '.number_format($rowonline['total1'],0) ?>"]];

    var totalonline = [<?php echo $rowonline['total2']?>,<?php echo $rowonline['total1']?>, 0];
    // console.log(totalonline);
    <?php 
         $sqlb2b = "SELECT IFNULL((select SUM(totalfaktur) from b2bdo do where deleted=0 and date(do.tgl_trans)=  STR_TO_DATE('".date("d/m/Y",strtotime('-1 months'))."','%d/%m/%Y')),0) as total1, IFNULL((select SUM(totalfaktur) from b2bdo do where deleted=0 and date(do.tgl_trans)=  STR_TO_DATE('".date("d/m/Y")."','%d/%m/%Y')),0) as total2";
         $rowb2b=mysql_fetch_array(mysql_query($sqlb2b));
    ?>
    var tglb2b = [["<?php echo date("d F Y") ?>","<?php echo 'Total : '.number_format($rowb2b['total2'],0) ?>"],["<?php echo date("d F Y",strtotime('-1 months')) ?>","<?php echo 'Total : '.number_format($rowb2b['total1'],0) ?>"]];
    var totalb2b = [<?php echo $rowb2b['total2']?>,<?php echo $rowb2b['total1']?>, 0];

    var barColors = ["red", "#800000"];
    var barColors2 = ["green", "#003c00"];

    var ChartOnline = new Chart("ChartOnline", {
        type: "bar",
        data: {
            labels: tglonline,
            datasets: [{
                backgroundColor: barColors,
                data: totalonline
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return value;
                            }
                        }
                    }
                }]
            },
             tooltips: {
                  callbacks: {
                      label: function(tooltipItem, data) {
                          return formatnum(tooltipItem.yLabel);
                      }
                  }
            },
// hover: {
            //     animationDuration: 1
            // },
            // animation: {
            // duration: 1,
            // onComplete: function () {
            //     var chartInstance = this.chart,
            //         ctx = chartInstance.ctx;
            //         ctx.textAlign = 'center';
            //         ctx.fillStyle = "rgba(0, 0, 0, 1)";
            //         ctx.textBaseline = 'bottom';
            //         // Loop through each data in the datasets
            //         this.data.datasets.forEach(function (dataset, i) {
            //             var meta = chartInstance.controller.getDatasetMeta(i);
            //             meta.data.forEach(function (bar, index) {
            //                 var data = dataset.data[index];
            //                 ctx.fillText(formatnum(data).replace(/\d(?=(\d{3})+\.)/g, '$&,'), bar._model.x, bar._model.y + 12);
            //             });
            //         });
            //     }
            // }
        }
    });

    var ChartB2B = new Chart("ChartB2B", {
        type: "bar",
        data: {
            labels: tglb2b,
            datasets: [{
                backgroundColor: barColors2,
                data: totalb2b
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return value;
                            }
                        }
                    }
                }]
            },
             tooltips: {
                  callbacks: {
                      label: function(tooltipItem, data) {
                          return formatnum(tooltipItem.yLabel);
                      }
                  }
            },
// hover: {
            //     animationDuration: 1
            // },
            // animation: {
            // duration: 1,
            // onComplete: function () {
            //     var chartInstance = this.chart,
            //         ctx = chartInstance.ctx;
            //         ctx.textAlign = 'center';
            //         ctx.fillStyle = "rgba(0, 0, 0, 1)";
            //         ctx.textBaseline = 'bottom';
            //         // Loop through each data in the datasets
            //         this.data.datasets.forEach(function (dataset, i) {
            //             var meta = chartInstance.controller.getDatasetMeta(i);
            //             meta.data.forEach(function (bar, index) {
            //                 var data = dataset.data[index];
            //                 ctx.fillText(formatnum(data).replace(/\d(?=(\d{3})+\.)/g, '$&,'), bar._model.x, bar._model.y + 12);
            //             });
            //         });
            //     }
            // }
        }
    });

   
   //bulanan
   
    <?php 
        $sqlonlineBulanan = "SELECT IFNULL((select SUM(total-exp_fee) from olnso so where deleted=0
and IF(month(so.lastmodified) < 10, CONCAT('0',month(so.lastmodified)),month(so.lastmodified))='".date("m",strtotime('-1 months'))."'
and year(so.lastmodified) = '".date("Y",strtotime('-1 months'))."' ),0) as total1, IFNULL((select SUM(total-exp_fee) from olnso so where deleted=0
and IF(month(so.lastmodified) < 10, CONCAT('0',month(so.lastmodified)),month(so.lastmodified))='".date("m")."'
and year(so.lastmodified) = '".date("Y")."' ),0) as total2 ";

        // var_dump($sql1);
        $rowonlineBulanan=mysql_fetch_array(mysql_query($sqlonlineBulanan));
    ?>
    
    var totalonlineBulanan = [<?php echo $rowonlineBulanan['total2']?>,<?php echo $rowonlineBulanan['total1']?>, 0];
    // console.log(totalonline);
    <?php 
         $sqlb2bBulanan = "SELECT IFNULL((select SUM(totalfaktur) from b2bdo do where deleted=0
and IF(month(do.tgl_trans) < 10, CONCAT('0',month(do.tgl_trans)),month(do.tgl_trans))='".date("m",strtotime('-1 months'))."'
and year(do.tgl_trans) = '".date("Y",strtotime('-1 months'))."' ),0) as total1, IFNULL((select SUM(totalfaktur) from b2bdo do where deleted=0
and IF(month(do.tgl_trans) < 10, CONCAT('0',month(do.tgl_trans)),month(do.tgl_trans))='".date("m")."'
and year(do.tgl_trans) = '".date("Y")."' ),0) as total2 ";

         $rowb2bBulanan=mysql_fetch_array(mysql_query($sqlb2bBulanan));
    ?>

    var tglonlineBulanan = [["<?php echo date("F Y") ?>","<?php echo 'Total : ' .number_format(($rowonlineBulanan['total2']+$rowb2bBulanan['total2']),0) ?>"],["<?php echo date("F Y",strtotime('-1 months')) ?>","<?php echo 'Total : '.number_format(($rowonlineBulanan['total1']+$rowb2bBulanan['total1']),0) ?>"]];

    var data1 = [<?php echo $rowonlineBulanan['total2']?>,<?php echo $rowonlineBulanan['total1']?>];

    var data2 = [<?php echo $rowb2bBulanan['total2']?>,<?php echo $rowb2bBulanan['total1']?>];




    var tglb2bBulanan = [["<?php echo date("m/Y") ?>","<?php echo number_format($rowb2bBulanan['total2'],0) ?>"],["<?php echo date("m/Y",strtotime('-1 months')) ?>","<?php echo number_format($rowb2bBulanan['total1'],0) ?>"]];
    var totalb2bBulanan = [<?php echo $rowb2bBulanan['total2']?>,<?php echo $rowb2bBulanan['total1']?>, 0,];

    var barColors = ["#800000", "#003c00"];

    var ChartOnlineBulanan = new Chart("ChartOnlineBulanan", {
        type: "bar",
        data: {
            labels: tglonlineBulanan,
              datasets: [{
                label: "Red",
                backgroundColor: ["red", "#800000"],
                data: data1
              }, {
                label: "Green",
                backgroundColor: ["green", "#003c00"],
                data: data2
              }]
            // datasets: [{
            //     backgroundColor: barColors,
            //     data: totalonlineBulanan
            // }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return value;
                            }
                        }
                    }
                }]
            },
             tooltips: {
                  callbacks: {
                      label: function(tooltipItem, data) {
                          return formatnum(tooltipItem.yLabel);
                      }
                  }
            },
            hover: {
                animationDuration: 1
            },
            animation: {
            duration: 1,
            onComplete: function () {
                var chartInstance = this.chart,
                    ctx = chartInstance.ctx;
                    ctx.textAlign = 'center';
                    ctx.fillStyle = "rgba(0, 0, 0, 1)";
                    ctx.textBaseline = 'bottom';
                    var kolom = ["OLN","B2B","OLN","B2B"];
                    // Loop through each data in the datasets
                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            var data = kolom[i];
                            ctx.fillText(data, bar._model.x, bar._model.y - 5);
                        });
                    });
                }
            }
        }
    });

    var ChartB2BBulanan = new Chart("ChartB2BBulanan", {
        type: "bar",
        data: {
            labels: tglb2bBulanan,
            datasets: [{
                backgroundColor: barColors,
                data: totalb2bBulanan
            }]
        },
      
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return value;
                            }
                        }
                    }
                }]
            },
             tooltips: {
                  callbacks: {
                      label: function(tooltipItem, data) {
                          return formatnum(tooltipItem.yLabel);
                      }
                  }
            },
            // hover: {
            //     animationDuration: 1
            // },
            // animation: {
            // duration: 1,
            // onComplete: function () {
            //     var chartInstance = this.chart,
            //         ctx = chartInstance.ctx;
            //         ctx.textAlign = 'center';
            //         ctx.fillStyle = "rgba(0, 0, 0, 1)";
            //         ctx.textBaseline = 'bottom';
            //         // Loop through each data in the datasets
            //         this.data.datasets.forEach(function (dataset, i) {
            //             var meta = chartInstance.controller.getDatasetMeta(i);
            //             meta.data.forEach(function (bar, index) {
            //                 var data = dataset.data[index];
            //                 ctx.fillText(formatnum(data).replace(/\d(?=(\d{3})+\.)/g, '$&,'), bar._model.x, bar._model.y + 12);
            //             });
            //         });
            //     }
            // }
        }
    });

    <?php 
        $arrbiayabulan = array();
        $arrtotalbiayabulan = array();
        $tahun = date('Y'); //Mengambil tahun saat ini
        $bulan = date('m'); //Mengambil bulan saat ini
        $tanggal = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $sqlbiayabulan = '';
        $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
        for ($i=1; $i < $tanggal+1; $i++) { 
            $day = $i;
            if($i<10){
                $day = '0'.$i;
            }
            if(($i+1) == ($tanggal+1)){
                $sqlbiayabulan .= " SELECT IFNULL(DATE_FORMAT(a.tanggal,'%d %M %Y'),'$day ".$months[($bulan-1)]." $tahun') AS tgl, IFNULL(SUM(total),0) AS total FROM biayaoperasional a WHERE deleted=0 AND DATE(a.tanggal) = STR_TO_DATE('$day/$bulan/$tahun','%d/%m/%Y') ";
            }else{
                $sqlbiayabulan .= " SELECT IFNULL(DATE_FORMAT(a.tanggal,'%d %M %Y'),'$day ".$months[($bulan-1)]." $tahun') AS tgl, IFNULL(SUM(total),0) AS total FROM biayaoperasional a WHERE deleted=0 AND DATE(a.tanggal) = STR_TO_DATE('$day/$bulan/$tahun','%d/%m/%Y') UNION ALL ";
            }

            
        }
        
        $result=mysql_query($sqlbiayabulan);
        while ($row=mysql_fetch_array($result,MYSQL_ASSOC))
        {
            array_push($arrbiayabulan,array($row['tgl'],'Total : '.number_format($row['total'],0)));
            array_push($arrtotalbiayabulan,$row['total']);
        }
        array_push($arrtotalbiayabulan,0);
    ?>
    var tglbiayabulan = <?php echo json_encode($arrbiayabulan) ?>;
    var totalbiayabulan = <?php echo json_encode($arrtotalbiayabulan) ?>;
    console.log(totalbiayabulan);
    console.log(totalonline);
    var ChartBiayaBulan = new Chart("ChartBiayaBulan", {
        type: "bar",
        data: {
            labels: tglbiayabulan,
            datasets: [{
                backgroundColor: "#33AEEF",
                data: totalbiayabulan
            }]
        },
        options: {
            legend: {display: false},
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        callback: function(value, index, values) {
                            if(parseInt(value) >= 1000){
                                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                            } else {
                                return value;
                            }
                        }
                    }
                }]
            },
             tooltips: {
                  callbacks: {
                      label: function(tooltipItem, data) {
                          return formatnum(tooltipItem.yLabel);
                      }
                  }
            },
// hover: {
            //     animationDuration: 1
            // },
            // animation: {
            // duration: 1,
            // onComplete: function () {
            //     var chartInstance = this.chart,
            //         ctx = chartInstance.ctx;
            //         ctx.textAlign = 'center';
            //         ctx.fillStyle = "rgba(0, 0, 0, 1)";
            //         ctx.textBaseline = 'bottom';
            //         // Loop through each data in the datasets
            //         this.data.datasets.forEach(function (dataset, i) {
            //             var meta = chartInstance.controller.getDatasetMeta(i);
            //             meta.data.forEach(function (bar, index) {
            //                 var data = dataset.data[index];
            //                 ctx.fillText(formatnum(data).replace(/\d(?=(\d{3})+\.)/g, '$&,'), bar._model.x, bar._model.y + 12);
            //             });
            //         });
            //     }
            // }
        }
    });
    // console.log(tglbiayabulan);

</script>