<?php
# excel library
include Router::getSourcePath() . 'classes/Excel.class.php';

class ZoneController
{

    /**
     * handleRequest จะทำการตรวจสอบ action และพารามิเตอร์ที่ส่งเข้ามาจาก Router
     * แล้วทำการเรียกใช้เมธอดที่เหมาะสมเพื่อประมวลผลแล้วส่งผลลัพธ์กลับ
     *
     * @param string $action ชื่อ action ที่ผู้ใช้ต้องการทำ
     * @param array $params พารามิเตอร์ที่ใช้เพื่อในการทำ action หนึ่งๆ
     */
    public function handleRequest(string $action = "index", array $params)
    {
        switch ($action) {
            case "index":
                $this->index();
                break;
            case "manage_zone":
                $this->$action();
                break;
            case "create_zone":
                $result = $this->$action($params["POST"]);
                echo $result;
                break;
            case "edit_zone":
                $ID_Zone = isset($params["GET"]["ID_Zone"]) ? $params["GET"]["ID_Zone"] : "";
                $result = $this->$action($params["POST"], $ID_Zone);
                echo $result;
                break;
            case "delete_zone":
                $result = $this->$action($params["POST"]["ID_Zone"]);
                echo $result;
                break;
            case "findbyID_Zone":
                $ID_Zone = isset($params["POST"]["ID_Zone"]) ? $params["POST"]["ID_Zone"] : "";

                if (!empty($ID_Zone)) {
                    $result = $this->$action($ID_Zone);
                    echo $result;
                }
                break;
            case "download_zone":

                $result = $this->$action();
                echo $result;

                break;
            default:
                break;
        }
    }
    private function create_zone($params)
    {
        # สร้างโซน
        $access_zone = new Zone();
        if (count($params['ID_Employee']) > 0) {
            foreach ($params['ID_Employee'] as $val) {
                $data_arr = array(
                    'ID_Employee' => $val,
                    'AMPHUR_ID' => isset($params['AMPHUR_ID']) ? $params['AMPHUR_ID'] : NULL,
                    'PROVINCE_ID' => $params['PROVINCE_ID']
                );
                $zone_result = $access_zone->create_zone($data_arr);
            }
        }
        return json_encode($zone_result);
    }
    private function edit_zone($params, $ID_Zone)
    {
        # อัปเดตโซน
        $am = isset($params['AMPHUR_ID']) ? $params['AMPHUR_ID'] : NULL;
        if ($params['PROVINCE_ID'] != '1') {
            $am = NULL;
        }
        $data_arr = array(
            'ID_Employee' => $params['ID_Employee'][0],
            'AMPHUR_ID' => $am,
            'PROVINCE_ID' => $params['PROVINCE_ID']
        );
        $access_zone = new Zone();
        $zone_result = $access_zone->edit_zone(
            $data_arr,
            $ID_Zone
        );
        echo json_encode($zone_result);
    }

    private function delete_zone($ID_Zone)
    {
        # ลบโซน
        $access_zone = new Zone();
        $access_zone = $access_zone->delete_zone(
            $ID_Zone
        );
        return json_encode($access_zone);
    }
    private function findbyID_Zone(string $ID_Zone)
    {
        $zone = Zone::findById($ID_Zone); //echo json_encode($sales);


        $data_sendback = array(
            "ID_Zone" => $zone->getID_Zone(),
            "ID_Employee" => $zone->getID_Employee(),
            //"ID_Company" => $zone->getID_Company(),
            "AMPHUR_ID" => $zone->getAMPHUR_ID(),
            "PROVINCE_ID" => $zone->getPROVINCE_ID(),

        );
        echo json_encode(array("data" => $data_sendback));
    }
    private function error_handle(string $message)
    {
        $this->index($message);
    }

