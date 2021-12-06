var columns = [
    {"width": "15%", "class": "text-center"},
    {"width": "20%", "class": "text-center"},
    {"width": "20%", "class": "text-center"},
    {"width": "30%", "class": "text-center"},
  ]

var dataTable_ = $("#tbl_news").DataTable({
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


var filesList = [];
var del_files = [];
var myDropzone;
Dropzone.autoDiscover = false;
$(document).ready(function () {
   intDropzone();
});


var form_validte = $("#form_newsManage").validate({
  rules: {
    Tittle_Message: {
      required: true,
    },
    Text_Message: {
      required: true,
    },
    action: "required"
  },
  messages: {
    Tittle_Message: {
      required: "กรุณาใส่ข้อมูล",
    },
    Text_Message: {
      required: "กรุณาใส่ข้อมูล",
    },
    action: "กรุณาใส่ข้อมูล"
  }, errorPlacement: function (error, element) {
    {
      error.insertAfter(element)
    }
  }
});


// show dialog create or edit
function  newsManageShow(type, ID_Message ) {
  var title = "" ;
  //$('.dz-preview').remove();
  /* clear old form value */
  $('#form_newsManage')[0].reset();
  $("#thumnails_award_pic").attr("src", "");
  $("#thumnails_award_pic2").attr("src", "");
  $("#thumnails_award_pic3").attr("src", "");

    switch(type)
    {
      case "create":
        title = "สร้างข่าวสาร";

        // set id
        $('#button_newsManageModal').attr("data-id", null);

        //
        form_validte.resetForm();

        break;
      case "edit":

        // แก้ไขข่าวสาร
        title = "แก้ไขข่าวสาร";

        //clear error if exists
        form_validte.resetForm();
        get_news_to_edit(ID_Message);

        // set id
        $('#button_newsManageModal').attr("data-id", ID_Message);
        break;
      case 'view':
      title = "ดูข่าวสาร ";
      //clear error if exists
      form_validte.resetForm();

      get_news_to_view(ID_Message);


      //$('#form_companymanage input').attr('readonly', 'readonly');
      //$('#form_companymanage select').attr("disabled", true);
      //$('#button_companymanageModal').hide();

      break;
      default:
        // ..
        break;
    }

    /* set title */
    $('#newsManageTitle').html(title);

    /* set button event  */
    $('#button_newsManageModal').attr("data-status", type);

    /* modal show  */
    if(type=='view'){
      $('#newsManageViewModal').modal('show')
    }else{
      $('#newsManageModal').modal('show');
    }
  }


function onAction_deleteMessage(ID_Message) {

  delete_news(ID_Message);
}


function onaction_createoredit(ID_Message = null) {


  for (var i in CKEDITOR.instances) {
    CKEDITOR.instances[i].updateElement();
  };

  var type = $('#button_newsManageModal').attr('data-status');

  var data = new FormData();

  var form_data = $('#form_newsManage').serializeArray();
  $.each(form_data, function (key, input) {
      data.append(input.name, input.value);
  });

  var ImgFile = myDropzone.getFilesWithStatus(Dropzone.ADDED);
  ImgFile.forEach((o)=>{
     //alert('vv');
     data.append("profile_news[]", o);
  });

  del_files.forEach((o)=>{
     data.append("del_files[]", o);
  });

  switch(type) {
    case 'create':
      create_news(data);
      break;
    case 'edit':
      update_new(data);
      break;
  }

}


// call ajax to insert data into server.
function create_news(formData)
{
  var url_string = "index.php?controller=News&action=create_news";

      if (!$("#form_newsManage").validate().form()) {
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
          enctype: 'multipart/form-data',
          success: function (res, status, xhr) {
            //var data = JSON.parse(res);
            var data = JSON.parse(res);
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
}

// set value into html tag
// call ajax get data from server.
var fArr = [];
function get_news_to_edit(ID_Message) {

  $.ajax({
    url: "index.php?controller=News&action=findbyID_Message",
    data: {
      "ID_Message": ID_Message
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response, status) {
      /* set input value */

      $('#Tittle_Message').val(response.data.Tittle_Message);
      CKEDITOR.instances['Text_Message'].setData(response.data.Text_Message);
      //fArr = [response.data.Picture_Message,response.data.Picture_Message2,response.data.Picture_Message3];
      response.data.img.forEach((o)=>{
        fArr.push(o);
      });
      //addImgView(fArr);
    },
    error: function (xhr, status, exception) {
      console.log(xhr);
    }
  });

}

function get_news_to_view(ID_Message) {

  $.ajax({
    url: "index.php?controller=News&action=findbyID_Message",
    data: {
      "ID_Message": ID_Message
    },
    type: "POST",
    dataType: 'json',
    async: false,
    success: function (response, status) {
      /* set input value */

      $('#Tittle_Message_view').html(response.data.Tittle_Message);
      $('#Text_Message_view').html(response.data.Text_Message);
      $("#thumnails_new_profile_view").attr("src", response.data.Picture_Message);
      $("#thumnails_new_profile_view2").attr("src", response.data.Picture_Message2);
      $("#thumnails_new_profile_view3").attr("src", response.data.Picture_Message3);

    },
    error: function (xhr, status, exception) {
      console.log(xhr);
    }
  });

}


function update_new(formData)
{
  var ID_Message = $("#button_newsManageModal").attr("data-id");
  var url_string = "index.php?controller=News&action=edit_news&ID_Message=" + ID_Message;
  if (!$("#form_newsManage").validate().form()) {
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
      enctype: 'multipart/form-data',
      success: function (res, status, xhr) {
      var data = JSON.parse(res);
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

}


function delete_news(ID_Message)
{
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
        url: "index.php?controller=News&action=delete_news&ID_Message=" + ID_Message,
        processData: false,
        contentType: false,
        success: function (res, status, xhr) {
          var data = JSON.parse(res);
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

function addImgView(f){
  if(f.length > 0){
    for(var i=0;i<f.length;i++){
      var mocFile = {
          id: i,
          name: f[i],
          path: f[i],
        };
      if(f[i]!=''){
        myDropzone.emit("addedfile",mocFile);
        myDropzone.emit("thumbnail",mocFile,f[i]);
        //myDropzone.emit("complete",mocFile);
        //filesList.push(mocFile);
      }
    }
  }
  //myDropzone.options.maxFiles = 3 - f.length;
}


$('#newsManageModal').on('shown.bs.modal', function (e) {
  ////
  //intDropzone();
  addImgView(fArr);
});

$('#newsManageModal').on('hidden.bs.modal', function () {
  //window.alert('hidden event fired!');
  if(filesList.length>0){
    for(var i=0; i<filesList.length;i++){
      myDropzone.removeFile(filesList[i]);
    }
  }
  filesList = [];
  fArr = [];
  del_files = [];
  //myDropzone.destroy();
});

function intDropzone(){
   myDropzone = new Dropzone("div#dropzoneId",{
    url: "/file/post",
    acceptedFiles: 'image/*',
    //maxFiles: 3,
    autoQueue: false,
    addRemoveLinks: true,
    dictDefaultMessage : 'วางไฟล์ที่นี่เพื่ออัพโหลด',
    dictRemoveFile : 'ลบไฟล์',
    dictInvalidFileType: 'คุณไม่สามารถอัปโหลดไฟล์ประเภทนี้ได้',
    thumbnailWidth: 120,
    thumbnailHeight: 120,
    init: function(){
      this.on("addedfile",function(file){
        this.emit("complete",file);
        filesList.push(file);
      });
      this.on("maxfilesexceeded", function(file){
        //alert("bb");
        this.removeFile(file);
      });
      this.on("removedfile", function(file) {
        //alert(JSON.stringify(file));
        del_files.push(file.name);
      });
    },
  });
}


