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
                        <h1 class="m-0">รายงานเปอร์เซ็นของกลุ่มลูกค้าทั้ง 2 แบบ</h1>
                        <!-- content -->
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <form class="form-horizontal" method='get' action='' enctype="multipart/form-data">
                                        <input type="hidden" name="controller" value="ReportCustomer">
                                        <input type="hidden" name="action" value="customer2">
                                        <div class="form-group row">
                                            <label for="" class="col-sm-2 col-form-label">วันที่เริ่ม</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="date_start" class="form-control" value="<?php echo $date_start ?>"  >
                                            </div>
                                            <label for="" class="col-sm-2 col-form-label">วันที่สิ้นสุด</label>
                                            <div class="col-sm-3">
                                                <input type="text"  name="date_end" class="form-control" value="<?php echo $date_end ?>">
                                            </div>
                                            <div class="col-sm-2">
                                                <button type="submit" class="btn btn-info">ค้นหา</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div style="text-align:center; margin-top:20px; display: none;">
                                    <a target="_blank" href="index.php?controller=ReportCustomer&action=customer_print2&date_start=<?php echo $_GET['date_start']; ?>&date_end=<?php echo $_GET['date_end'] ?>">ดาวน์โหลดรายงาน</a>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- eof -->
                    </div><!-- /.col -->

                </div><!-- /.row -->

                <div class="row mb-2">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                รายงานเปอร์เซ็นของกลุ่มลูกค้า 1 คำนวนการการขายเเต่ละครั้ง
                                <canvas id="myChart" width="100%"></canvas>
                                <br/>
                                <table width="100%">
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>กลุ่มลูกค้า</th>
                                        <th>คิดเป็นเปอร์เซ็น</th>
                                    <tr>
                                        <tbody>
                                        <?php
                                        $no = 0;
                                        foreach($cluster_name as $key => $val){
                                            $no++;
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $val; ?></td>
                                                <td><?php
                                                    if($totalAll>'0'){
                                                        echo number_format(($company[$key]/$totalAll)*100,2);
                                                    }else{
                                                        echo '0';
                                                    } ?>%</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                </table>
                                <div style="text-align:center; margin-top:20px;">
                                    <a target="_blank" href="index.php?controller=ReportCustomer&action=customer_print2&date_start=<?php echo $_GET['date_start']; ?>&date_end=<?php echo $_GET['date_end'] ?>">ดาวน์โหลดรายงาน</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                รายงานเปอร์เซ็นของกลุ่มลูกค้า 2 คำนวนจากยอดขาย
                                <canvas id="myChart2" width="100%"></canvas>
                                <br/>
                                <table width="100%">
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>กลุ่มลูกค้า</th>
                                        <th>คิดเป็นเปอร์เซ็น</th>
                                    <tr>
                                        <tbody>
                                        <?php
                                        $no = 0;
                                        foreach($cluster_name as $key => $val){
                                            $no++;
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $val; ?></td>
                                                <td><?php
                                                    if($totalAll>'0'){
                                                        echo number_format(($company[$key]/$totalAll)*100,2);
                                                    }else{
                                                        echo '0';
                                                    }
                                                    ?>%</td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        </tbody>
                                </table>
                                <div style="text-align:center; margin-top:20px;">
                                    <a target="_blank" href="index.php?controller=ReportCustomer&action=customer_print2&date_start=<?php echo $_GET['date_start']; ?>&date_end=<?php echo $_GET['date_end'] ?>">ดาวน์โหลดรายงาน</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a class="brand-link">
            <img src="AdminLTE/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">S Super Cable</span>
        </a>
        <!-- Sidebar -->
        <?php
        $user_status = $_SESSION['employee']->getUser_Status_Employee();
        if(strtolower($user_status)=='sales'){
            include("templates/sales/sidebar_menu.inc.php");
        }else if(strtolower($user_status)=='admin'){
            include("templates/admin/sidebar_menu.inc.php");
        }else if(strtolower($user_status)=='user'){
            include("templates/users/sidebar_menu.inc.php");
        }
        ?>
        <!-- /.sidebar -->
    </aside>

    <?php
    # modal dialog ( edit profile )
    include Router::getSourcePath() . "views/modal/modal_editprofile.inc.php";
    # modal dialog ( borrow manage )
    include Router::getSourcePath() . "templates/footer_page.inc.php";

    ?>



    </div>
    <?php
    $content = ob_get_clean();
    // $user_jsonencode = json_encode($user);
    // echo '<PRE>';
    // print_r(ob_get_clean());exit();
    include Router::getSourcePath() . "templates/layout.php";
} catch (Throwable $e) { // PHP 7++
    echo "การเข้าถึงถูกปฏิเสธ: ไม่ได้รับอนุญาตให้ดูหน้านี้";
    exit(1);
}
?>
<script>
    var ctx = document.getElementById('myChart').getContext('2d');

    var data = {
        labels: <?php echo $cluster_array; ?>,
        datasets: [
            {
                backgroundColor: <?php echo $bg_array; ?>,
                data: <?php echo $company_array; ?>
            },
        ]

    };


    var options = {
        tooltips: {
            enabled: false
        },
        plugins: {
            datalabels: {
                formatter: (value, ctx) => {
                    let datasets = ctx.chart.data.datasets;
                    if (datasets.indexOf(ctx.dataset) === datasets.length - 1) {
                        let sum = datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = Math.round((value / sum) * 100) + '%';
                        return percentage;
                    } else {
                        return percentage;
                    }
                },
                color: '#fff',
            }
        },
        animation: {
            onComplete: function(e) {
                var image = myChart.toBase64Image();
                console.log(image);
                //$("#test_img").attr("src",image);
                $.post( "save_img.php", { img:image } );
            }
        }
    };


    var myChart = new Chart(ctx, {
        type: 'pie',
        data: data,
        options: options
    });



    var ctx2 = document.getElementById('myChart2').getContext('2d');
    var myChart = new Chart(ctx2, {
        type: 'pie',
        data: data,
        options: options
    });


    $('input[name="date_start"]').datepicker({
        format: 'yyyy-mm-dd'

    });

    $('input[name="date_end"]').datepicker({
        format: 'yyyy-mm-dd'
    });
</script>
