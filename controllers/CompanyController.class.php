<?php

# excel library
include Router::getSourcePath() . 'classes/Excel.class.php';


class CompanyController
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
            case "manage_company" :
                $this->$action();
                break;
            case "create_company" :
                $result = $this->$action($params["POST"]);
                echo $result;
                break;
            case "edit_company" :
                $ID_Company = isset($params["GET"]["ID_Company"]) ? $params["GET"]["ID_Company"] : "";
                $result = $this->$action($params["POST"], $ID_Company);
                echo $result;
                break;
            case "delete_company":
                $result = $this->$action($params["POST"]["ID_Company"]);
                echo $result;
                break;
            case "import_excel_company":
                $FILES = isset($params["FILES"]["file"]) ? $params["FILES"]["file"] : "";
                $FILE_IMG = isset($params["FILES"]["examfile"]) ? $params["FILES"]["examfile"] : "";
                $result = $this->$action($params["POST"], $FILES, $FILE_IMG);
                echo $result;
                break;
            case "findbyID_Company":
                $ID_Company = isset($params["POST"]["ID_Company"]) ? $params["POST"]["ID_Company"] : "";
                //print_r($ID_Company);exit();
                if (!empty($ID_Company)) {
                    $result = $this->$action($ID_Company);
                    echo $result;
                }
                break;
            case "getAmphur":
                $PROVINCE_ID = isset($params["POST"]["PROVINCE_ID"]) ? $params["POST"]["PROVINCE_ID"] : "";

                if (!empty($PROVINCE_ID)) {
                    $result = $this->$action($PROVINCE_ID);
                    echo $result;
                }
                break;
            case "export_excel_test_company":
                $result = $this->$action($params["POST"]);
                echo $result;
                break;
            default:
                break;
        }
    }
    private function import_excel_company(array $params, array $FILES, array $FILE_IMG)
    {
        $excel = new Excel();
        #UPLOAD IMAGE
        if (!empty($FILE_IMG) && !empty($FILE_IMG['name'])) {
            # update new pic
            $target_file_img = Router::getSourcePath() . "images/" . $FILE_IMG['name'];

            if (!empty($FILE_IMG) && isset($FILE_IMG['name'])) {
                if (!empty($FILE_IMG['name'])) {
                    move_uploaded_file($FILE_IMG["tmp_name"], $target_file_img);

                    $company_ = new Company();
                    $company_->file_log($FILE_IMG['name'], 2);
                }
            }

        }
        #UPLOAD EXCEL

        if (!empty($FILES) && !empty($FILES['name'])) {
            $path = $FILES["tmp_name"];
            $object = PHPExcel_IOFactory::load($path);
            $params = array();

            //case: การอัพโหลดไฟล์ excel ถ้าลืมใส่ column ไหนให้บอกผิด row ไหน
            $EXCEL_HeaderCol = array("ID_Company" => array("name" => "ไอดีบริษัท", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ไอดีบริษัท")
            , "Name_Company" => array("name" => "ชื่อบริษัท", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ชื่อบริษัท")
            , "Address_Company" => array("name" => "ที่อยู่บริษัท", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ที่อยู่บริษัท")
            , "PROVINCE_ID" => array("name" => "ไอดีจังหวัด", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ไอดีจังหวัด")
            , "AMPHUR_ID" => array("name" => "ไอดีอำเภอ", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ไอดีอำเภอ")
            , "Tel_Company" => array("name" => "เบอร์บริษัท", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ เบอร์บริษัท")
            , "Email_Company" => array("name" => "อีเมล์บริษัท", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ อีเมล์บริษัท")
            , "Tax_Number_Company" => array("name" => "เลขผู้เสียภาษี", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ เลขผู้เสียภาษี")
            , "Credit_Limit_Company" => array("name" => "วงเงินสูงสุด", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ วงเงินสูงสุด")
            , "Credit_Term_Company" => array("name" => "เครดิตเทอม", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ เครดิตเทอม")
            , "Cluster_Shop_ID" => array("name" => "คลัสเตอร์", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ไอดีกลุ่มลูกค้า")
            , "Contact_Name_Company" => array("name" => "ชื่อที่ติดต่อ", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ ชื่อที่ติดต่อ")
            , "IS_Blacklist" => array("name" => "บัญชีดำ", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ บัญชีดำ")
            , "Cause_Blacklist" => array("name" => "สาเหตุที่ติดบัญชีดำ", "status" => false, "error" => "ไม่พบข้อมูลคอลัมน์ สาเหตุที่ติดบัญชีดำ")
            );
            $count = 0;
            foreach ($object->getWorksheetIterator() as $worksheet) {
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                //  echo $highestRow;exit();
                // row = 2 คือ row แรก ไม่รวม header
                #เช็คหัวตารางชื่อตรงกันไหมใน array ที่ hardcode ไว้
                if ($count != 1) {
                    for ($col_ = 0; $col_ < 14; $col_++) {
                        $col__cc = strval($worksheet->getCellByColumnAndRow($col_, 1)->getValue());
                        if ($col__cc == '') {
                            $c = array_values($EXCEL_HeaderCol);
                            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูลไม่พบคอลัมน์  " . $c[$col_]['name'];
                            return json_encode(array("status" => false, "message" => $message));

                        } else {
                            $ccc = array_key_exists($col__cc, $EXCEL_HeaderCol);
                            if (!$ccc) {
                                $c = array_values($EXCEL_HeaderCol);
                                $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูลไม่พบคอลัมน์  " . $c[$col_]['name'];
                                return json_encode(array("status" => false, "message" => $message));
                            }
                        }
                    }
                    ++$count;
                }

                #eof

                for ($row = 2; $row <= $highestRow; $row++) {
                    if ($worksheet->getCellByColumnAndRow(0, $row)->getValue() != '') {
                        $getCellArray = $this->checkemptycell_company($worksheet, $row);
                        if ($getCellArray['status'] == false) {
                            $c = array_values($EXCEL_HeaderCol);
                            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูลไม่พบข้อมูลในแถวที่{$row}(รวมหัวตาราง) ในคอลัมน์คือ " . $c[$getCellArray["column"]]['name'] . '';
                            return json_encode(array("status" => false, "message" => $message));
                        }


                        $push_array = array("ID_Company" => $getCellArray["data"][0],
                            "Name_Company" => $getCellArray["data"][1],
                            "Address_Company" => $getCellArray["data"][2],
                            "PROVINCE_ID" => $getCellArray["data"][3],
                            "AMPHUR_ID" => $getCellArray["data"][4],
                            "Tel_Company" => $getCellArray["data"][5],
                            "Email_Company" => $getCellArray["data"][6],
                            "Tax_Number_Company" => $getCellArray["data"][7],
                            "Credit_Limit_Company" => $getCellArray["data"][8],
                            "Credit_Term_Company" => $getCellArray["data"][9],
                            "Cluster_Shop_ID" => $getCellArray["data"][10],
                            "Contact_Name_Company" => $getCellArray["data"][11],
                            "IS_Blacklist" => $getCellArray["data"][12],
                            "Cause_Blacklist" => $getCellArray["data"][13]
                        );
                        array_push($params, $push_array);

                    } else {


                    }
                }
            }
            // # create user ใหม่
            $company_ = new Company();
            //$result = [];
            $result = $company_->create_company_at_once($params);
            # update new pic
            $target_file = Router::getSourcePath() . "uploads/" . $FILES['name'];
            if (!empty($FILES) && isset($FILES['name'])) {
                if (!empty($FILES['name'])) {
                    move_uploaded_file($FILES["tmp_name"], $target_file);
                }
            }
            return json_encode($result);
        }

        #
        return json_encode(array("status" => true));
    }

    private function checkemptycell_company($worksheet, $row)
    {
        $push_array = array();
        for ($i = 0; $i < 14; $i++) {
            $columnValue = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
            if(trim($columnValue)=='' and $i!='2' and $i!='4' and $i!='11' and $i!='13'){
                return array("status" => false, "column" => $i, "row" => $row);
            } else {
                $push_array[$i] = $worksheet->getCellByColumnAndRow($i, $row)->getValue();
            }
        }

        return array("status" => true, "data" => $push_array);

    }

    private function getAmphur($PROVINCE_ID){
        $access_company = new Company();
        $amphur_result = $access_company->getAmphur(
            $PROVINCE_ID
        );

        echo json_encode($amphur_result);

    }

    private function create_company($params)
    {
        # สร้างบริษัทลูกค้า
        $access_company = new Company();
        $company_result = $access_company->create_company(
            $params
        );
        return json_encode($company_result);
    }

    private function edit_company($params, $ID_Company)
    {
        # อัปเดตบริษัทลูกค้า
        $access_company = new Company();
        $company_result = $access_company->edit_company(
            $params, $ID_Company
        );
        echo json_encode($company_result);

    }

    private function delete_company($ID_Company)
    {
        # ลบบริษัทลูกค้า
        $access_company = new Company();
        $company_result = $access_company->delete_company(
            $ID_Company
        );
        return json_encode($company_result);
    }

    private function findbyID_Company(string $ID_Company)
    {
        $company = Company::findById($ID_Company);//echo json_encode($employee);

        $data_sendback = array(
            "ID_Company" => $company->getID_Company(),
            "Name_Company" => $company->getName_Company(),
            "Address_Company" => $company->getAddress_Company(),
            "PROVINCE_ID" => $company->getPROVINCE_ID(),
            "AMPHUR_ID" => $company->getAMPHUR_ID(),
            "Tel_Company" => $company->getTel_Company(),
            "Email_Company" => $company->getEmail_Company(),
            "Tax_Number_Company" => $company->getTax_Number_Company(),
            "Credit_Limit_Company" => $company->getCredit_Limit_Company(),
            "Credit_Term_Company" => $company->getCredit_Term_Company(),
            "Cluster_Shop_ID" => $company->getCluster_Shop_ID(),
            "Contact_Name_Company" => $company->getContact_Name_Company(),
            "IS_Blacklist" => $company->getIS_Blacklist(),
            "Cause_Blacklist" => $company->getCause_Blacklist(),
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
    //หน้าจัดการบริษัทลูกค้า
    private function manage_company($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];
        # retrieve data
        $company = Company::findAll();
        $file_log = Filelog::findByPage('manage_company');
        $provinceList = Province::findAll();
        $amphurList = Amphur::findAll();
        $cluster_shopList = Cluster_Shop::findAll();
        include Router::getSourcePath() . "views/admin/manage_company.inc.php";
    }
    private function getEmp($province,$amphur)
    {
        $sql = "SELECT employee.* FROM zone LEFT JOIN employee ON employee.ID_Employee = zone.ID_Employee WHERE zone.PROVINCE_ID='".$province."'";
        if($amphur!='' and $amphur!='-'){
            $sql .= ' AND zone.AMPHUR_ID="'.$amphur.'"';
        }

        $con = Db::getInstance();
        $stmt = $con->prepare($sql);
        //$stmt->setFetchMode(PDO::FETCH_COLUMN);
        $stmt->execute();
        $dataList = array();
        $txt = '';
        $i = 0;
        while ($prod = $stmt->fetch()) {
            if($i>'0'){
                $txt .= ',';
            }
            $txt .= $prod['Name_Employee'].' '.$prod['Surname_Employee'];
            $i++;
        }
        return $txt;
    }
    //หน้า export ไฟล์ตัวอย่าง excel บริษัทลูกค้า
    private function export_excel_test_company($params = null)
    {
        $exportExcel = new Company();
        $exportExcelCompany = Company::findAll();

        try {
            // ob_end_clean();

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
            $objPHPExcel->getActiveSheet()->setTitle('Report');

            // กำหนด worksheet ที่ต้องการให้เปิดมาแล้วแสดง ค่าจะเริ่มจาก 0 , 1 , 2 , ......
            $objPHPExcel->setActiveSheetIndex(0);

            // การจัดรูปแบบของ cell
            $objPHPExcel->getDefaultStyle()
                ->getAlignment()
                ->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP)
                ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            //HORIZONTAL_CENTER //VERTICAL_CENTER

            // จัดความกว้างของคอลัมน์
            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(20);


            // กำหนดหัวข้อให้กับแถวแรก
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'ID_Company')
                ->setCellValue('B1', 'Name_Company')
                ->setCellValue('C1', 'Address_Company')
                ->setCellValue('D1', 'PROVINCE_ID')
                ->setCellValue('E1', 'AMPHUR_ID')
                ->setCellValue('F1', 'Tel_Company')
                ->setCellValue('G1', 'Email_Company')
                ->setCellValue('H1', 'Tax_Number_Company')
                ->setCellValue('I1', 'Credit_Limit_Company')
                ->setCellValue('J1', 'Credit_Term_Company')
                ->setCellValue('K1', 'Cluster_Shop_ID')
                ->setCellValue('L1', 'Contact_Name_Company')
                ->setCellValue('M1', 'IS_Blacklist')
                ->setCellValue('N1', 'Cause_Blacklist');;


            $start_row = 2;
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A' . $start_row, "111")
                ->setCellValue('B' . $start_row, "FIRSTSTEP")
                ->setCellValue('C' . $start_row, " ")
                ->setCellValue('D' . $start_row, "1")
                ->setCellValue('E' . $start_row, " ")
                ->setCellValue('F' . $start_row, "1234567890")
                ->setCellValue('G' . $start_row, "test_@hotmail.com")
                ->setCellValue('H' . $start_row, "1234567891234")
                ->setCellValue('I' . $start_row, "50000")
                ->setCellValue('J' . $start_row, "30 วัน")
                ->setCellValue('K' . $start_row, "1")
                ->setCellValue('L' . $start_row, " ")
                ->setCellValue('M' . $start_row, "ไม่ใช่")
                ->setCellValue('N' . $start_row, " ");

            $i = 0;

            $filename = 'Company-' . date("dmYHis") . '.xlsx'; //  กำหนดชือ่ไฟล์ นามสกุล xls หรือ xlsx

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');  // Excel2007 (xlsx) หรือ Excel5 (xls)
            ob_clean();

            $objWriter->save('./uploads/' . $filename);

            //download
            header('Content-Type: application/octet-stream');
            header("Content-Transfer-Encoding: Binary");
            header("Content-disposition: attachment; filename=\"" . $filename . "\"");
            echo file_get_contents("./uploads/" . $filename);
            die;


            return json_encode(array('status' => true, "filename" => $filename));
            //ob_end_clean();

            // die($objWriter);
            //die;


        } catch (Exception $e) {
            // status that return to frontend
            $status = false;
            // error message handle
            $message = $e->getMessage();
        }


        return json_encode(array('status' => true));
    }

}