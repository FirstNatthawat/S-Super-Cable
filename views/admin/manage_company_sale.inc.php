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
                        <h1 class="m-0">บริษัทลูกค้าไม่มี Sale ดูเเล</h1>

                        <!-- content -->
                        <div class="card">
                            <!-- <div class="card-header">
                                <h3 class="card-title">User Management</h3>
                            </div> -->
                            <!-- /.card-header -->
<!--                            <div class="form-group row mt-2 mb-2 mr-1" style="display:none;">-->
<!--                                <div class="col-md-12 text-right">-->
<!--                                    <a href="index.php?controller=Company&action=export_pdf"-->
<!--                                       class="collapse-link text-right mt-2 mb-2 mr-2" style="color: #415468;">-->
<!--                                        <span class="btn btn-round btn-success"-->
<!--                                             style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;"><i-->
<!--                                                    class="fa fa-file"></i> ดาวน์โหลดไฟล์ </span>-->
<!--                                    </a>-->
<!--                                    <a href="#" onclick="importShow()" class="collapse-link text-right mt-2 mb-2 mr-2"-->
<!--                                       style="color: #415468;">-->
<!--                                        <span class="btn btn-round btn-success"-->
<!--                                              style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;"><i-->
<!--                                                    class="fa fa-file"></i> อัปโหลดไฟล์ excel </span>-->
<!--                                    </a>-->
<!---->
<!--                                    <a href="#" onclick="companymanageShow('create')"-->
<!--                                       class="collapse-link text-right mt-2 mb-2 mr-2" style="color: #415468;">-->
<!--                                        <span class="btn btn-round btn-success"-->
<!--                                              style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;"><i-->
<!--                                                    class="fa fa-plus"></i> สร้างบริษัทลูกค้า </span>-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="card-body p-0 d-flex">
                                <div class="table-responsive">
                                    <table id="tbl_companymanagement" class="table table-md" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>เลขที่</th>
                                            <th>ชื่อบริษัท</th>
                                            <!--<th>ที่อยู่บริษัท</th>-->
                                            <th>เบอร์บริษัท</th>
                                            <!-- <th>อีเมล์บริษัท</th>
                                            <th>เลขผู้เสียภาษี</th> -->
                                            <th>วงเงินสูงสุด (บาท)</th>
                                            <!-- <th>Credit Term</th>
                                             <th>Cluster</th>
                                             <th>ชื่อที่ติดต่อ</th>
                                             <th>ติด Blacklist</th>
                                             <th>สาเหตุที่ติด</th> -->
                                            <th>อำเภอ/จังหวัด</th>
                                            <th>พนักงาน</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php $i = 1; ?>
                                        <?php foreach ($company as $key => $value) { 
                                            if($this->getEmp($value->getPROVINCE_ID(),$value->getAMPHUR_ID())==''){
                                            
                                            ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value->getName_Company(); ?></td>
                                             
                                                <td><?php echo $value->getTel_Company(); ?></td>
                                            
                                                <td><?php echo number_format($value->getCredit_Limit_Company(), 2); ?></td>
                                           
                                                <td><?php echo $value->getAMPHUR_NAME(). "/" .$value->getPROVINCE_NAME(); ?></td>
                                                <td><?php echo $this->getEmp($value->getPROVINCE_ID(),$value->getAMPHUR_ID()); ?></td>
                                            </tr>
                                        <?php }
                                        }
                                         ?>
                                        </tbody>
                                    </table>
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
        <?php include("templates/admin/sidebar_menu.inc.php"); ?>
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

<script type="text/javascript" src="AdminLTE/assets/js/page/manage_companysales.js"></script>
