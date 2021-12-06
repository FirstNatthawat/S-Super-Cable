<div class="modal fade" id="invManageModal" tabindex="-1" role="dialog" aria-labelledby="invManageModalDialog"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invManageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Form -->
                <form id="form_invManage" method='post' action='' enctype="multipart/form-data">
                <!-- -->
                <div class="form-group" style="display:none;">
                    <label>Inv No. <span class="text-danger" >*</span></label>
                    <input type="text" name="Invoice_No" id="Invoice_No" class="form-control">
                </div>
                <!-- -->
                <div class="form-group">
                    <label>ลูกค้า</label>
                    <select class="form-control" name="ID_Company" id="ID_Company">
                        <option value="">--เลือก--</option>
                        <?php
                        if(count($company) > 0){
                            foreach($company as $val){
                        ?>
                        <option value="<?php echo $val->getID_Company(); ?>"><?php echo $val->getName_Company(); ?></option>
                        <?php
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- -->
                <div class="form-group">
                    <label>บริษัท</label>
                    <input type="text" name="Name_Company" id="Name_Company" class="form-control">
                </div>
                <!-- -->
                <div class="form-group" style="display:none;">
                    <label>จำนวนเงิน</label>
                    <input type="text" name="" id="" class="form-control">
                </div>
                <!-- -->
                <div class="form-group">
                    <label>เลขที่ประจำตัวผู้เสียภาษี</label>
                    <input type="text" name="Tax_Number_Company" id="Tax_Number_Company" class="form-control">
                </div>
                <!-- -->

                <!-- -->
                <div class="form-group">
                    <label>ที่อยู่ลูกค้า</label>
                    <input type="text" name="Address_Company" id="Address_Company" class="form-control">
                </div>
                <!-- -->
                <div class="form-group">
                    <label>ชื่อผู้ติดต่อ</label>
                    <input type="text" name="Contact_Name_Company" id="Contact_Name_Company" class="form-control">
                </div>
                <!-- -->
                 <div class="form-group">
                    <label>อีเมล์ลูกค้า</label>
                    <input type="text" name="Email_Company" id="Email_Company" class="form-control">
                </div>
                <!-- -->
                 <div class="form-group">
                    <label>เบอร์โทรศัพท์ลูกค้า</label>
                    <input type="text" name="Tel_Company" id="Tel_Company" class="form-control">
                </div>
                <!-- -->
                 <div class="form-group" style="display:none;">
                    <label>เบอร์โทรสารลูกค้า</label>
                    <input type="text" class="form-control">
                </div>
                <!-- -->
                 <div class="form-group">
                    <label>วันที่เอกสาร</label>
                    <input type="text" autocomplete="off" name="Invoice_Date" id="Invoice_Date" class="form-control">
                </div>
                <!-- -->
                 <div class="form-group" style="display:none;">
                    <label>วันที่ครบกำหนด</label>
                    <input type="text" class="form-control">
                </div>
                
                <!-- -->
                 <div class="form-group">
                    <label>เครดิต (วัน)</label>
                    <input type="text" name="Credit_Term_Company" id="Credit_Term_Company" class="form-control">
                </div>
                <!-- -->
                <div class="form-group">
                    <label>ประเภท Vat</label>
                    <select class="form-control" name="Vat_Type" id="Vat_Type">
                        <option value="exclude">Exclude</option>
                        <option value="include">Include</option>
                        <option value="novat">Novat</option>
                    </select>
                </div>
                <!-- -->
                <div class="form-group">
                    <label>Vat</label>
                    <?php
                    if(count($vat) > 0){
                        foreach($vat as $val){
                            $vatValue = $val->getPercent_Vat();
                        }
                    }
                    ?>
                    <input type="text" name="Percent_Vat"  id="Percent_Vat" class="form-control" value="<?php echo $vatValue; ?>">
                </div>
                <div class="form-group">
                    <label>ส่วนลด</label>
                    <input type="text" name="Discount" id="Discount" class="form-control">
                </div>
                <fieldset style="margin-bottom:30px;">
                    <legend>เพิ่มรายการสินค้า</legend>
                    <div class="row">
                        <div class="col-lg-6">
                            <select class="form-control" name="id_goods" id="id_goods">
                                <option value="">--เลือกสินค้า--</option>
                                <?php
                                if(count($goodsList) > 0){
                                    foreach($goodsList as $val){
                                ?>
                                <option value="<?php echo $val->getID_Goods(); ?>"><?php echo $val->getName_Goods(); ?></option>
                                <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <!-- /.col-lg-6 -->
                        <div class="col-lg-2">
                           <input type="text" id="qty" class="form-control" placeholder="จำนวนสินค้า">
                        </div>
                        <div class="col-lg-2">
                           <input type="text" id="p_discout_price" class="form-control" placeholder="ส่วนลด">
                        </div>
                        <div class="col-lg-2">
                           <button type="button" class="btn btn-block btn-primary" onclick="addItem()" >เพิ่ม</button>
                        </div>
                        <!-- /.col-lg-6 -->
                    </div>
                </fieldset>
                <!-- -->
                <table class="table" id="productList">
                    <thead>
                        <tr class="table-secondary">
                            <th scope="col" style="width: 10%;">#</th>
                            <th scope="col" style="width: 20%; font-size: 12px;">สินค้า</th>
                            <th scope="col" class="text-center" style="width: 10%; font-size: 12px;">จำนวน</th>
                            <th scope="col" style="width: 15%; font-size: 12px;" class="text-center">ราคา/หน่วย</th>
                            <th scope="col" style="width: 15%; font-size: 12px;" class="text-center">ส่วนลด</th>
                            <th scope="col" style="width: 15%; font-size: 12px;" class="text-center">รวมเป็นเงิน</th>
                            <th scope="col" style="width: 15%; font-size: 12px;" class="text-center">&nbsp;</th>
                        </tr>
                        <tbody></tbody>
                    </thead>
                </table>
                <!-- -->
                </form>

            </div>

            <div class="modal-footer">
                <a href="#" id="button_invManageModal" onclick="onaction_createoredit()" data-status="" data-id=""
                   class="btn btn-primary">ตกลง</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>

        </div>
    </div>
</div>
