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
                        <h1 class="m-0">รายการเบิก-คืน สินค้า</h1>

                        <!-- content -->
                        <div class="card" >

                            <div class="form-group row mt-2 mb-2 mr-1">
                                <div class="col-md-12 text-right">
                                    <a href="#" onclick="modalShow('create')"
                                       class="collapse-link text-right mt-2 mb-2 mr-2" style="color: #415468;">
                                        <span class="btn btn-round btn-success"
                                              style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;"><i
                                                class="fa fa-plus"></i> เบิกสินค้า </span>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body p-0 d-flex">
                                <div class="table-responsive">
                                    <table id="tbl" class="table table-md" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>วันที่ยืม</th>
                                            <th>เบิก-คืน</th>
                                            <th>ชื่อสินค้าที่เบิก-คืน</th>
                                            <th>รายละเอียด</th>
                                            <th>จำนวน</th>
                                            <th>สถานะ</th>
                                            <th>หมายเหตุ</th>
                                            <th>การกระทำ</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                            if(count($borrow)>0){
                                                foreach($borrow as $key => $val){
                                                    $status_approve_txt = "รออนุมัติ";
                                                    $status_approve = $val->getApprove_BorrowOrReturn();
                                                    if($status_approve=='1'){
                                                        $status_approve_txt = "อนุมัติ";
                                                    }else if($status_approve=='2'){
                                                        $status_approve_txt = "ไม่อนุมัติ";
                                                    }

                                                    $type = "ยืม";
                                                    $promotion = Promotion::findById($val->getID_Promotion());
                                                    $showBtn = 0;
                                                    if($val->getType_BorrowOrReturn()=='1' && $promotion->getHave_To_Return()=='1' && $status_approve=='1'){
                                                        $showBtn = 1;
                                                    }
                                                    if($val->getType_BorrowOrReturn()=='2'){
                                                        $type = "คืน";
                                                    }
                                        ?>
                                            <tr>
                                                <td><?php  $date = date_create($val->getDate_BorrowOrReturn()); echo date_format($date, 'd/m/Y');; ?></td>
                                                <td><?php echo $type; ?></td>
                                                <td><?php echo $val->getName_Promotion(); ?></td>
                                                <td><?php echo $val->getDetail_BorrowOrReturn(); ?></td>
                                                <td><?php echo $val->getAmount_BorrowOrReturn(); ?></td>
                                                <td><?php echo $status_approve_txt; ?></td>
                                                <td>
                                                    <?php echo $val->getComment_BorrowOrReturn(); ?>
                                                </td>
                                                <td class=" last">
                                                    
                                                <?php if($showBtn=='1'){ ?>
                                                    <a href="#"
                                                       onclick="onaction_edit('<?php echo $val->getID_BorrowOrReturn(); ?>')">
                                                        <button type="button" class="btn btn-round btn-info"
                                                                style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;width:96px !important;">
                                                            <i class="fas fa-exchange-alt"></i> คืน
                                                        </button>
                                                    </a>
                                                <?php } ?>
                                                <?php if($status_approve=='0'){ ?>

                                                <?php } ?>
                                                <?php if($status_approve=='0'){ ?>
                                                    <a href="#"
                                                       onclick="onaction_delete('<?php echo $val->getID_BorrowOrReturn(); ?>')">
                                                        <button type="button" class="btn btn-round btn-danger"
                                                                style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;width:96px !important;">
                                                            <i class="fa fa-trash"></i> ลบ
                                                        </button>
                                                    </a>
                                                <?php } ?>
                                                </td>
                                            </tr>
                                        <?php
                                                }
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
    # modal dialog ( manage borrow )
    include Router::getSourcePath() . "views/modal/modal_borrow.inc.php";
    include Router::getSourcePath() . "templates/footer_page.inc.php";

    ?>


    <?php
    $content = ob_get_clean();
    // $user_jsonencode = json_encode($user);
    // echo '<PRE>';
    // print_r(ob_get_clean());exit();
    include Router::getSourcePath() . "templates/layout.php";
} catch (Throwable $e) { // PHP 7++
    //print_r($e);
    echo "การเข้าถึงถูกปฏิเสธ: ไม่ได้รับอนุญาตให้ดูหน้านี้";
    exit(1);
}
?>

<script type="text/javascript" src="AdminLTE/assets/js/page/borrow.js"></script>
