var columns = [{
    "width": "5%",
    "class": "text-left"
  },
  {
    "width": "5%",
    "class": "text-center"
  },
  {
    "width": "5%",
    "class": "text-center"
  },
  {
    "width": "5%",
    "class": "text-center"
  },
  {
    "width": "5%",
    "class": "text-right"
  },
  {
    "width": "5%",
    "class": "text-center"
  },
  {
    "width": "5%",
    "class": "text-center"
  },
  {
    "width": "5%",
    "class": "text-center"
  },
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
    "aaSorting": [
      [0, 'desc']
    ],
    "paginate": {
      "sFirst": "หน้าแรก",
      "sPrevious": "ก่อนหน้า",
      "sNext": "ถัดไป",
      "sLast": "หน้าสุดท้าย",
      "oAria": {
        "sSortAscending": ": เปิดใช้งานการเรียงข้อมูลจากน้อยไปมาก",
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
    },
    Type_BorrowOrReturn: {
      required: true,
    },
    Have_To_Return: {
      required: true,
    }
  },
  messages: {
    ID_Promotion: {
      required: "กรุณาใส่ข้อมูล",
    },
    Amount_BorrowOrReturn: {
      required: "กรุณาใส่ข้อมูล",
    },
    Type_BorrowOrReturn: {
      required: "กรุณาใส่ข้อมูล",
    }

  },
  errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  },
  submitHandler: function (form) {
    var dataId = $('#button_modal').attr("data-id");
    var dataStatus = $('#button_modal').attr("data-status");
    if (dataStatus == "create") {
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
      $('#button_modal').attr("data-id", "");
      $('#Type_BorrowOrReturn').val('1');

      $('#devDetail_BorrowOrReturns').show();
      break;
    case 'edit':
      title = "เเก้ไขสินค้าสินค้าส่งเสริมการขาย";
      //clear error if exists
      onaction_edit(selectID);
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

  $('#ID_Promotion option').attr("disabled", false);
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
          title: 'รอดำเนินการอนุมัติ',
          confirmButtonText: 'ตกลง',
        }).then((result) => {
          location.reload();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'ขออภัย...',
          text: res.message,
          confirmButtonText: 'ตกลง',
        }).then((result) => {
          location.reload();
        });
      }
    }
  });
}


function onaction_delete(id) {
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

function onaction_edit(id) {

  $('#modelTitle').html("คืนสินค้า");
  /* set button event  */
  $('#button_modal').attr("data-status", 'create');
  /* modal show  */
  $('#formDataModal').modal('show');
  $('#Type_BorrowOrReturn').val('2');
  $('#devDetail_BorrowOrReturns').hide();
  //$('#ID_Promotion').val(id);
  //$('#ID_Promotion option').attr("disabled", true);
  $('#ID_Promotion').attr("readonly", "readonly");
  $.ajax({
    type: "GET",
    url: "index.php?controller=Borrow&action=borrowById&ID_BorrowOrReturn=" + id,
    processData: false,
    contentType: false,
    success: function (res, status, xhr) {
      $("#Amount_BorrowOrReturn").val(res.data.Amount_BorrowOrReturn);
      $("#ID_Promotion").val(res.data.ID_Promotion);

    }
  });
}



function fetch_borrowdata() {
  google.charts.load("current", {
    packages: ['corechart']
  });
  google.charts.setOnLoadCallback(drawChart);

}

function drawChart() {


  $.ajax({
    type: "POST",
    url: "index.php?controller=Borrow&action=fetch_borrow",
    processData: false,
    contentType: false,
    success: function (res, status, xhr) {
      var response = JSON.parse(res);
      var array_borrowrank = [];

      var Header = ['Element', 'จำนวนครั้งที่ยืม'];
      array_borrowrank.push(Header);
      $.each(response.data, function (index, value) {

        var temp = [];
        temp.push(value.Name_Promotion);
        temp.push(parseInt(value.count));

        array_borrowrank.push(temp);
      });
      console.log(array_borrowrank);

      var data = google.visualization.arrayToDataTable([array_borrowrank]);

      var options = {
        title: 'รายการของที่ยืมมากที่สุด (เมื่ออนุมัติเเล้ว)'
      };


      var data = google.visualization.arrayToDataTable(array_borrowrank);
      // Instantiate and draw the chart.
      var chart = new google.visualization.BarChart(document.getElementById('columnchart_values'));
      chart.draw(data, options);

    }
  });



}




$(document).ready(function () {
  fetch_borrowdata();
});
