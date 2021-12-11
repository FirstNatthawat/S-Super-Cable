<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar user panel (optional) -->

    <?php include("templates/sidebar_profile.inc.php"); ?>


    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        หน้าหลัก
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="index.php?controller=Homepage&action=index" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>
                                หน้าหลัก
                            </p>
                        </a>
                    </li>
                </ul>
            </li>


            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-tasks"></i>
                    <p>
                        จัดการ
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=Invoice&action=manage_invoice"; ?>"
                           class="nav-link">
                            <i class="nav-icon fas fa-file-invoice"></i>
                            <p>
                                เสนอราคา
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon  fas fa-gift"></i>
                    <p>
                        เบิก/คืน
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=Borrow&action=borrow"; ?>" class="nav-link">
                            <i class="nav-icon fas fa-gifts"></i>
                            <p>
                                ส่งเสริมการขาย
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-eye"></i>
                    <p>
                        เเสดงผล
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=News&action=show_news_status"; ?>"
                           class="nav-link">
                            <i class="nav-icon fas fa-comment"></i>
                            <p>
                                ข่าวสาร
                            </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=Award&action=show_award_status"; ?>"
                           class="nav-link">
                            <i class="nav-icon fas fa-award"></i>
                            <p>
                                รางวัล
                            </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=ReportCompany&action=company"; ?>"
                           class="nav-link">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                ค้นหาข้อมูลลูกค้า
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-file"></i>
                    <p>
                        รายงาน
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=File&action=show_index_download_file"; ?>"
                           class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>เอกสาร </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="index.php?controller=report&action=borrow" class="nav-link">
                            <i class="nav-icon fas fa-gift "></i>
                            <p>ส่งเสริมการขาย </p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-chart-pie"></i>
                    <p>
                        กราฟ
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=ReportCustomer&action=customer2"; ?>"
                           class="nav-link">
                            <i class="nav-icon fas fa-store"></i>
                            <p>เปอร์เซ็นของกลุ่มลูกค้า </p> ทั้งสองเเบบ
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=SalesStatistics2&action=manage_SalesStatistics"; ?>" class="nav-link">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>ยอดขายของตัวเอง</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>
                        สถิติ
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="index.php?controller=reportcustomernotmoving&action=customer_not_moving" class="nav-link">
                            <i class="nav-icon fas fa-store"></i>
                            <p>ลูกค้าที่ไม่เคลื่อนไหว </p>
                        </a>
                    </li>
                </ul>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="<?php echo Router::getSourcePath() . "index.php?controller=EmpStatistics2&action=manage_EmpStatistics"; ?>" class="nav-link">
                            <i class="nav-icon fas fa-wallet"></i>
                            <p>เปรียบเทียบยอดขาย </p> ของตัวเอง
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item menu-open">
                <a class="nav-link active">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>
                        ออกจากระบบ
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" onclick="logout()" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>ออกจากระบบ </p>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>
<!-- /.sidebar -->
