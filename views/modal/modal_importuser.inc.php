<div class="modal fade" id="importuserModal" tabindex="-1" role="dialog" aria-labelledby="importuserModalDialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importuserModalTitle">นำเข้าไฟล์ excel</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <form id="form_importexcel" method='post' action='' enctype="multipart/form-data">
            <p>ตัวอย่าง format การนำข้อมูลเข้าระบบ</p>
            <img src="<?php echo Router::getSourcePath(). "images/format_excel.png"?>" width="100%">  
            <input type="file" name="file" id="file" value="" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"  required="required"/>
          </form>
      </div>
      
      <div class="modal-footer">
        <a href="#" id="button_importuserModal"data-status="" data-id="" class="btn btn-primary">ตกลง</a>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>

      </div>
      
    </div>
  </div>
</div>