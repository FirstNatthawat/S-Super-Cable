<?php
$title = "S Super Cable";

try {
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
                        <h1 class="m-0">ดาวน์โหลดไฟล์เอกสาร</h1>

                        <!-- content -->
                        <div class="card">

                            <div class="form-group row mt-2 mb-2 mr-1">
                                <div class="col-md-12 text-right">

                                </div>
                            </div>

                            <div class="card-body p-0 d-flex">
                                <div class="table-responsive">
                                    <table id="tbl_file" class="table table-md" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>เลขที่</th>
                                            <th>ชื่อไฟล์</th>
                                            <th>วันที่อัปโหลดไฟล์</th>
                                            <th>การกระทำ</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php $i=1; foreach ($fileList as $key => $value) { ?>
                                            <tr>
                                                <td><?php echo $i++; ?></td>
                                                <td><?php echo $value->getName_File() ; ?></td>
                                                <td><?php  $date = date_create($value->getDate_Upload_File()) ;
                                                    echo date_format($date, 'd/m/Y'); ?></td>
                                                <td class=" last">
                                                    <a href="#"
                                                       onclick="onAction_downloadFile('<?php echo $value->getID_File(); ?>')">
                                                        <button type="button" class="btn btn-primary"
                                                                style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;width:96px !important;">
                                                            <i class="fas fa-file-download"></i> ดาวน์โหลด
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
        }else if(strtolower($user_status)=='admin') {
            include("templates/admin/sidebar_menu.inc.php");
        }
        ?>
        <!-- /.sidebar -->
    </aside>

    <?php
    # modal dialog ( edit profile )
    include Router::getSourcePath() . "views/modal/modal_editprofile.inc.php";

    # modal dialog ( file manage )
    include Router::getSourcePath() . "views/modal/modal_filemanage.inc.php";
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


<script type="text/javascript" src="AdminLTE/assets/js/page/manage_download_file.js"></script>