var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-right"},
  {"width": "5%", "class": "text-center"},

]

var dataTable_ = $('#tbl_goodsmanagement').DataTable({
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

var form_validte = $("#form_goodsmanage").validate({
  rules: {
    ID_Goods: {
      //required: true,
    },
    Name_Goods: {
      required: true,
    },
    Detail_Goods: {
     // required: true,
    },
    Price_Goods: {
      required: true,
    },
    action: "required"
  },
  messages: {
    ID_Goods: {
      // required: "กรุณาใส่ข้อมูล",
    },
    Name_Goods: {
      required: "กรุณาใส่ข้อมูล",
    },
    Detail_Goods: {
     // required: "กรุณาใส่ข้อมูล",
    },
    Price_Goods: {
      required: "กรุณาใส่ข้อมูล",
    },
    action: "กรุณาใส่ข้อมูล"
  }, errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  }
});

function goodsmanageShow(type, ID_Goods = null) {
  var title = "";

  /* clear old form value */
  $('#form_goodsmanage')[0].reset();

  switch (type) {
    case 'create':
      title = "สร้างสินค้า";

      $('#div_idgoods').show();

      // set id
      $('#button_goodsmanageModal').attr("data-id", null);
      break;
    case 'edit':
      title = "เเก้ไขสินค้า";
      //clear error if exists
      form_validte.resetForm();

      $.ajax({
        url: "index.php?controller=Goods&action=findbyID_Goods",
        data: {
          "ID_Goods": ID_Goods
        },
        type: "POST",
        dataType: 'json',
        async: false,
        success: function (response, status) {
          /* set input value */
          $('#div_idgoods').hide();
          console.log(response);
          $('#ID_Goods').val(response.data.ID_Goods);
          $('#Name_Goods').val(response.data.Name_Goods);
          $('#Detail_Goods').val(response.data.Detail_Goods);
          $('#Price_Goods').val(response.data.Price_Goods);

          // set id
          $('#button_goodsmanageModal').attr("data-id", ID_Goods);
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
  $('#goodsmanageTitle').html(title);

  /* set button event  */
  $('#button_goodsmanageModal').attr("data-status", type);

  /* modal show  */
  $('#goodsmanageModal').modal('show');
}

function onaction_deletegoods(ID_Goods) {
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
        url: "index.php?controller=Goods&action=delete_goods",
        data: {
          "ID_Goods": ID_Goods
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
  var url_string = "index.php?controller=Goods&action=export_excel_test_goods";
  $.ajax({
    type: "POST",
    url: "index.php?controller=Goods&action=export_excel_test_goods",
    data: {
      "page": 'manage_goods'
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

$("#button_importgoodsModal").on('click', function (event) {
  var form_importexcel = $('#form_importexcel')[0];
  var formData_importexcel = new FormData(form_importexcel);

  // case: ตอนอัพโหลดไฟล์ excel validate ว่าใช่ไฟล์ excel ไหมถ้าไม่ใช่ขึ้นแจ้งเตือนว่า type ไม่ตรง
  $("#form_importexcel").validate().form();
  /* eof */

  var url_string = "index.php?controller=Goods&action=import_excel_goods";
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

function onaction_createorupdate(ID_Goods = null) {

  var type = $('#button_goodsmanageModal').attr("data-status");

  var form = $('#form_goodsmanage')[0];
  var formData = new FormData(form);

  switch (type) {
    case 'create':
      var url_string = "index.php?controller=Goods&action=create_goods";
      if (!$("#form_goodsmanage").validate().form()) {
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
      var ID_Goods = $("#button_goodsmanageModal").attr("data-id");

      var url_string = "index.php?controller=Goods&action=edit_goods&ID_Goods=" + ID_Goods;
      if (!$("#form_goodsmanage").validate().form()) {
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
  $('#importgoodsModal').modal('show');

}
