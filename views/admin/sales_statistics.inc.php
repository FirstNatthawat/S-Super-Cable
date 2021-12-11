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
                        <h1 class="m-0">รายงานยอดขายแต่ละคน</h1>
                        <div class="card">
                            <div class="card-body">
                            <form id="form_search" method='post' action='' enctype="multipart/form-data" class="form-horizontal">
                                <div class="form-group row">
                                    <label for="ID_Employee" class="col-sm-2 col-form-label" style="text-align:right;">พนักงาน:</label>
                                    <div class="col-sm-4">
                                        <select class="form-control" name="ID_Employee" id="ID_Employee">
                                            <option value="">-กรุณาเลือกพนักงาน-</option>
                                            <?php
                                                foreach ($employeeList as $item) {
                                            ?>
                                                <option value="<?php echo $item->getID_Employee();?>"><?php echo $item->getName_Employee().' '.$item->getSurname_Employee(); ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-2" style="display:none;">
                                        <select class="form-control" name="year1" id="year1">
                                            <option value="">-กรุณาเลือกปี-</option>
                                            <?php
                                               for($i=date('Y')-4;$i<=date('Y');$i++){
                                            ?>
                                                <option value="<?php echo $i;?>"><?php echo $i; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary">แสดง</button>
                                    </div>
                                </div>
                            </form>
                            <div id="tb">

                            </div>
                            <div style="margin-top:50px; ">
                                <canvas id="myChart" width="400" height="400"></canvas>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
       
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
    # modal dialog (zone manage )
    include Router::getSourcePath() . "templates/footer_page.inc.php";
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

<script type="text/javascript" src="AdminLTE/assets/js/page/sales_statistics.js"></script>
<style>
.tb_center td{
    text-align: center;
}
</style>
