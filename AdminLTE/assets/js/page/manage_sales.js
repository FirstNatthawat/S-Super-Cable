var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-right"},
  {"width": "5%", "class": "text-center"},

]

var dataTable_ = $('#tbl_salesmanagement').DataTable({
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

var form_validte = $("#form_salesmanage").validate({
  rules: {
    ID_Excel: {
      //required: true,
    },
    Date_Sales: {
      required: true,
    },
    ID_Company: {
      required: true,
      // minlength: 3
    },
    ID_Employee: {
      required: true,
    },
    Result_Sales: {
      required: true,
    },
    action: "required"
  },
  messages: {
    ID_Excel: {
      // required: "กรุณาใส่ข้อมูล",
    },
    Date_Sales: {
      required: "กรุณาใส่ข้อมูล",
    },
    ID_Company: {
      required: "กรุณาใส่ข้อมูล",
      //minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    ID_Employee: {
      required: "กรุณาใส่ข้อมูล",
    },
    Result_Sales: {
      required: "กรุณาใส่ข้อมูล",
    },
    action: "กรุณาใส่ข้อมูล"
  }, errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  }
});

function salesmanageShow(type, ID_Excel = null) {
  var title = "";

  /* clear old form value */
  $('#form_salesmanage')[0].reset();

  switch (type) {
    case 'create':
      title = "สร้างยอดขาย";

      $('#div_idsales').show();

      // set id
      $('#button_salesmanageModal').attr("data-id", null);
      break;
    case 'edit':
      title = "เเก่ไขยอดขาย";
      //clear error if exists
      form_validte.resetForm();

      $.ajax({
        url: "index.php?controller=ResultSales&action=findbyID_Sales",
        data: {
          "ID_Excel": ID_Excel
        },
        type: "POST",
        dataType: 'json',
        async: false,
        success: function (response, status) {
          /* set input value */
          $('#div_idsales').hide();
          console.log(response);
          $('#ID_Excel').val(response.data.ID_Excel);
          $('#Date_Sales').val(response.data.Date_Sales);
          // $('#ID_Company').val(response.data.ID_Company);
          // $("#ID_Employee").val(response.data.ID_Employee);
          $('#ID_Company').val(response.data.ID_Company).trigger("change");
          $("#ID_Employee").val(response.data.ID_Employee).trigger("change");
          $('#Result_Sales').val(response.data.Result_Sales);

          // set id
          $('#button_salesmanageModal').attr("data-id", ID_Excel);
        },
        error: function (xhr, status, exception) {
          //console.log(xhr);
        }
      });


      break;
    default:
      // ..
      break;
  }

  /* set title */
  $('#salesmanageTitle').html(title);

  /* set button event  */
  $('#button_salesmanageModal').attr("data-status", type);

  /* modal show  */
  $('#salesmanageModal').modal('show');
}

function onaction_deletesales(ID_Excel) {
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
        url: "index.php?controller=ResultSales&action=delete_sales",
        data: {
          "ID_Excel": ID_Excel
        },
        type: "POST",
        dataType: 'json',
        async: false,
        success: function (data, status) {
          if (data.status == true) {
            Swal.fire(
              'ลบเรียบร้อย!',
              'ข้อมูลคุณถูกลบเรียบร้อยเเล้ว',
              'success'
            ).then((result) => {
              location.reload();

            });
          }

        },
        error: function (xhr, status, exception) {
          //console.log(xhr);
        }
      });

    }
  })
}

// case: ตอนอัพโหลดไฟล์ excel validate ว่าใช่ไฟล์ excel ไหมถ้าไม่ใช่ขึ้นแจ้งเตือนว่า type ไม่ตรง
$('#form_importexcel #file ').validate({
  rules: {
    file: {

      extension: "xlsx|csv",

    }
  },
  messages: {
    file: "กรุณาอัพโหลดไฟล์ Excel ที่นามสกุล .xlsx, .csv เท่านั้น"
  },

  errorPlacement: function (error, element) {
    //แจ้งเตือนผิด format
    Swal.fire({
      icon: 'error',
      title: 'ขออภัย...',
      text: "กรุณาอัพโหลดไฟล์ Excel ที่นามสกุล .xlsx, .csv เท่านั้น",
      confirmButtonText: 'ตกลง',
    }).then((result) => {
      // break
      location.reload();

    });
  }
});


