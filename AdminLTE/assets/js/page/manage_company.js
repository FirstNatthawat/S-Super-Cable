var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "10%", "class": "text-center"},
  {"width": "10%", "class": "text-left"},
  {"width": "15%", "class": "text-right"},
  {"width": "20%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},
  {"width": "25%", "class": "text-center"},

]

var dataTable_ = $('#tbl_companymanagement').DataTable({
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

var form_validte = $("#form_companymanage").validate({
  rules: {
    ID_Company: {
      //required: true,
    },
    Name_Company: {
      required: true,
      minlength: 3
    },
    Address_Company: {
      // required: true,
      // minlength: 3
    },
    PROVINCE_ID: {
      required: true,
    },
    AMPHUR_ID: {
      //required: true,
    },
    AMPHUR_NAME: {
     // required: true,
    },
    PROVINCE_NAME: {
      //required: true,
    },
    Tel_Company: {
      required: true,
      minlength: 10
    },
    Email_Company: {
      required: true,
      minlength: 3
    },
    Tax_Number_Company: {
      required: true,
      minlength: 13
    },
    Credit_Limit_Company: {
      required: true,
    },
    Credit_Term_Company: {
      required: true,
      minlength: 3
    },
    Cluster_Shop_ID: {
      required: true,
    },
    Contact_Name_Company: {
      required: false,
    },
    IS_Blacklist: {
      required: true,
    },
    Cause_Blacklist: {
      required: false,
    },
    action: "required"
  },
  messages: {
    ID_Company: {
      // required: "กรุณาใส่ข้อมูล",
    },
    Name_Company: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Address_Company: {
      // required: "กรุณาใส่ข้อมูล",
      // minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    PROVINCE_ID: {
      required: "กรุณาใส่ข้อมูล",
    },
     AMPHUR_ID: {
    //   required: "กรุณาใส่ข้อมูล",
     },
    AMPHUR_NAME: {
      // required: "กรุณาใส่ข้อมูล",
     },
     PROVINCE_NAME: {
      // required: "กรุณาใส่ข้อมูล",
     },
    Tel_Company: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 10 ตัวอักษร"
    },
    Email_Company: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Tax_Number_Company: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 13 ตัวอักษร"
    },
    Credit_Limit_Company: {
      required: "กรุณาใส่ข้อมูล",
    },
    Credit_Term_Company: {
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
    },
    Cluster_Shop_ID: {
      required: "กรุณาใส่ข้อมูล",
    },
    Cluster_Shop_Name: {
      //required: "กรุณาใส่ข้อมูล",
    },
    IS_Blacklist: {
      required: "กรุณาใส่ข้อมูล",
    },
    action: "กรุณาใส่ข้อมูล"
  }, errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  }
});

function companymanageShow(type, ID_Company = null) {
  //clear ค่าเก่า
  $('#form_companymanage input').attr('readonly', false);
  $('#form_companymanage select').attr("disabled", false);

  $('#button_companymanageModal').show();


  var title = "";

  /* clear old form value */
  $('#form_companymanage')[0].reset();

  switch (type) {
    case 'create':
      title = "สร้างบริษัทลูกค้า";

      $('#div_idcompany').show();

      // set id
      $('#button_companymanageModal').attr("data-id", null);
      $('#form_companymanage textarea').attr("disabled", false);
      $('#form_companymanage textarea').attr("readonly", false);
      $( '#form_companymanage input:radio' ).attr( 'disabled', false );

      $('#ID_Employee').val('');

      
      onaction_set_radiobuttonvalue( 0 );

      event_onclick_isblacklistradio(  0 );
      break;
    case 'edit':
      title = "เเก้ไขบริษัทลูกค้า ";
      //clear error if exists
      form_validte.resetForm();

      onaction_getinptval(ID_Company);
      $('#form_companymanage textarea').attr("disabled", false);
      $( '#form_companymanage input:radio' ).attr( 'disabled', false );

      break;
    case 'view':
      title = "ดูข้อมูลบริษัทลูกค้า ";
      //clear error if exists
      form_validte.resetForm();

      onaction_getinptval(ID_Company);

      $('#form_companymanage input').attr('readonly', 'readonly');
      $('#form_companymanage select').attr("disabled", true);
      $('#form_companymanage textarea').attr("disabled", true);
      $( '#form_companymanage input:radio' ).attr( 'disabled', true );
      $('#button_companymanageModal').hide();

      break;
    default:
      // ..
      break;
  }

  /* set title */
  $('#companymanageTitle').html(title);

  /* set button event  */
  $('#button_companymanageModal').attr("data-status", type);

  /* modal show  */
  $('#companymanageModal').modal('show');
}

