<div class="modal fade" id="formDataModal" tabindex="-1" role="dialog" aria-labelledby="borrowmanageModalDialog"aria-hidden="true">
    <div class="modal-dialog" role="document">
    <!-- Form -->
        <form id="form_modal" method='post' action='' enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modelTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group " style="display: none;">
                        <label for="ID_Promotion" class="col-form-label">เบิก-ยืม/คืน-คืน:<span class="text-danger" >*</span></label>
                        <select  class="form-control" id="Type_BorrowOrReturn" name="Type_BorrowOrReturn" readonly>
                            <option value="">--เลือก--</option>
                            <option value="1">เบิก-ยืม</option>
                            <option value="2">คืน</option>
                        </select>
                    </div>
                    <div class="form-group ">

                        <label for="ID_Promotion" class="col-form-label">สินค้าส่งเสริมการขาย:<span class="text-danger" >*</span></label>
                        <select  class="form-control" id="ID_Promotion" name="ID_Promotion">
                            <option value="">--เลือก--</option>
                            <?php
                            if(count($promotionList)>0){
                                foreach($promotionList as $val){
                            ?>
                             <option value="<?php echo $val->getID_Promotion(); ?>"><?php echo $val->getName_Promotion(); ?></option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group" id="devDetail_BorrowOrReturns">
                        <label for="Detail_BorrowOrReturns" class="col-form-label">รายละเอียดการเบิก-ยืม:</label>
                        <textarea class="form-control" name="Detail_BorrowOrReturn" id="Detail_BorrowOrReturns" rows="5" cols="60"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="Amount_BorrowOrReturn" class="col-form-label">จำนวน:<span class="text-danger" >*</span></label>
                        <input type="number" class="form-control" id="Amount_BorrowOrReturn" name="Amount_BorrowOrReturn" value="" required="required" min="1">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="button_modal" class="btn btn-primary"  data-status="" data-id="xx" >ตกลง</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                </div>
            </div>
        </form>
    </div>
</div>