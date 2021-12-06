$("#form_search").submit(function(){
   $.ajax({
        type: "POST",
        url: "index.php?controller=salesstatistics&action=getReport2",
        data: $("#form_search").serialize(),
        success: function (res, status, xhr) {
          var monthArr = [];
          monthArr[0] = 'ม.ค.';
          monthArr[1] = 'ก.พ.';
          monthArr[2] = 'มี.ค.';
          monthArr[3] = 'เม.ย.';
          monthArr[4] = 'พ.ค.';
          monthArr[5] = 'มิ.ย.';
          monthArr[6] = 'ก.ค.';
          monthArr[7] = 'ส.ค.';
          monthArr[8] = 'ก.ย.';
          monthArr[9] = 'ต.ค.';
          monthArr[10] = 'พ.ย.';
          monthArr[11] = 'ธ.ค.';
          monthVal = [];
          var yearArr = [];
          var valArr = [];
          var data = res;
          //alert('data:'+JSON.stringify(data));
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
          var dataIndex = -1;
          var dataYear = [];
          var dataMonth = new Array(3);
          $.each(data, function() {
              //dataMonth[dataIndex] = new Array(12);
              $.each(this, function(a,b) {
                dataIndex++;
                dataMonth[dataIndex] = new Array(12);
                yearArr.push(b.year);
                dataYear.push(b.year);
                htmlTB += '<tr>';
                htmlTB += '<td>'+b.year+'</td>';
                var total = 0;
                for(var i=1;i<=12;i++){
                  htmlTB += '<td>'+b.month[i]+'</td>';
                  monthVal[i-1] = b.month[i];
                  dataMonth[dataIndex][i-1] = b.month[i];
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
            //dataIndex++;
          });
          var total_all = 0;
          for(var i=1;i<=12;i++){
            total_all = total_all+t[i];
          }
          /*
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
          */
          $("#tb").html(htmlTB);
          addChart(monthArr,monthVal,dataYear,dataMonth);
        }
    });
  return false;
});

function addChart(year,val,dataYear,dataMonth){
  console.log('dataMonth[0]:',dataMonth[0]);
  console.log('dataMonth[1]:',dataMonth[1]);
  console.log('dataMonth[2]:',dataMonth[2]);
  var ctx = document.getElementById('myChart').getContext('2d');
  var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: year,
        datasets: [{
            label: dataYear[0],
            data: dataMonth[0],
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 99, 132, 0.2)',
            ],
            borderColor: [
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 1
        },
        {
            label: dataYear[1],
            data: dataMonth[1],
            backgroundColor: [
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
            ],
            borderColor: [
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
                'rgba(54, 39, 245, 0.8)',
            ],
            borderWidth: 1
        },
        {
            label: dataYear[2],
            data: dataMonth[2],
            backgroundColor: [
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
            ],
            borderColor: [
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
                'rgba(230, 245, 39, 0.8)',
            ],
            borderWidth: 1
        }
        ]
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
