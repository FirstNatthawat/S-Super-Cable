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
                            <i class="nav-icon fas fa-gift "></i>
                            <p>
                                ส่งเสริมการขาย
                            </p>
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