// eof
function downloadExcel() {
  var url_string = "index.php?controller=ResultSales&action=export_excel_test_sales";
  $.ajax({
    type: "POST",
    url: "index.php?controller=ResultSales&action=export_excel_test_sales",
    data: {
      "page": 'manage_sales'
    },

    dataType: 'json',

    success: function (data, status, xhr) {

      console.log(data);
      var filename = data.filename;
      //alert(data.filename);
      //window.location.href = "./uploads/" + filename;


    }
  });
}

$("#button_importsalesModal").on('click', function (event) {
  var form_importexcel = $('#form_importexcel')[0];
  var formData_importexcel = new FormData(form_importexcel);

  // case: ตอนอัพโหลดไฟล์ excel validate ว่าใช่ไฟล์ excel ไหมถ้าไม่ใช่ขึ้นแจ้งเตือนว่า type ไม่ตรง
  $("#form_importexcel").validate().form();
  /* eof */

  var url_string = "index.php?controller=ResultSales&action=import_excel_sales";
  if ($('#form_importexcel #examfile').val() != '' || $('#form_importexcel #file').val() != '') {
    $.ajax({
      type: "POST",
      url: url_string,
      processData: false,
      contentType: false,
      data: formData_importexcel,
      success: function (data, status, xhr) {
        var data = JSON.parse(data);
        console.log(data);
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
            text: data.message,
            confirmButtonText: 'ตกลง',
          }).then((result) => {
            location.reload();

          });
        }


      }
    });

  } else {
    // error handle
    Swal.fire({
      icon: 'error',
      title: 'ขออภัย...',
      text: 'กรุณาตรวจสอบข้อมูลให้ถูกต้อง',
      confirmButtonText: 'ตกลง',
    }).then((result) => {
      return;

    });
  }

});

function onaction_createorupdate(ID_Excel = null) { //มันมาเข้า method นี้

  var type = $('#button_salesmanageModal').attr("data-status");

  var form = $('#form_salesmanage')[0];
  var formData = new FormData(form);

  switch (type) {
    case 'create':
      var url_string = "index.php?controller=ResultSales&action=create_sales";
      if (!$("#form_salesmanage").validate().form()) {
        Swal.fire({
          icon: 'error',
          title: 'ขออภัย...',
          text: 'กรุณาตรวจสอบข้อมูลให้ถูกต้อง',
          confirmButtonText: 'ตกลง',
        }).then((result) => {
          return;

        });
      } else {
        console.log(formData);
        $.ajax({
          type: "POST",
          url: url_string,
          data: formData,
          processData: false,
          contentType: false,
          success: function (res, status, xhr) {
            var data = JSON.parse(res);
            console.log(data);
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

      break;
    case 'edit':
      var ID_Excel = $("#button_salesmanageModal").attr("data-id");

      var url_string = "index.php?controller=ResultSales&action=edit_sales&ID_Excel=" + ID_Excel;
      if (!$("#form_salesmanage").validate().form()) {
        Swal.fire({
          icon: 'error',
          title: 'ขออภัย...',
          text: 'กรุณาตรวจสอบข้อมูลให้ถูกต้อง',
          confirmButtonText: 'ตกลง',
        }).then((result) => {
          return;

        });
      } else {
        $.ajax({
          type: "POST",
          url: url_string,
          data: formData,
          processData: false,
          contentType: false,
          success: function (data, status, xhr) {
            var data = JSON.parse(data);
            console.log(data);
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
                text: 'มีบางอย่างผิดพลาด',
                confirmButtonText: 'ตกลง',
              }).then((result) => {
                location.reload();

              });
            }
          }
        });
      }

      break;
    default:
      // ..
      break;
  }
}


function importShow() {

  /* modal show  */
  $('#importsalesModal').modal('show');

}


$(document).ready(function() {
  $.fn.modal.Constructor.prototype._enforceFocus = function() {};
  $.fn.select2.defaults.set("theme", "classic");

  $('.js-example-basic-single').select2({  language: "th",dropdownParent: $('#salesmanageModal'),  dropdownAutoWidth : true   , width: '100%'});

});
