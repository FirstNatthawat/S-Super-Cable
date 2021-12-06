<div class="modal fade" id="companymanageModal" tabindex="-1" role="dialog" aria-labelledby="companymanageModalDialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="companymanageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Form -->
                <form id="form_companymanage" method='post' action='' enctype="multipart/form-data">
                    <!--  <input type="hidden" name="ID_Company" value="" /> -->
                    <div class="form-group" id="div_idcompany">
                        <!--  <label for="ID_Company" class="col-form-label">ไอดีบริษัทลูกค้า:</label>-->
                        <!--   <input type="text" class="form-control" id="ID_Company" name="ID_Company" value=""  required="required"  > -->
                    </div>
                    <div class="form-group ">
                        <label for="Name_Company" class="col-form-label">ชื่อบริษัท:<span class="text-danger" >*</span></label>
                        <input type="text" class="form-control" id="Name_Company" name="Name_Company" value=""
                               required="required">
                    </div>
                    <div class="form-group">
                        <label for="Address_Company" class="col-form-label">ที่อยู่บริษัท:</label>
                        <textarea class="form-control" name="Address_Company" id="Address_Company" rows="5" cols="60" ></textarea>
                    </div>
                    <div class="form-group">
                        <label for="PROVINCE_ID" class="col-form-label">จังหวัด:<span class="text-danger" >*</span></label>
                        <select class="form-control" name="PROVINCE_ID" id="province">
                            <option value="" selected disabled>-กรุณาเลือกจังหวัด-</option>
                            <?php
                            
                            foreach ($provinceList as $province) {
                                ?>
                                <option value="<?php echo $province->getPROVINCE_ID(); ?>"><?php echo $province->getPROVINCE_NAME(); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="AMPHUR_ID" class="col-form-label">อำเภอ:</label>
                        <select class="form-control" name="AMPHUR_ID" id="amphure_id">
                            <option value="" selected disabled>-กรุณาเลือกอำเภอ-</option>
                            <?php
                            foreach ($amphurList as $amphur) {
                                ?>
                                <option value="<?php echo $amphur->getAMPHUR_ID();?>"><?php echo $amphur->getAMPHUR_NAME(); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Tel_Company" class="col-form-label">เบอร์บริษัท:<span class="text-danger" >*</span></label>
                        <input type="tel" class="form-control" id="Tel_Company" name="Tel_Company" value=""
                               required="required">
                    </div>
                    <div class="form-group">
                        <label for="Email_Company" class="col-form-label">อีเมล์บริษัท:<span class="text-danger" >*</span></label>
                        <input type="email" class="form-control" id="Email_Company" name="Email_Company" value=""
                               required="required">
                    </div>
                    <div class="form-group">
                        <label for="Tax_Number_Company" class="col-form-label">เลขผู้เสียภาษี:<span class="text-danger" >*</span></label>
                        <input type="tel" class="form-control" id="Tax_Number_Company" name="Tax_Number_Company"
                               value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="Credit_Limit_Company" class="col-form-label">วงเงินสูงสุด(บาท):<span class="text-danger" >*</span></label>
                        <input type="number" class="form-control" id="Credit_Limit_Company" name="Credit_Limit_Company"
                               value="" min='0' required="required">
                    </div>
                    <div class="form-group">
                        <label for="Credit_Term_Company" class="col-form-label">เครดิตเทอม:<span class="text-danger" >*</span></label>
                        <input type="text" class="form-control" id="Credit_Term_Company" name="Credit_Term_Company"
                               value="" required="required">
                    </div>

                    <div class="form-group">
                        <label for="Cluster_Shop_ID" class="col-form-label">กลุ่มลูกค้า:<span class="text-danger" >*</span></label>
                        <select class="form-control" name="Cluster_Shop_ID" id="Cluster_Shop_ID">
                            <option value="" selected disabled>-กรุณากลุ่มลูกค้า-</option>
                            <?php

                            foreach ($cluster_shopList as $cluster_shop) {
                                ?>
                                <option value="<?php echo $cluster_shop->getCluster_Shop_ID();?>"><?php echo $cluster_shop->getCluster_Shop_Name(); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="Contact_Name_Company" class="col-form-label">ชื่อที่ติดต่อ:</label>
                        <input type="text" class="form-control" id="Contact_Name_Company" name="Contact_Name_Company"
                               value="">
                    </div>
                    <div class="form-group">
<!--                        <label for="IS_Blacklist" class="col-form-label">บัญชีดำ:<span class="text-danger" >*</span></label>-->
<!--                        <select name="IS_Blacklist" class="form-control" id="IS_Blacklist">-->
<!--                            <option value="" selected disabled>-กรุณาเลือก-</option>-->
<!--                            <option value="ใช่">ใช่</option>-->
<!--                            <option value="ไม่ใช่">ไม่ใช่</option>-->
<!--                        </select>-->
                        <label for="IS_Blacklist" class="col-form-label">บัญชีดำ:<span class="text-danger" >*</span></label><br>
                        <input type=radio value="ใช่" id=IS_Blacklist name=IS_Blacklist>ใช่<br>
                        <input type=radio value="ไม่ใช่" id=IS_Blacklist name=IS_Blacklist checked>ไม่ใช่
                    </div>
                    <div class="form-group">
                        <label for="Cause_Blacklist" class="col-form-label">สาเหตุที่ติดบัญชีดำ:</label>
                        <input type="text" class="form-control" id="Cause_Blacklist" name="Cause_Blacklist" value="">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" id="button_companymanageModal" onclick="onaction_createorupdate()" data-status="" data-id=""
                   class="btn btn-primary">ตกลง</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>

        </div>
    </div>
</div>