<?php
// Require composer autoload
require_once __DIR__ . '/vendor/autoload.php';

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];
$mpdf = new \Mpdf\Mpdf(['tempDir' => __DIR__ . '/tmp',
    'fontdata' => $fontData + [
            'sarabun' => [
                'R' => 'THSarabunNew.ttf',
                'I' => 'THSarabunNewItalic.ttf',
                'B' =>  'THSarabunNewBold.ttf',
                'BI' => "THSarabunNewBoldItalic.ttf",
            ]
        ],
]);

ob_start(); // Start get HTML code
?>


<!DOCTYPE html>
<html>
<head>
<title>PDF</title>
<link href="https://fonts.googleapis.com/css?family=Sarabun&display=swap" rel="stylesheet">
<style>
body {
    font-family: sarabun;
}

.item{
  padding: 3px;
}

.item table {
  border-collapse: collapse;
 
}

.item th {
 
  text-align: center;
  padding: 8px;
}

.item td {
 
  text-align: left;
  padding: 8px;
}

.item tr:nth-child(even) {
  
}

.header{
  text-align: center;
}

.table2 {
  border-collapse: collapse;
  width: 100%;
}

.table2 td{
  padding: 10px;
  line-height: 20px;
}


</style>
</head>
<body>
    <table width="100%" border="0">
      <tr>
        <td width="70%">
            <h1>บริษัท เอส.ซูเปอร์ เคเบิ้ล จำกัด</h1>
            99/3 หมู่ที่ 2 ตำบลหอมเกร็ด <br/>
            อำเภอสามพราน จังหวัดนครปฐม 73110 <br/>
            โทร 02-449-4770 FAX. 02-905-0594-5 <br/>
            เลขที่ประจำตัวผู้เสียภาษี 0735552001421
        </td>
         <td width="30%" style="text-align:right;">
          <h3>ใบเสนอราคา</h3>
          <h3>SALE ORDER</h3>
        </td>
      </tr>
    </table>
    <table width="100%" border="0">
      <tr>
        <td width="70%">
          <table width="100%" border="1" class="table2">
            <tr>
              <td>
                ลูกค่้า : <?php echo $data_sendback['Name_Company']; ?>
                <br/>
                ที่อยู่ : <?php echo $data_sendback['Address_Company']; ?>
                <br/>
                เบอร์โทร : <?php echo $data_sendback['Tel_Company']; ?>
              </td>
            </tr>
          </table>
        </td>
        <td width="30%" style="text-align:right;">
          <table width="100%" border="1" class="table2">
            <tr>
              <td>
                Invoice No : <?php echo $data_sendback['Invoice_No']; ?>
                <br/>
                วันที่ : <?php $date = date_create($data_sendback['Invoice_Date']) ;echo date_format($date, 'd/m/Y'); ?>
                <br/>
                เงื่อนไขการชำระเงิน <?php echo $data_sendback['Credit_Term_Company']; ?> 
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>
    <div class="item">
      <table border="1">
        <tr>
          <th width="50">ลำดับ</th>
          <th width="350">รายการสินค้า</th>
          <th width="80">จำนวน/หน่วย</th>
          <th width="80">หน่วยละ</th>
          <th width="80">ส่วนลด</th>
          <th width="90">จำนวนเงิน</th>
        </tr>
        <?php
          if(count($data_sendback['invoice_detail'])>0){
            foreach($data_sendback['invoice_detail'] as $key => $val ){
        ?>
        <tr>
          <td><center><?php echo ($key+1); ?></center></td>
          <td><?php echo $val['Name_Goods']; ?></td>
          <td style="text-align: right;"><?php echo number_format($val['Quantity_Goods']); ?></td>
          <td style="text-align:right;"><?php echo number_format($val['Price_Goods'],2); ?></td>
          <td style="text-align:right;"><?php echo number_format($val['Discount_Price'],2);?></td>
          <td style="text-align:right;"><?php echo number_format($val['Total'],2); ?></td>
        </tr>
        <?php 
            }
          }
        ?>
        <tr>
          <td colspan="5" style="text-align: right;">มูลค่าสินค้า</td>
          <td style="text-align: right;"><b><?php echo number_format($data_sendback['Total'],2); ?></b></td>
        </tr>
        <tr>
          <td colspan="5" style="text-align: right;">จำนวนเงิน VAT</td>
          <td style="text-align: right;"><b><?php echo number_format($data_sendback['Vat'],2); ?></b></td>
        </tr>

        <tr>
          <td colspan="5" style="text-align: right;">ร่วมสุทธิ</td>
          <td style="text-align: right;"><b><?php echo number_format($data_sendback['Grand_Total'],2); ?></b></td>
        </tr>
      </table>
      <br/>
      <table width="100%" border="1">
        <tr>
          <td width="33.333333333%" style="text-align: center;">
            จึงเรียนมาเพื่อโปรพิจารณา
            <br/>
            <br/>
            ผู้เสนอราคา..............................
            <br>
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
          </td>
          <td width="33.333333333%" style="text-align: center;">
            อนุมัติสั่งซื้อตามที่เสนอมา
            <br/>
            <br/>
            ผู้อนุมัติ..............................
            <br>
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
          </td>
          <td width="33.333333333%" style="text-align: center;">
            ในนาม บริษัท เอส.ซูเปอร์ เคเบิ้ล จำกัด
            <br/>
            <br/>
            ผู้มีอำนาจลงนาม..............................
            <br>
            (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
          </td>
        </tr>
      </table>
    </div>
</body>
</html>

<?php
$html = ob_get_contents();
$mpdf->WriteHTML($html);
$mpdf->Output("./download/inv.pdf");
//$mpdf->Output();
header('location:./download/inv.pdf');
//exit(0);
?>