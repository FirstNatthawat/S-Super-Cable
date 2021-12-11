<div class="modal fade" id="importsalesModal" tabindex="-1" role="dialog" aria-labelledby="importsalesModalDialog"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importsalesModalTitle">นำเข้าไฟล์ excel</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_importexcel" method='post' action='' enctype="multipart/form-data">
                    <p>ตัวอย่าง format การนำข้อมูลเข้าระบบ</p>
                    <img src="<?php echo Router::getSourcePath() . "images/" . $file_log['file_name'] ?>" width="100%">
                    <h6 class="pt-4">อัพโหลดไฟล์รูปภาพตัวอย่าง</h6>
                    <input id="examfile" name="examfile" type="file" style="">
                    <h6 class="pt-4">อัพโหลดไฟล์ Excel</h6>
                    <input type="file" name="file" id="file" value=""
                           accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel,.csv"/>
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" id="button_importsalesModal" data-status="" data-id="" class="btn btn-primary">ตกลง</a>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
                <a href="index.php?controller=ResultSales&action=export_excel_test_sales"
                <button type="button" class="btn btn-success" class="fa fa-file"></i>
                    ดาวน์โหลดไฟล์ตัวอย่าง</span></button>
                </a>
            </div>
        </div>
    </div>
</div>