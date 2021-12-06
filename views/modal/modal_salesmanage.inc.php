<div class="modal fade" id="salesmanageModal"  role="dialog" aria-labelledby="salesmanageModalDialog"
     aria-hidden="true" style="overflow:hidden;"  >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="salesmanageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Form -->
                <form id="form_salesmanage" method='post' action='' enctype="multipart/form-data">
                    <!-- <input type="hidden" name="ID_Excel" value=""/> -->
                    <div class="form-group" id="div_idsales">
                        <!--   <label for="ID_Excel" class="col-form-label">ไอดียอดขาย:</label> -->
                        <!--                        <input type="text" class="form-control" id="ID_Excel" name="ID_Excel" value=""> -->
                    </div>
                    <div class="form-group  ">
                        <label for="Date_Sales" class="col-form-label">วันที่ขาย:<span class="text-danger" >*</span></label>
                        <input type="date" class="form-control" id="Date_Sales" name="Date_Sales" value=""
                               required="required">
                    </div>
                    <div class="form-group ">
                        <label for="ID_Company" class="col-form-label">ชื่อบริษัทลูกค้า:<span class="text-danger" >*</span></label>
                        <select onchange="test();" data-placeholder="กรุณาเลือกบริษัทลูกค้า" class="js-example-basic-single  form-control" name="ID_Company" id="ID_Company">
                            <option  value="" ></option>

                            <?php
                            foreach ($companyList as $company) {
                                ?>
                                <option value="<?php echo $company->getID_Company(); ?>"><?php echo $company->getName_Company(); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!--                        <input type="text" class="form-control" id="ID_Company" name="ID_Company" value=""  required="required" >-->
                    </div>

                    <div class="form-group">
                        <label for="ID_Employee" class="col-form-label">ชื่อพนักงาน:<span class="text-danger" >*</span></label>
                        <select data-placeholder="กรุณาเลือกพนักงาน"class=" js-example-basic-single form-control" name="ID_Employee" id="ID_Employee">
                            <option  value="" ></option>

                            <?php foreach ($employeeList as $employee) { ?>
                                <option value="<?php echo $employee->getID_Employee(); ?>"><?php echo $employee->getName_Employee() . " " . $employee->getSurname_Employee(); ?></option>
                            <?php } ?>
                        </select>
                        <!--                        <input type="text" class="form-control" id="ID_Employee" name="ID_Employee" value="" required="required" >-->
                    </div>
                    <div class="form-group">
                        <label for="Result_Sales" class="col-form-label">ยอดขาย:<span class="text-danger" >*</span></label>
                        <input type="number" class="form-control" id="Result_Sales" name="Result_Sales" value=""
                               required="required" min="0">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" id="button_salesmanageModal" onclick="onaction_createorupdate()" data-status="" data-id=""
                   class="btn btn-primary">ตกลง</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>

        </div>
    </div>
</div>