function onaction_getinptval(ID_Company) {
  $.ajax({
    url: "index.php?controller=Company&action=findbyID_Company",
    data: {
      "ID_Company": ID_Company
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response, status) {
      /* set input value */
      $('#div_idcompany').hide();

      $('#ID_Company').val(response.data.ID_Company);
      $('#Name_Company').val(response.data.Name_Company);
      $('#Address_Company').val(response.data.Address_Company);
      // case: dropdown
      $('#province')
        .val(response.data.PROVINCE_ID)
        .trigger('change');
      $('#amphure_id')
        .val(response.data.AMPHUR_ID)
        .trigger('change');
      $("#Tel_Company").val(response.data.Tel_Company);
      $('#Email_Company').val(response.data.Email_Company);
      $('#Tax_Number_Company').val(response.data.Tax_Number_Company);
      $('#Credit_Limit_Company').val(response.data.Credit_Limit_Company);
      $("#Credit_Term_Company").val(response.data.Credit_Term_Company);
      // case: dropdown
      $('#Cluster_Shop_ID')
        .val(response.data.Cluster_Shop_ID)
        .trigger('change');
      $('#Contact_Name_Company').val(response.data.Contact_Name_Company);
      //$('input:radio[name="IS_Blacklist"]').attr('checked',true);
      $('#Cause_Blacklist').val(response.data.Cause_Blacklist);
      $('#ID_Employee').val(response.data.ID_Employee);
      // set id
      $('#button_companymanageModal').attr("data-id", ID_Company);

      // case : set radio button value
      var IS_Blacklist_Status = response.data.IS_Blacklist == "ใช่" ?  1 : 0;
      onaction_set_radiobuttonvalue( IS_Blacklist_Status );
      // case : set event ของ radio button ( เลือกไม่ใช่ให้ไม่สามารถพิมพ์เหตุผลได้)
      event_onclick_isblacklistradio( IS_Blacklist_Status );
    },
    error: function (xhr, status, exception) {
      //console.log(xhr);
    }
  });
}

function onaction_set_radiobuttonvalue(IS_Blacklist_Status){
  if(IS_Blacklist_Status == 1){
    $("#IS_Blacklist_True").attr('checked',true);
    $("#IS_Blacklist_False").attr('checked',false);
  }else{
    $("#IS_Blacklist_False").attr('checked',true);
    $("#IS_Blacklist_True").attr('checked',false);
  }
  
}
function onaction_deletecompany(ID_Company) {
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
        url: "index.php?controller=Company&action=delete_company",
        data: {
          "ID_Company": ID_Company
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
$('#form_importexcel').validate({
  rules: {
    file: {

      extension: "xlsx|xls|csv",

    }
  },
  messages: {
    file: "กรุณาอัพโหลดไฟล์ Excel ที่นามสกุล .xls , .xlsx, .csv เท่านั้น"
  },
  errorPlacement: function (error, element) {
    //แจ้งเตือนผิด format
    Swal.fire({
      icon: 'error',
      title: 'ขออภัย...',
      text: "กรุณาอัพโหลดไฟล์ Excel ที่นามสกุล .xls , .xlsx,  .csv เท่านั้น",
      confirmButtonText: 'ตกลง',

    }).then((result) => {
      // break
      location.reload();

    });
  }
});

// eof
function downloadExcel() {
  var url_string = "index.php?controller=Company&action=export_excel_test_company";
  $.ajax({
    type: "POST",
    url: "index.php?controller=Company&action=export_excel_test_company",
    data: {
      "page": 'manage_company'
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

$("#button_importcompanyModal").on('click', function (event) {
  var form_importexcel = $('#form_importexcel')[0];
  var formData_importexcel = new FormData(form_importexcel);
  var url_string = "index.php?controller=Company&action=import_excel_company";
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

function onaction_createorupdate(ID_Company = null) {

  var type = $('#button_companymanageModal').attr("data-status");

  var form = $('#form_companymanage')[0];
  var formData = new FormData(form);

  switch (type) {
    case 'create':
      var url_string = "index.php?controller=Company&action=create_company";
      if (!$("#form_companymanage").validate().form()) {
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
      var ID_Company = $("#button_companymanageModal").attr("data-id");

      var url_string = "index.php?controller=Company&action=edit_company&ID_Company=" + ID_Company;
      if (!$("#form_companymanage").validate().form()) {
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
  $('#importcompanyModal').modal('show');

}

$(document).ready(function(){


  

});


$('#province').on('change', function () {
  var province_id = $('#province').val();
  getAmphur(province_id);

});

function event_onclick_isblacklistradio(radio_value){
  // case : 1 = ใช่ , 0 = ไม่ใช่
  if(radio_value == 1){
    $('#Cause_Blacklist').attr('readonly', false);
  }else{
    $('#Cause_Blacklist').attr('readonly', true);
  }

}

function getAmphur(provice_id) {

  var optionStr = createOptionPlaceholder("กรุณาเลือกอำเภอ");

  $.ajax({
    url: "index.php?controller=Company&action=getAmphur",
    data: {
      "PROVINCE_ID": provice_id
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response) {
      let amphures = response;
      console.log(response);
      $.each(response, function (index, amphure) {

        optionStr += createOption(amphure.AMPHUR_ID, amphure.AMPHUR_NAME);

      });
      $('#amphure_id').html(optionStr);

    },
    error: function (request, status, error) {
      console.log(request.responseText);
    }
  });

}



function createOption(value, text) {
  return '<option value="' + value + '">' + text + '</option>';
}
function createOptionPlaceholder(text) {
  return '<option value="" disabled selected>' + text + '</option>';
}



