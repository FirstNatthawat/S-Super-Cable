<div class="modal fade" id="zonemanageModal" tabindex="-1" role="dialog" aria-labelledby="zonemanageModalDialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="zonemanageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Form -->
                <form id="form_zonemanage" method='post' action='' enctype="multipart/form-data">
                    <!-- <input type="hidden" name="ID_Excel" value=""/> -->
                    <div class="form-group">
                        <label for="ID_Employee" class="col-form-label">ชื่อพนักงาน:<span class="text-danger" >*</span></label>
                        <select data-placeholder="กรุณาเลือกพนักงาน" class="js-example-basic-multiple form-control" name="ID_Employee[]" id="ID_Employee" multiple="multiple">
                            <option value="" </option>
                            <?php

                            foreach ($employeeList as $employee) {
                                ?>
                                <option value="<?php echo $employee->getID_Employee(); ?>"><?php echo $employee->getName_Employee() . " " . $employee->getSurname_Employee(); ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="PROVINCE_ID" class="col-form-label">จังหวัด:<span class="text-danger" >*</span></label>
                        <select class="form-control" name="PROVINCE_ID" id="province" onchange=" var select = document.getElementById('province'); select.options[select.selectedIndex].value; if (select.options[select.selectedIndex].value != 1) {select.options[select.selectedIndex].value; document.getElementById('amphure_id').setAttribute('disabled', 'disabled');}">
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
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" id="button_zonemanageModal" onclick="onaction_createorupdate()" data-status="" data-id=""
                   class="btn btn-primary">ตกลง</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>

        </div>
    </div>
</div>