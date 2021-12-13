<?php
try {
    $title = "S Super Cable";
    if (!isset($_SESSION['employee']) || !is_a($_SESSION['employee'], "Employee")) {
        header("Location: " . Router::getSourcePath() . "index.php");
    }
    ob_start();
?>
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
        </ul>
    </nav>
    <!-- /.navbar -->

    <div class=" content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <h1 class="m-0">แนวโน้มยอดขายแบบที่ 2 คำนวณจาก y =mx+c </h1>

                        <!-- content -->
                        <div class="card">
                            <!-- /.card-header -->
                            <div class="form-group row mt-2 mb-2 mr-1">
                                <div class="col-md-12 text-right">
                                    <!-- -->
                                    <form class="form-horizontal" method='get' action='' enctype="multipart/form-data">
                                        <input type="hidden" name="controller" value="TrendTwo">
                                        <input type="hidden" name="action" value="trend">

                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label" style="text-align:right;">ค้นหา</label>
                                            <div class="col-sm-3">
                                                <select class="form-control" name="type">
                                                    <option value="3" <?php if (isset($_GET['type']) and $_GET['type'] == '3') { ?> selected <?php } ?>>3 เดือน</option>
                                                    <option value="6" <?php if (isset($_GET['type']) and $_GET['type'] == '6') { ?> selected <?php } ?>>6 เดือน</option>
                                                    <option value="12" <?php if (isset($_GET['type']) and $_GET['type'] == '12') { ?> selected <?php } ?>>1 ปี</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-info">ค้นหา</button>
                                            </div>
                                        </div>
                                        <div class="row text-center">
                                            <div class="col col-sm-12">
                                                <?php
                                                $dayStart =  date('Y-m') . '-01';
                                                $dayEnd = date('Y-m-d');
                                                $total = Sales::sumDate($dayStart, $dayEnd);
                                                if ($total['p'] == '') {
                                                    $total['p'] = 0;
                                                }
                                                ?>
                                                <!-- ยอดปัจจุบันเดือน <strong> <?= $this->m(date('m')) ?> : <?= $total['p'] ?> </strong> บาท -->
                                                <?php if (isset($si['sum'])) { ?>
                                                    <strong> รวมยอดขายทั้งหมด <?php echo number_format($si['sum'], 2); ?> </strong>บาท
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- -->
                                </div>
                            </div>
                            <div class="card-body p-0 d-flex">
                                <?php
                                if (isset($_GET['type'])) {
                                ?>
                                    <table class="table table-md dataTable no-footer dtr-inline" width="100%">
                                        <tr>
                                            <th width="20%" style="text-align:center;">เดือน</th>
                                            <!-- <th style="text-align:center;">ยอดขาย</th> -->
                                            <th style="text-align:center;">ทำนาย</th>
                                            <th style="text-align:center;">ยอดขายจริง</th>
                                        </tr>
                                        <?php
                                        $m = number_format(date('m'));
                                        $total_sum = 0;
                                        $avg = '';
                                        $labels = [];
                                        $dataV = [];
                                        $dataActualSale = [];
                                        // array_push($dataV,  $total['p']);
                                        // array_push($labels,  $this->m(date('m')));

                                        foreach ($day as $val) {
                                            $ex = explode('-', $val["date"]);
                                            $m = number_format($ex[1]);
                                            // case : ก่อนหน้าให้ + ไป 12
                                          
                                            if($val['type'] == "previous_month" && DATE("Y") != $ex[0]){
                                                
                                                $m += 12;
                                            }
                                            $avg = ($si['slope'] * $m) + $si['intercept'];

                                            $labels[] = $this->m(date('m', strtotime("+" . ($ex[1] + 0) . " month")));


                                            $dataV[] = str_replace(',', '', number_format($avg, 2));
                                            
                                            $access_month = intval($m) - 1 >  0 ? intval($m) - 1 : 0;
                                            $actual_sale = $si["actual_sale"][$access_month];

                                            $dataActualSale[] = str_replace(',', '', number_format($actual_sale, 2));

                                            

                                        ?>
                                            <tr>
                                                <td style="text-align:center;">

                                                    <!-- <?php echo $this->m(date('m', strtotime("+" . ($ex[1] + 1) . " month"))) . " " . date('Y', strtotime("+" . ($ex[1]) . " month")) ?> -->
                                                    <?php echo $this->m(date('m', strtotime("+" . ($ex[1] + 0) . " month"))) . " " . $ex[0] ?>
                                                </td>

                                                <td style="text-align:center;">
                                                    <?php echo number_format($avg, 2); ?>&nbsp;
                                                    <span style="color:red;">
                                                        <?php
                                                        if ($val['type'] == "last_month") {
                                                            echo "*(ทำนายย้อนหลัง)";
                                                        } else {
                                                            if ($val["date"] != DATE("Y-m-d")) {
                                                                echo '*(ทำนายก่อนหน้า)';
                                                            }
                                                        };
                                                        ?>
                                                    </span>
                                                </td>
                                                <td><?php echo number_format($actual_sale, 2); ?></td>
                                            </tr>
                                        <?php
                                        }
                                        ?>
                                    </table>
                                <?php } ?>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <?php
                        if (isset($_GET['type'])) {
                        ?>
                            <div class="card">
                                <canvas id="myChart" width="100%"></canvas>
                            </div>
                        <?php } ?>
                        <!-- eof -->
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </div>
    <!-- /.content-wrapper -->
    
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a class="brand-link">
            <img src="AdminLTE/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">S Super Cable</span>
        </a>
        <!-- Sidebar -->
        <?php include("templates/admin/sidebar_menu.inc.php"); ?>
        <!-- /.sidebar -->
    </aside>

    <?php
    # modal dialog ( edit profile )
    include Router::getSourcePath() . "views/modal/modal_editprofile.inc.php";
    # modal dialog (zone manage )
    include Router::getSourcePath() . "templates/footer_page.inc.php";
    include Router::getSourcePath() . "views/modal/modal_zone_manage.inc.php";
    ?>

<?php
    $content = ob_get_clean();
    // $user_jsonencode = json_encode($user);
    // echo '<PRE>';
    // print_r(ob_get_clean());exit();
    include Router::getSourcePath() . "templates/layout.php";
} catch (Throwable $e) { // PHP 7++
    print_r($e);
    echo "การเข้าถึงถูกปฏิเสธ: ไม่ได้รับอนุญาตให้ดูหน้านี้";
    exit(1);
}
?>

<script>
    var ctx = document.getElementById('myChart').getContext('2d');;

    const labels = <?php echo json_encode($labels); ?>;
    const data = {
        labels: labels,
        datasets: [{
            label: 'ทำนาย',
            data: <?php echo json_encode($dataV); ?>,
            fill: false,
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }, {
            label: 'ยอดขายจริง',
            data: <?php echo json_encode($dataActualSale); ?>,
            fill: false,
            borderColor: '	rgb(250,128,114)',
            tension: 0.1
        }]
    };
    const myChart = new Chart(ctx, {
        type: 'line',
        data: data,
    });
</script>