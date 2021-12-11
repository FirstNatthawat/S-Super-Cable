var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},
  {"width": "25%", "class": "text-center"},
  {"width": "5%", "class": "text-right"},
  {"width": "25%", "class": "text-center"},
]

var dataTable_ = $('#tbl').DataTable({
  "processing": true,
  "bDestroy": true,
  "bPaginate": true,
  "bFilter": true,
  "bInfo": true,
  "searching": true,
  "language": {
    "sLengthMenu": "แสดง _MENU_ เร็คคอร์ด ต่อหน้า",
    "sZeroRecords": "ไม่เจอข้อมูลที่ค้นหา",
    "sInfo": "แสดง _START_ ถึง _END_ ของ _TOTAL_ เร็คคอร์ด",
    "sInfoEmpty": "แสดง 0 ถึง 0 ของ 0 เร็คคอร์ด",
    "sInfoFiltered": "(จากเร็คคอร์ดทั้งหมด _MAX_ เร็คคอร์ด)",
    "sSearch": "ค้นหา :",
    "aaSorting" :[[0,'desc']],
    "paginate": {
      "sFirst":    "หน้าแรก",
      "sPrevious": "ก่อนหน้า",
      "sNext":     "ถัดไป",
      "sLast":     "หน้าสุดท้าย",
      "oAria": {
        "sSortAscending":  ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
        "sSortDescending": ": เปิดใช้งานการเรียงข้อมูลจากมากไปน้อย"
      }
    }
  },

  // "responsive": true,
  rowReorder: {
    selector: 'td:nth-child(2)'
  },
  responsive: true,

  initComplete: function () {

  },
  "columns": columns


});

var form_validte = $("#form_modal").validate({
  rules: {
    ID_Promotion: {
      required: true,
    },
    Amount_BorrowOrReturn: {
      required: true,
    }
  },
  messages: {
    ID_Promotion: {
      required: "กรุณาใส่ข้อมูล",
    },
    Amount_BorrowOrReturn: {
      required: "กรุณาใส่ข้อมูล",
    }
  },
  errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  },
  submitHandler: function(form) {
    var dataId =  $('#button_modal').attr("data-id");
    var dataStatus = $('#button_modal').attr("data-status");
    if(dataStatus=="create"){
      onaction_insert();
    }
  }
});

function modalShow(type, selectID = null) {
  var title = "";
  /* clear old form value */
  $('#form_modal')[0].reset();
  switch (type) {
    case 'create':
      title = "ยืม/เบิกสินค้าส่งเสริมการขาย";
      $('#button_modal').attr("data-id","");
      break;
    case 'edit':
      title = "เเก้ไขสินค้าส่งเสริมการขาย";
      //clear error if exists
      break;
    default:
      // ..
      break;
  }

  /* set title */
  $('#modelTitle').html(title);

  /* set button event  */
  $('#button_modal').attr("data-status", type);

  /* modal show  */
  $('#formDataModal').modal('show');
}


function onaction_insert() {

  $.ajax({
    type: "POST",
    url: "index.php?controller=Borrow&action=borrow_insert",
    data: $("#form_modal").serialize(),
    success: function (res, status, xhr) {
      if (res.status == true) {
        Swal.fire({
          icon: 'success',
          title: 'สำเร็จ',
          confirmButtonText: 'ตกลง',
        }).then((result) => {
          location.reload();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'ขออภัย...',
          text: 'มีบางอย่างผิดพลาด , อาจจะมีข้อมูลอยู่ในฐานข้อมูลเเล้ว , โปรดลองอีกครั้ง',
          confirmButtonText: 'ตกลง',
        }).then((result) => {
          location.reload();
        });
      }
    }
  });
}


function onaction_delete(id){
  Swal.fire({
    title: 'คุณเเน่ใจใช่ไหม?',
    text: "คุณต้องการลบข้อมูลนี้ใช่ไหม?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ตกลง',
    cancelButtonText: 'ยกเลิก'

  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "index.php?controller=Borrow&action=borrow_delete&id=" + id,
        processData: false,
        contentType: false,
        success: function (res, status, xhr) {
          var data = res;
          if (data.status == true) {
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              confirmButtonText: 'ตกลง',
            }).then((result) => {
              location.reload();

            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'ขออภัย...',
              text: 'มีบางอย่างผิดพลาด , อาจจะมีข้อมูลอยู่ในฐานข้อมูลเเล้ว , โปรดลองอีกครั้ง',
              confirmButtonText: 'ตกลง',
            }).then((result) => {
              location.reload();

            });
          }
        }
      });

    }
  })
}


function onaction_disApprove(id){
  Swal.fire({
    title: 'คุณเเน่ใจใช่ไหม?',
    text: "คุณต้องการไม่อนุมัติการยิมนี้?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ตกลง',
    cancelButtonText: 'ยกเลิก'

  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "index.php?controller=Borrow&action=borrow_approve_dis&id=" + id,
        processData: false,
        contentType: false,
        success: function (res, status, xhr) {
          var data = res;
          if (data.status == true) {
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              confirmButtonText: 'ตกลง',
            }).then((result) => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'ขออภัย...',
              text: 'มีบางอย่างผิดพลาด , อาจจะมีข้อมูลอยู่ในฐานข้อมูลเเล้ว , โปรดลองอีกครั้ง',
              confirmButtonText: 'ตกลง',
            }).then((result) => {
              location.reload();
            });
          }
        }
      });
    }
  })
}

function onaction_Approve(id){
  Swal.fire({
    title: 'คุณเเน่ใจใช่ไหม?',
    text: "คุณต้องการอนุมัติการยิมนี้?",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#3085d6',
    cancelButtonColor: '#d33',
    confirmButtonText: 'ตกลง',
    cancelButtonText: 'ยกเลิก'

  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        type: "POST",
        url: "index.php?controller=Borrow&action=borrow_approve_save&id=" + id,
        processData: false,
        contentType: false,
        success: function (res, status, xhr) {
          var data = res;
          if (data.status == true) {
            Swal.fire({
              icon: 'success',
              title: 'สำเร็จ',
              confirmButtonText: 'ตกลง',
            }).then((result) => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'ขออภัย...',
              text: 'มีบางอย่างผิดพลาด , อาจจะมีข้อมูลอยู่ในฐานข้อมูลเเล้ว , โปรดลองอีกครั้ง',
              confirmButtonText: 'ตกลง',
            }).then((result) => {
              location.reload();
            });
          }
        }
      });
    }
  })
}

