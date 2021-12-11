
<div class="sidebar">
    <?php
    // echo '<PRE>';
    // print_r($_SESSION);exit();
    ?>
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">

            <img style="width:33px;height:33px;" src="<?php echo empty($employee->Picuture_Employee) ? "AdminLTE/dist/img/no_img.png" : "images/" . $employee->Picuture_Employee; ?>"
                 class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" onclick="showModalEditProfile()"
               class="d-block"><?php echo $employee->getName_Employee() . " " . $employee->getSurname_Employee(); ?></a>

        </div>
    </div>