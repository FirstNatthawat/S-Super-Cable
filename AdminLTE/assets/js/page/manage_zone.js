var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},

]
var dataTable_ = $('#tbl_zonemanagement').DataTable({
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

$("#ID_Employee").select2();
$("#province").change(function() {
  if($(this).val()=='1'){
    //$("#amphure_id").prop('disabled', false);
  }else{
    //$("#amphure_id").prop('disabled', true);
  }
});

var form_validte = $("#form_zonemanage").validate({
  rules: {
    ID_Zone: {

    },
    ID_Employee: {
      required: true,
    },

    PROVINCE_ID: {
      required: true,
    },
    AMPHUR_ID: {
      //required: true,
    },
    action: "required"
  },
  messages: {
    ID_Zone: {

    },
    ID_Employee: {
      required: "กรุณาใส่ข้อมูล",
    },
    PROVINCE_ID: {
      required: "กรุณาใส่ข้อมูล",
    },
    AMPHUR_ID: {
    //  required: "กรุณาใส่ข้อมูล",
    },
    action: "กรุณาใส่ข้อมูล"
  }, errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  }
});


function zonedownloadexcel(){

  var url_string = "index.php?controller=Zone&action=download_zone";
  $.ajax({
    type: "POST",
    url: "index.php?controller=Zone&action=download_zone",
    data: {
      "page": 'download_zone'
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


function zonemanageShow(type, ID_Zone = null) {
  var title = "";

  /* clear old form value */
  $('#form_zonemanage')[0].reset();

  switch (type) {
    case 'create':
      title = "สร้างโซนพนักงาน";

      $('#div_idzone').show();

      // set id
      $('#button_zonemanageModal').attr("data-id", null);
      $("#ID_Employee").attr('multiple','multiple');
      break;
    case 'edit':
      title = "เเก้ไขโซนพนักงาน";
      //clear error if exists
      form_validte.resetForm();
      $("#ID_Employee").removeAttr('multiple');
      $.ajax({
        url: "index.php?controller=Zone&action=findbyID_Zone",
        data: {
          "ID_Zone": ID_Zone
        },
        type: "POST",
        dataType: 'json',
        async: false,
        success: function (response, status) {
          /* set input value */
          $('#div_idpromotion').hide();
          console.log(response);
          // case: dropdown
          $('#ID_Employee')
            .val(response.data.ID_Employee)
            .trigger('change');
          $('#province')
            .val(response.data.PROVINCE_ID)
            .trigger('change');
          $('#amphure_id')
            .val(response.data.AMPHUR_ID)
            .trigger('change');

          // set id
          $('#button_zonemanageModal').attr("data-id", ID_Zone);
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
  $('#zonemanageTitle').html(title);

  /* set button event  */
  $('#button_zonemanageModal').attr("data-status", type);

  /* modal show  */
  $('#zonemanageModal').modal('show');
}
function onaction_deletezone(ID_Zone) {
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
        url: "index.php?controller=Zone&action=delete_zone",
        data: {
          "ID_Zone": ID_Zone
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
function onaction_createorupdate(ID_Zone = null) {

  var type = $('#button_zonemanageModal').attr("data-status");

  var form = $('#form_zonemanage')[0];
  var formData = new FormData(form);

  switch (type) {
    case 'create':
      var url_string = "index.php?controller=Zone&action=create_zone";
      if (!$("#form_zonemanage").validate().form()) {
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
      var ID_Zone = $("#button_zonemanageModal").attr("data-id");

      var url_string = "index.php?controller=Zone&action=edit_zone&ID_Zone=" + ID_Zone;
      if (!$("#form_zonemanage").validate().form()) {
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
  $('#importzoneModal').modal('show');

}

$(document).ready(function() {
  $.fn.modal.Constructor.prototype._enforceFocus = function() {};
  $.fn.select2.defaults.set("theme", "classic");

  $('.js-example-basic-multiple').select2({  language: "th",dropdownParent: $('#zonemanageModal'),  dropdownAutoWidth : true   , width: '100%'});

});


