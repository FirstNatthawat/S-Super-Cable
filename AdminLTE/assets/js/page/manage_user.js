var columns = [
  {"width": "10%", "class": "text-center"},
  {"width": "30%", "class": "text-center"},
  {"width": "20%", "class": "text-left"},
  {"width": "20%", "class": "text-center"},
  {"width": "30%", "class": "text-center"},

]

var dataTable_ = $('#tbl_usermanagement').DataTable({
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


// field: {
//     required: true,
//     extension: "xls|csv"
//   }
var form_validte = $("#form_usermanage").validate({
  rules: {
    ID_Employee: {
      required: true,
      minlength: 3
    },
    Name_Employee: {
      required: true,
      minlength: 3
    },
    Surname_Employee: {
      required: true,
      minlength: 3
    },
    Username_Employee: {
      required: true,
      minlength: 3
    },
    Password_Employee: {
      required: true,
      minlength: 3
    },
    Password_Employee_Confirm: {
      minlength: 3,
    },
    Email_Employee: {
      required: true,
      minlength: 3
    },
    action: "required"
  },
  messages: {
    ID_Employee: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Name_Employee: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Surname_Employee: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Username_Employee: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Password_Employee: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Password_Employee_Confirm: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร",
      equalTo: "กรุณากรอกรหัสผ่านให้เหมือนกัน"
    },
    Email_Employee: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    action: "กรุณาใส่ข้อมูล"
  }, errorPlacement: function (error, element) {
    if (element.attr("name") == "Password_Employee" || element.attr("name") == "Password_Employee_Confirm") {

      error.insertAfter(".error_replacement").after('<br>');

    } else {
      error.insertAfter(element)
    }
  }

});

function usermanageShow(type, ID_Employee = null) {
  var title = "";

  /* clear old form value */
  $('#form_usermanage')[0].reset();

  switch (type) {
    case 'create':
      title = "สร้างผู้ใช้";

      $('#div_idemployee').show();

      // div password show
      $("#div_password").show();

      $('#resetpassword').hide();

      // change password label text
     // $('#lbl_Password_Employee').html("รหัสผ่าน:");

      // set id
      $('#button_usermanageModal').attr("data-id", null);
      break;
    case 'edit':
      title = "อัปเดตผู้ใช้";
      //clear error if exists
      form_validte.resetForm();

      // div password show
      $("#div_password").hide();
      // div reset password hide
      $('#resetpassword').show();

      // change password label text
     // $('#lbl_Password_Employee').html("New Password:");
      $.ajax({
        url: "index.php?controller=Employee&action=findbyID",
        data: {
          "ID_Employee": ID_Employee
        },
        type: "POST",
        dataType: 'json',
        async: false,
        success: function (response, status) {
          /* set input value */
          $('#div_idemployee').hide();

          // $('#ID_Employee').val(response.data.ID_Employee);
          $('#Name_Employee').val(response.data.Name_Employee);
          $('#Surname_Employee').val(response.data.Surname_Employee);
          $("#Username_Employee").val(response.data.Username_Employee);
          $('#Email_Employee').val(response.data.Email_Employee);

          // case: dropdown
          $('#User_Status_Employee')
            .val(response.data.User_Status_Employee)
            .trigger('change');

          // set id
          $('#button_usermanageModal').attr("data-id", ID_Employee);
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
  $('#usermanageTitle').html(title);

  /* set button event  */
  $('#button_usermanageModal').attr("data-status", type);

  /* modal show  */
  $('#usermanageModal').modal('show');
}

function onaction_deleteuser(ID_Employee) {
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
        url: "index.php?controller=Admin&action=delete_user",
        data: {
          "ID_Employee": ID_Employee
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

$("#resetpassword").on('click', function (event) {
  // div password show
  $('#div_password').show();
  // this hide
  $(this).hide();
});


// case: ตอนอัพโหลดไฟล์ excel validate ว่าใช่ไฟล์ excel ไหมถ้าไม่ใช่ขึ้นแจ้งเตือนว่า type ไม่ตรง
$('#form_importexcel #examfile').validate({
  rules: {
    file: {

      extension: "png|jpeg|jpg|gif",

    }
  },
  messages: {
    file: "กรุณาอัพโหลดไฟล์รูป ที่นามสกุล .png, .jpeg, .jpg,.gif  เท่านั้น"
  },

  errorPlacement: function (error, element) {
    //แจ้งเตือนผิด format
    Swal.fire({
      icon: 'error',
      title: 'ขออภัย...',
      text: "กรุณาอัพโหลดรูป ที่นามสกุล .png, .jpeg, .jpg, .gif เท่านั้น",
      confirmButtonText: 'ตกลง',
    }).then((result) => {
      // break
      location.reload();

    });
  }
});
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
  var url_string = "index.php?controller=Admin&action=export_excel_test_user";
  $.ajax({
    type: "POST",
    url: "index.php?controller=Admin&action=export_excel_test_user",
    data: {
      "page": 'manage_user'
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

$("#button_importuserModal").on('click', function (event) {
  var form_importexcel = $('#form_importexcel')[0];
  var formData_importexcel = new FormData(form_importexcel);

  // case: ตอนอัพโหลดไฟล์ excel validate ว่าใช่ไฟล์ excel ไหมถ้าไม่ใช่ขึ้นแจ้งเตือนว่า type ไม่ตรง
  $("#form_importexcel").validate().form();
  /* eof */

  var url_string = "index.php?controller=Admin&action=import_excel_user";
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


$("#Password_Employee a").on('click', function (event) {
  event.preventDefault();
  if ($('#Password_Employee input').attr("type") == "text") {
    $('#Password_Employee input').attr('type', 'password');
    $('#Password_Employee i').addClass("fa-eye-slash");
    $('#Password_Employee i').removeClass("fa-eye");
  } else if ($('#Password_Employee input').attr("type") == "password") {
    $('#Password_Employee input').attr('type', 'text');
    $('#Password_Employee i').removeClass("fa-eye-slash");
    $('#Password_Employee i').addClass("fa-eye");
  }
});
$("#Password_Employee_Confirm a").on('click', function (event) {
  event.preventDefault();
  if ($('#Password_Employee_Confirm input').attr("type") == "text") {
    $('#Password_Employee_Confirm input').attr('type', 'password');
    $('#Password_Employee_Confirm i').addClass("fa-eye-slash");
    $('#Password_Employee_Confirm i').removeClass("fa-eye");
  } else if ($('#Password_Employee_Confirm input').attr("type") == "password") {
    $('#Password_Employee_Confirm input').attr('type', 'text');
    $('#Password_Employee_Confirm i').removeClass("fa-eye-slash");
    $('#Password_Employee_Confirm i').addClass("fa-eye");
  }
});

function onaction_createorupdate(ID_Employee = null) {

  var type = $('#button_usermanageModal').attr("data-status");

  var form = $('#form_usermanage')[0];
  var formData = new FormData(form);

  switch (type) {
    case 'create':
      var url_string = "index.php?controller=Admin&action=create_user";
      if (!$("#form_usermanage").validate().form()) {
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
      }


      break;
    case 'edit':
      var ID_Employee = $("#button_usermanageModal").attr("data-id");

      var url_string = "index.php?controller=Admin&action=edit_user&ID_Employee=" + ID_Employee;
      if (!$("#form_usermanage").validate().form()) {
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
      }

      break;
    default:
      // ..
      break;
  }
}


function importShow() {

  /* modal show  */
  $('#importuserModal').modal('show');

}
