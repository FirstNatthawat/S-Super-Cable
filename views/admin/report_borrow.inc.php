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
                        <h1 class="m-0">รายงานส่งเสริมการขาย</h1>

                        <!-- content -->
                        <div class="card">
                            <div class="card-body">
                                <div>
                                    <form class="form-horizontal" method='get' action='' enctype="multipart/form-data">
                                        <input type="hidden" name="controller" value="Report">
                                        <input type="hidden" name="action" value="borrow">
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
                                <canvas id="myChart" width="100%"></canvas>
                                <div style="text-align:center; margin-top:20px;">
                                    <a target="_blank" href="index.php?controller=Report&action=borrow_print&date_start=<?php echo $_GET['date_start']; ?>&date_end=<?php echo $_GET['date_end'] ?>">ดาวน์โหลดรายงาน</a> <br>
                                     <a target="_blank" href="index.php?controller=Report&action=borrow_excel&date_start=<?php echo $_GET['date_start']; ?>&date_end=<?php echo $_GET['date_end'] ?>">ดาวน์โหลด Excel</a>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
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
    labels: <?php echo $promotion_array; ?>,
    datasets: [
        {

            label: "ยืม",
            backgroundColor: "blue",
            data: <?php echo $borrow_array; ?>
        },
        {
            label: "คืน",
            backgroundColor: "red",
            data: <?php echo $borrow_return_array; ?>
        },
    ]

};

var myChart = new Chart(ctx, {

    type: 'bar',
    data: data,
    options: {
        scales: {
            y: {

                beginAtZero: true
            }
        }
    }
});

$('input[name="date_start"]').datepicker({
    format: 'yyyy-mm-dd'

});

$('input[name="date_end"]').datepicker({
    format: 'yyyy-mm-dd'
});
</script>

