$("#form_search").submit(function(){
   $.ajax({
        type: "POST",
        url: "index.php?controller=EmpStatistics&action=getReport",
        data: $("#form_search").serialize(),
        success: function (res, status, xhr) {
          var yearArr = [];
          var valArr = [];
          var data = res;
          var htmlTB = '<table width="100%" class="tb_center" border="1">';
          htmlTB += '<tr>';
          htmlTB += '<td>ปี</td>';
          htmlTB += '<td>ม.ค.</td>';
          htmlTB += '<td>ก.พ.</td>';
          htmlTB += '<td>มี.ค.</td>';
          htmlTB += '<td>เม.ย.</td>';
          htmlTB += '<td>พ.ค.</td>';
          htmlTB += '<td>มิ.ย.</td>';
          htmlTB += '<td>ก.ค.</td>';
          htmlTB += '<td>ส.ค.</td>';
          htmlTB += '<td>ก.ย.</td>';
          htmlTB += '<td>ต.ค.</td>';
          htmlTB += '<td>พ.ย.</td>';
          htmlTB += '<td>ธ.ค.</td>';
          htmlTB += '<td>รวม</td>';
          htmlTB += '</tr>';
          var t = [];
          for(var i=1;i<=12;i++){
            t[i] = 0;
          }
          var x = 0;
          var old = [];
          $.each(data, function() {
              $.each(this, function(a,b) {
                yearArr.push(b.year);
                htmlTB += '<tr>';
                htmlTB += '<td>'+b.year+'</td>';
                var total = 0;
                for(var i=1;i<=12;i++){
                  htmlTB += '<td>'+b.month[i]+'</td>';
                  total = total+b.month[i];
                  if(x===0){
                     old[i] = b.month[i];
                  }
                  if(x>0){
                    t[i] = old[i]-b.month[i];
                  }
                }
                console.log('x='+x);
                x++;
                htmlTB += '<td>'+total+'</td>';
                htmlTB += '</tr>';
                valArr.push(total);
              });
          });
          var total_all = 0;
          for(var i=1;i<=12;i++){
            total_all = total_all+t[i];
          }

          htmlTB += '<tr>';
          htmlTB += '<td>เปรียบเทียบ</td>';
          htmlTB += '<td>'+t[1]+'</td>';
          htmlTB += '<td>'+t[2]+'</td>';
          htmlTB += '<td>'+t[3]+'</td>';
          htmlTB += '<td>'+t[4]+'</td>';
          htmlTB += '<td>'+t[5]+'</td>';
          htmlTB += '<td>'+t[6]+'</td>';
          htmlTB += '<td>'+t[7]+'</td>';
          htmlTB += '<td>'+t[8]+'</td>';
          htmlTB += '<td>'+t[9]+'</td>';
          htmlTB += '<td>'+t[10]+'</td>';
          htmlTB += '<td>'+t[11]+'</td>';
          htmlTB += '<td>'+t[12]+'</td>';
          htmlTB += '<td>'+total_all+'</td>';
          htmlTB += '</tr>';
          htmlTB += '</table>';

          $("#tb").html(htmlTB);
          addChart(yearArr,valArr);
        }
    });
  return false;
});

function addChart(year,val){
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: year,
        datasets: [{
            label: 'ยอดขาย',
            data: val,
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(255, 206, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(255, 159, 64, 0.2)'
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 1
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
}
