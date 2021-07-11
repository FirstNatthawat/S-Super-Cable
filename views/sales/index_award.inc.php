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
        <!-- /.content-header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-md-12">
                        <h1 class="m-0">รางวัล</h1><?php echo "ข้อความที่ไม่ได้อ่าน <font color=red>".$countAllAward[0]."</font>"; ?>

                        <!-- content -->
                        <div class="card">
                            <div class="card-body p-0 d-flex">
                                <div class="table-responsive">
                                    <table id="example3" class="table table-md" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>รูปภาพ</th>
                                            <th>ชื่อรางวัล</th>
                                            <th>วันเวลา</th>
                                            <th>ลูกจ้าง</th>

                                            <th>การกระทำ </th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        <?php $i=1; foreach ($awardList as $key => $value) { ?>
                                            <tr>
                                                <td><img src=<?php echo $value->getPicture_Award(); ?> width=200 height=200></td>
                                                <td><?php echo $value->getTittle_Award() ; ?></td>
                                                <td><?php echo $value->getDate_Award(); ?></td>
                                                <td><?php echo $value->getFullname_employee(); ?></td>

                                                <td class=" last">
                                                    <?php
                                                    if ($value->getStatus() == 0) {
                                                        ?>
                                                        <button type="button" onclick="location.replace('index.php?controller=Award&action=update_status_award&ID_Award=<?=$value->getID_Award()?>');"
                                                                class="btn btn-round btn-warning text-center"
                                                                style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;width:96px !important;">
                                                            อ่าน
                                                        </button>
                                                    <?php } else { ?>
                                                        <button type="button" class="btn btn-round btn-danger"
                                                                style=" font-size: 13px; padding: 0 15px; margin-bottom: inherit;width:96px !important;">
                                                            อ่านแล้ว
                                                        </button>
                                                    <?php } ?>
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
    </div>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a class="brand-link">
            <img src="AdminLTE/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                 style="opacity: .8">
            <span class="brand-text font-weight-light">S Super Cable</span>
        </a>
        <!-- Sidebar -->
        <?php include("templates/sales/sidebar_menu.inc.php"); ?>
        <!-- /.sidebar -->
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
<script>
    $(function () {
        $('#example3').DataTable({
            lengthMenu: [2, 10, 20, 50, 100, 200, 500],
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "sLengthMenu": "แสดง _MENU_ เร็คคอร์ด ต่อหน้า",
                "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
                "sInfo": "แสดง _START_ ถึง _END_ ของ _TOTAL_ เร็คคอร์ด",
                "sInfoEmpty": "แสดง 0 ถึง 0 ของ 0 เร็คคอร์ด",
                "sInfoFiltered": "(จากเร็คคอร์ดทั้งหมด _MAX_ เร็คคอร์ด)",
                "sSearch": "ค้นหา :",
                "aaSorting": [[0, 'desc']],
                "paginate": {
                    "sFirst": "หน้าแรก",
                    "sPrevious": "ก่อนหน้า",
                    "sNext": "ถัดไป",
                    "sLast": "หน้าสุดท้าย",
                    "oAria": {
                        "sSortAscending":  ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
                        "sSortDescending": ": เปิดใช้งานการเรียงข้อมูลจากมากไปน้อย"
                    }
                }
            },
        });
    });
</script>
<script type="text/javascript" src="AdminLTE/assets/js/page/manage_award.js"></script> <!-- -->
