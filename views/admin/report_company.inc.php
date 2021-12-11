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
                        <h1 class="m-0">รายงานบริษัทลูกค้า</h1>

                        <!-- content -->
                        <div class="card">
                            <div class="card-body">
                            <div class="form-group row mt-2 mb-2 mr-1">
                                <div class="col-md-12 text-right">
                                    <form method="POST">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="com_name" id="com_name" placeholder="ชื่อบริษัท">  
                                            <!--<button type="submit" class="btn btn-primary">ค้นหา</button>-->
                                        </div>
                                    </form>                        
                                </div>
                            </div>
                            <table id="com_tb" class="table table-md">
                                <thead>
                                    <tr>
                                        <th>ชื่อบริษัท</th>
                                        <th>พนักงาน</th>
                                        <th>รายละเอียด</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <div class="" style="display:none;">
                                <div class="table-responsive">
                                    <table id="tbl_companymanagement" class="table table-md" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>เลขที่</th>
                                            <th>ชื่อบริษัท</th>
                                            <!--<th>ที่อยู่บริษัท</th>-->
                                            <!--<th>เบอร์บริษัท</th>-->
                                            <!-- <th>อีเมล์บริษัท</th>
                                            <th>เลขผู้เสียภาษี</th> -->
                                            <!--<th>วงเงินสูงสุด</th>-->
                                            <!-- <th>Credit Term</th>
                                             <th>Cluster</th>
                                             <th>ชื่อที่ติดต่อ</th>
                                             <th>ติด Blacklist</th>
                                             <th>สาเหตุที่ติด</th> -->
                                            <!--<th>อำเภอ/จังหวัด</th>-->
                                            <th>พนักงาน</th>
                                            <th>การกระทำ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($company as $key => $value) { 
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value->getName_Company(); ?></td>
                                                <td><?php echo $this->getEmp($value->getPROVINCE_ID(),$value->getAMPHUR_ID()); ?></td>
                                                <td class=" last text-center">
                                                    <a href="#"
                                                       onclick="companymanageShow('view','<?php echo $value->getID_Company(); ?>')">
                                                        <button type="button" class="btn btn-round btn-info"
                                                                style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;width:96px !important;">
                                                            <i class="fa fa-eye"></i>เพิ่มเติม
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
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
    # modal dialog ( company manage )
    include Router::getSourcePath() . "views/modal/modal_companymanage.inc.php";
//    # modal dialog ( import excel company  )
//    include Router::getSourcePath() . "views/modal/modal_importcompany.inc.php";
    include Router::getSourcePath() . "templates/footer_page.inc.php";

    ?>

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

<script type="text/javascript" src="AdminLTE/assets/js/page/report_company.js"></script>