    // ควรมีสำหรับ controller ทุกตัว
    private function index($message = null)
    {
        include Router::getSourcePath() . "views/login.inc.php";
    }
    //หน้าจัดการโซน
    private function manage_zone($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];
        # retrieve data
        $zoneList = Zone::findAll();
        $employeeList = Employee::findAll();
        $companyList = Company::findAll();
        $amphurList = Amphur::findAll();
        $provinceList = Province::findAll();
        include Router::getSourcePath() . "views/admin/manage_zone.inc.php";
    }


    // download zone  พนักงาน
    private function download_zone($params = null)
    {

        $zonelist_data = Zone::findzone_groupbyamphur();




        try {
            // เรียนกใช้ PHPExcel
            $objPHPExcel = new PHPExcel();
            // กำหนดค่าต่างๆ ของเอกสาร excel
            $objPHPExcel->getProperties()->setCreator("bp.com")
                ->setLastModifiedBy("bp.com")
                ->setTitle("PHPExcel Test Document")
                ->setSubject("PHPExcel Test Document")
                ->setDescription("Test document for PHPExcel, generated using PHP classes.")
                ->setKeywords("office PHPExcel php")
                ->setCategory("Test result file");

            // กำหนดชื่อให้กับ worksheet ที่ใช้งาน
            $objPHPExcel->getActiveSheet()->setTitle('รายชื่อSaleที่ดูแล');

            // กำหนด worksheet ที่ต้องการให้เปิดมาแล้วแสดง ค่าจะเริ่มจาก 0 , 1 , 2 , ......
            $objPHPExcel->setActiveSheetIndex(0);

            // การจัดรูปแบบของ cell
            $objPHPExcel->getDefaultStyle()
                ->getAlignment()
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
            //HORIZONTAL_CENTER //VERTICAL_CENTER

            // จัดความกว้างของคอลัมน์
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);

            // กำหนดหัวข้อให้กับแถวแรก
            //   $objPHPExcel->setActiveSheetIndex(0)
            //       ->setCellValue('A1', 'ไอดีพนักงาน')
            //       ->setCellValue('B1', 'ชื่อ')
            //       ->setCellValue('C1', 'นามสกุล')
            //       ->setCellValue('D1', 'ชื่อผู้ใช้')
            //       ->setCellValue('E1', 'อีเมล์')
            //       ->setCellValue('F1', 'สถานะ');




            $activesheet = $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . 1, "รายชื่อSaleที่ดูแลในแต่ละจังหวัด");
            $activesheet->getStyle("A" . 1)->applyFromArray(
                array(
                    'font'  => array(
                        'bold'  => true,
                        'color' => array('rgb' => 'FF0000'),
                        'size'  => 12,
                        'name'  => 'Calibri'
                    )
                )
            );


            if (!empty($zonelist_data)) {
                $i = 0;

                $start_row = 2;
                foreach ($zonelist_data as $key => $value) {

                    $no = 1;
                    # case : loop amphur
                    if (isset($value['data'])) {
                        $activesheet = $objPHPExcel->setActiveSheetIndex(0)
                            ->setCellValue('A' . $start_row, $value['province_name']);
                        $activesheet->getStyle("A" . $start_row)->applyFromArray(
                            array(
                                'fill' => array(
                                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                    'color' => array('rgb' => 'FFFF00')
                                ),
                                'borders' => array(
                                    'outline' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN
                                    )
                                )
                            )
                        );

                        ++$start_row;

                        foreach ($value['data'] as $amphur_key => $amphur_value) {
                            # loop employee

                            $column = 1;
                            if (!empty($amphur_value['amphur_name'])) {
                                $activesheet = $objPHPExcel->setActiveSheetIndex(0)
                                    ->setCellValue('A' . $start_row, $no . '.' . $amphur_value['amphur_name']);

                                ++$no;
                            }
                            foreach ($amphur_value['data'] as $employee_key => $employee_value) {


                                $activesheet->setCellValueByColumnAndRow($column, $start_row, $employee_value->getName_Employee().' '.$employee_value->getSurname_Employee());
                                $colIndexvalue = PHPExcel_Cell::stringFromColumnIndex($column); # case : get column string name
                                $activesheet->getStyle($colIndexvalue . $start_row)->getNumberFormat()->setFormatCode('0.000');



                                ++$column;
                            } # end : foreach loop employee
                            ++$start_row;
                        } # end : foreach loop amphur
                    }
                }
                // กำหนดรูปแบบของไฟล์ที่ต้องการเขียนว่าเป็นไฟล์ excel แบบไหน ในที่นี้เป้นนามสกุล xlsx  ใช้คำว่า Excel2007
                // แต่หากต้องการกำหนดเป็นไฟล์ xls ใช้กับโปรแกรม excel รุ่นเก่าๆ ได้ ให้กำหนดเป็น  Excel5
                ob_start();
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  // Excel2007 (xlsx) หรือ Excel5 (xls)

                $filename = 'Zonelist-' . date("dmYHi") . '.xlsx'; //  กำหนดชือ่ไฟล์ นามสกุล xls หรือ xlsx
                // บังคับให้ทำการดาวน์ดหลดไฟล์
                header('Content-Type: application/vnd.ms-excel'); //mime type
                header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
                header('Cache-Control: max-age=0'); //no cache
                ob_end_clean();
                $objWriter->save('php://output'); // ดาวน์โหลดไฟล์รายงาน
                exit;

                //die($objWriter);

            } else {
                // status that return to frontend
                $status = false;
                // error message handle
                $message = "ไม่พบข้อมูล";
            }
        } catch (Exception $e) {
            // status that return to frontend
            $status = false;
            // error message handle
            $message = $e->getMessage();
        }


        return json_encode(array('status' => true));
    }
}
