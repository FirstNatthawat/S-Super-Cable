var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "10%", "class": "text-center"},
  {"width": "10%", "class": "text-left"},
  {"width": "10%", "class": "text-right"},
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

$('#tbl_companymanagement').on( 'keyup', function () {
  alert(this.value);
   // table.search( this.value ).draw();
} );

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
      required: true,
      minlength: 3
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
      required: "กรุณาใส่ข้อมูล",
      minlength: "ข้อมูลต้องมีอย่าง 3 ตัวอักษร"
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
    case 'view':
      title = "ดูข้อมูลบริษัทลูกค้า ";
      //clear error if exists
      form_validte.resetForm();

      onaction_getinptval(ID_Company);

      $('#form_companymanage input').attr('readonly', 'readonly');
      $('#form_companymanage select').attr("disabled", true);
      $('#form_companymanage textarea').attr("disabled", true);
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
      // case: dropdown
      $('#IS_Blacklist')
        .val(response.data.IS_Blacklist)
        .trigger('change');
      $('#Cause_Blacklist').val(response.data.Cause_Blacklist);
      $('#ID_Employee').val(response.data.ID_Employee);
      // set id
      $('#button_companymanageModal').attr("data-id", ID_Company);
    },
    error: function (xhr, status, exception) {
      //console.log(xhr);
    }
  });
}



$('#province').on('change', function () {
  var province_id = $('#province').val();
  getAmphur(province_id);

});


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

function search(){
  alert("search");
}


$(document).ready(function() {
    $("#com_name").keyup(function () {
      //alert($(this).val());
      //

      $.ajax({
        url: "index.php?controller=ReportCompany&action=getAjax",
        data: {keyword:$(this).val()},
        type: "POST",
        async: false,
        success: function (response) {
          let amphures = response;
          //alert(response);
          $("#com_tb tbody").html(response);
        },
        error: function (request, status, error) {
          console.log(request.responseText);
        }
      });
      //
    });
});
