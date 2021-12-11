var columns = [
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "5%", "class": "text-center"},
  {"width": "20%", "class": "text-center"},
]

var dataTable_ = $("#tbl_invoicemanagement").DataTable({
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


var form_validte = $("#form_invManage").validate({
  rules: {
    Invoice_No: {
      required: true,
    },
    Name_Company: {
      required: true,
    },
  },
  messages: {
    Invoice_No: {
      required: "กรุณาใส่ข้อมูล",
    },
    Name_Company: {
      required: "กรุณาใส่ข้อมูล",
    },
  },
  errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  },
});


function invoicemanageShow(type, ID_Message ) {

  var title = "" ;

  /* clear old form value */
  $('#form_invManage')[0].reset();


  switch(type)
  {
    case "create":
      title = "สร้างใบเสนอราคา";

      // set id
      $('#button_invManageModal').attr("data-id", null);

      //
      //form_validte.resetForm();

      break;
    case "edit":

      // แก้ไขข่าวสาร
      title = "แก้ไขใบเสนอราคา";

      //clear error if exists
      //form_validte.resetForm();
      get_inv_to_edit(ID_Message);

      // set id
      $('#button_newsManageModal').attr("data-id", ID_Message);
      break;
    case 'view':
      title = "ดูข่าวสาร ";
      //clear error if exists
      form_validte.resetForm();

      //get_news_to_view(ID_Message);


      //$('#form_companymanage input').attr('readonly', 'readonly');
      //$('#form_companymanage select').attr("disabled", true);
      //$('#button_companymanageModal').hide();

      break;
    default:
      // ..
      break;
  }

  /* set title */
  $('#invManageTitle').html(title);

  /* set button event  */
  $('#button_invManageModal').attr("data-status", type);

  /* modal show  */
  if(type=='view'){
    $('#invManageViewModal').modal('show')
  }else{
    $('#invManageModal').modal('show');
  }
}


function addItem(){
  var id_goods = $("#id_goods").val();
  var qty = $("#qty").val();
  var p_discout_price = $("#p_discout_price").val();
  if(p_discout_price <= 0){
    p_discout_price = 0;
  }
  if(qty===''){
    alert('ใส่จำนวน');
    return false;
  }
  qty = parseInt(qty);
  $.ajax({
    url: "index.php?controller=Goods&action=findbyID_Goods",
    data: {
      "ID_Goods": id_goods
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response, status) {
      /*
      $('#div_idgoods').hide();
      console.log(response);
      $('#ID_Goods').val(response.data.ID_Goods);
      $('#Name_Goods').val(response.data.Name_Goods);
      $('#Detail_Goods').val(response.data.Detail_Goods);
      $('#Price_Goods').val(response.data.Price_Goods);
      // set id
      $('#button_goodsmanageModal').attr("data-id", ID_Goods);
      */
      //alert(JSON.stringify(response.data));
      var total = qty*response.data.Price_Goods;
      if(p_discout_price!==undefined && p_discout_price!==null){
        total = total-parseInt(p_discout_price);
      }
      var chk = true;
      $('input[name^="goods_array"]').each(function(index,data){
        var value = $(this).val();
        if(id_goods==value){
          chk = false;
          var getQty = $('#qty_'+value).val();
          var newQty = parseInt(getQty)+qty;
          total = newQty*response.data.Price_Goods
          if(p_discout_price!==undefined && p_discout_price!==null){
            total = total-parseInt(p_discout_price);
          }
          $('#td_qty_'+value).text(newQty);
          $('#qty_'+value).val(newQty);
          $('#td_total_'+value).text(total);
        }
      });
      var html = '';
      html += '<tr id="tr_'+id_goods+'">';
      html += '<td></td>';
      html += '<td>'+response.data.Name_Goods+'</td>';
      html += '<td style="text-align: center;" id="td_qty_'+id_goods+'">'+qty+'</td>';
      html += '<td style="text-align: center;">'+response.data.Price_Goods+'</td>';
      html += '<td style="text-align: center;">'+p_discout_price+'</td>';
      html += '<td style="text-align: center;" id="td_total_'+id_goods+'">'+total+'</td>';
      html += '<td>';
      html += '<input type="hidden" name="goods_array[]" value="'+id_goods+'"/>';
      html += '<input type="hidden" name="qty_array[]" id="qty_'+id_goods+'" value="'+qty+'"/>';
      html += '<input type="hidden" name="p_discout_price[]" id="qty_'+id_goods+'" value="'+p_discout_price+'"/>';
      html += '<a href="javascript:void(0)" onclick="delItem('+id_goods+')"><i class="fas fa-trash-alt"></i></a></td>';
      html += '</tr>';
      if(chk===true){
        $("#productList").append(html);
      }
    },
    error: function (xhr, status, exception) {
      //console.log(xhr);
    }
  });
}

function delItem(id){
  //alert(id);
  $('#tr_'+id).remove();
}

function onaction_createoredit(ID_Goods = null) {

  var type = $('#button_invManageModal').attr("data-status");

  var form = $('#form_invManage')[0];
  var formData = new FormData(form);

  switch (type) {
    case 'create':
      var url_string = "index.php?controller=Invoice&action=create_invoice";
      if (!$("#form_invManage").validate().form()) {
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
      //alert("Edit");
      var ID_Invoice = $("#button_invManageModal").attr("data-id");
      var url_string = "index.php?controller=Invoice&action=edit_invoice&ID_Invoice="+ID_Invoice;

      if (!$("#form_invManage").validate().form()) {
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
    default:
      // ..
      break;
  }

}

function get_inv_to_edit(id){
  $("#button_invManageModal").attr("data-id",id);
  $.ajax({
    url: "index.php?controller=Invoice&action=findbyID",
    data: {
      "ID_Invoice": id
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response, status) {
      //alert(JSON.stringify(response.data));
      var data = response.data;
      $('#Invoice_No').val(data.Invoice_No);
      $('#Invoice_Date').val(data.Invoice_Date);
      $('#Credit_Term_Company').val(data.Credit_Term_Company);
      $('#Name_Company').val(data.Name_Company);
      $('#Contact_Name_Company').val(data.Contact_Name_Company);
      $('#Address_Company').val(data.Address_Company);
      $('#PROVINCE_ID').val(data.PROVINCE_ID);
      $('#AMPHUR_ID').val(data.AMPHUR_ID);
      $('#Email_Company').val(data.Email_Company);
      $('#Tel_Company').val(data.Tel_Company);
      $('#Tax_Number_Company').val(data.Tax_Number_Company);
      $('#Vat_Type').val(data.Vat_Type);
      $('#Percent_Vat').val(data.Percent_Vat);
      $('#Vat').val(data.Vat);
      $('#Total').val(data.Total);
      $('#Grand_Total').val(data.Grand_Total);
      $('#ID_Company').val(data.ID_Company);
      $('#ID_Setting_Vat').val(data.ID_Setting_Vat);

      if(data.invoice_detail.length > 0){
        for(var i=0; i<data.invoice_detail.length; i++){
          var html = '';
          var dataItem = data.invoice_detail[i];
          var id_goods = dataItem.ID_Goods;
          var p_discout_price = dataItem.Discount_Price;
          //alert(">"+id_goods);
          html += '<tr id="tr_'+id_goods+'">';
          html += '<td></td>';
          html += '<td>'+dataItem.Name_Goods+'</td>';
          html += '<td style="text-align: center;" id="td_qty_'+id_goods+'">'+dataItem.Quantity_Goods+'</td>';
          html += '<td style="text-align: center;">'+dataItem.Price_Goods+'</td>';
          html += '<td style="text-align: center;">'+p_discout_price+'</td>';
          html += '<td style="text-align: center;" id="td_total_'+id_goods+'">'+dataItem.Total+'</td>';
          html += '<td>';
          html += '<input type="hidden" name="goods_array[]" value="'+id_goods+'"/>';
          html += '<input type="hidden" name="qty_array[]" id="qty_'+id_goods+'" value="'+dataItem.Quantity_Goods+'"/>';
          html += '<input type="hidden" name="p_discout_price[]" id="qty_'+id_goods+'" value="'+p_discout_price+'"/>';
          html += '<a href="javascript:void(0)" onclick="delItem('+id_goods+')"><i class="fas fa-trash-alt"></i></a></td>';
          html += '</tr>';
          $("#productList").append(html);
        }
      }

    },
    error: function (xhr, status, exception) {
      //console.log(xhr);
    }
  });
}

function onaction_deleteinvoice(ID_Invoice){
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
        url: "index.php?controller=Invoice&action=delete_invoice",
        data: {
          "ID_Invoice": ID_Invoice
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

$("#ID_Company").change(function() {
  get_company($(this).val());
});

function get_company(ID_Company){
  $.ajax({
    url: "index.php?controller=Invoice&action=get_company",
    data: {
      "ID_Company": ID_Company
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response, status) {
      //alert(JSON.stringify(response.data));
      var data = response.data;

      $('#Credit_Term_Company').val(data.Credit_Term_Company);
      $('#Name_Company').val(data.Name_Company);
      $('#Contact_Name_Company').val(data.Contact_Name_Company);
      $('#Address_Company').val(data.Address_Company);
      $('#PROVINCE_ID').val(data.PROVINCE_ID);
      $('#AMPHUR_ID').val(data.AMPHUR_ID);
      $('#Email_Company').val(data.Email_Company);
      $('#Tel_Company').val(data.Tel_Company);
      $('#Tax_Number_Company').val(data.Tax_Number_Company);
      $('#ID_Company').val(data.ID_Company);
      //$('#ID_Setting_Vat').val(data.ID_Setting_Vat);


    },
    error: function (xhr, status, exception) {
      //console.log(xhr);
    }
  });
}

$('input[name="Invoice_Date"]').datepicker({
  format: 'yyyy-mm-dd'
});

$('#invManageModal').on('hidden.bs.modal', function () {
  $("#productList tbody tr").remove();
});
