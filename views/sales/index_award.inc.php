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
        <div class="content-header" >
            <div class="container-fluid">
                <div class="row mb-12">
                    <div class="col-md-12">
                        <h1 class="m-0">รางวัล </h1><?php echo "ข้อความที่ไม่ได้อ่าน <font color=red>".Award::message_unread($emp_id)."</font>"; ?>
                        <div class="card">
                            <div class="card-body p-0" >
                                <!-- content -->
                                <div class="callout callout-info" >
                                    <?php $i = 1;

                                    foreach ($award as $key => $value) {
                                        //$img = $value->getPicture_Award();
                                        $date = date_create($value->getDate_Award());
                                        $img = '';
                                        $img_data =  Award_Image::get_images($value->getID_Award());
                                        if(count($img_data)>0){
                                            $img = Router::getSourcePath() . "images/".$img_data[0]->getImage_name();
                                        }
                                        ?>
                                        <table width="100%" style="margin-bottom:20px;" >
                                            <tr valign="top">
                                                <td width="30%">
                                                    <img src="<?php echo $img; ?>" width="100%">
                                                </td>
                                                <td width="70%" style="padding:5px;">
                                                    วันที่ : <?php echo date_format($date, 'd/m/Y'); ?>
                                                    <br/>
                                                    <a href="index.php?controller=Award&action=show&id=<?php echo $value->getID_Award(); ?>"><?php echo $value->getTittle_Award(); ?></a>
                                                    <br/>
                                                    ชื่อคนที่ได้รับรางวัล : <?php echo $value->getFullname_employee(); ?>
                                                </td>
                                            </tr>
                                        </table>
                                    <?php } ?>
                                    <ul class="pagination pagination-sm">
                                        <?php
                                        for($i=1;$i<=$count_page;$i++){
                                            ?>
                                            <li class="page-item"><a href="index.php?controller=Award&action=show_award_status&page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <!-- end content -->
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Content Header (Page header) -->

        <!-- /.content-header -->

    </div><!-- /.row -->

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
        }else if(strtolower($user_status)=='user'){
            include("templates/users/sidebar_menu.inc.php");
        }
        ?>        <!-- /.sidebar -->
    </aside>

    <?php
    # modal dialog ( edit profile )
    include Router::getSourcePath() . "views/modal/modal_editprofile.inc.php";
    include Router::getSourcePath() . "templates/footer_page.inc.php";

    ?>


    <?php
    $content = ob_get_clean();

    include Router::getSourcePath() . "templates/layout.php";
} catch (Throwable $e) { // PHP 7++
    echo "การเข้าถึงถูกปฏิเสธ: ไม่ได้รับอนุญาตให้ดูหน้านี้";
    exit(1);
}
?>