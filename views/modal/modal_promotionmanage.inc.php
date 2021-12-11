<div class="modal fade" id="promotionmanageModal" tabindex="-1" role="dialog" aria-labelledby="promotionmanageModalDialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promotionmanageTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <!-- Form -->
                <form id="form_promotionmanage" method='post' action='' enctype="multipart/form-data">
                    <!-- <input type="hidden" name="ID_Excel" value=""/> -->
                    <div class="form-group" id="div_idpromotion">
                        <!--   <label for="ID_Excel" class="col-form-label">ไอดียอดขาย:</label> -->
                        <!--                        <input type="text" class="form-control" id="ID_Excel" name="ID_Excel" value=""> -->
                    </div>
                    <div class="form-group ">
                        <label for="Name_Promotion" class="col-form-label">ชื่อสินค้าส่งเสริมการขาย:<span class="text-danger" >*</span></label>
                        <input type="text" class="form-control" id="Name_Promotion" name="Name_Promotion" value="" required="required">
                    </div>
                    <div class="form-group">
                        <label for="Unit_Promotion" class="col-form-label">จำนวน:<span class="text-danger" >*</span></label>
                        <input type="number" class="form-control" id="Unit_Promotion" name="Unit_Promotion" value="" required="required" min ="0" >
                    </div>
                    <div class="form-group">
                        <label for="Price_Unit_Promotion" class="col-form-label">ราคาต่อชิ้น:<span class="text-danger" >*</span></label>
                        <input type="number" class="form-control" id="Price_Unit_Promotion" name="Price_Unit_Promotion" value=""  required="required" min="0">
                    </div>
                    <div class="form-group ">
                        <label for="Have_To_Return" class="col-form-label">ต้องคืนหรือไม่:<span class="text-danger" >*</span></label>
                        <select  class="form-control" id="Have_To_Return" name="Have_To_Return" >
                            <option  value="" selected disabled>--กรุณาเลือก--</option>
                            <option value="1">ยืม</option>
                            <option value="0">เบิก</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" id="button_promotionmanageModal" onclick="onaction_createorupdate()" data-status="" data-id=""
                   class="btn btn-primary">ตกลง</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
            </div>

        </div>
    </div>
</div>