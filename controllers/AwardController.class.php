<?php

class AwardController
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
            case "manage_award" :
                $this->$action();
                break;
            case "create_award":
                $ID_Employee = isset($params["GET"]["ID_Employee"]) ? $params["GET"]["ID_Employee"] : "";
                $FILE_IMG = isset($params["FILES"]["award_pic"]) ? $params["FILES"]["award_pic"] : "";
                $result = $this->$action($params["POST"], $FILE_IMG,$ID_Employee);
                echo $result;
                break;
            case "findAwardbyID_Award" :
                $ID_Award = isset($params["POST"]["ID_Award"]) ? $params["POST"]["ID_Award"] : "";
                if (!empty($ID_Award)) {
                    $result = $this->$action($ID_Award);
                    echo $result;
                }
                break;
            case "edit_award" :
                $FILE_IMG = isset($params["FILES"]) ? $params["FILES"] : "";
                $Params= isset($params["POST"]) ? $params["POST"] : "";
                $ID_Award = isset($params["GET"]["ID_Award"]) ? $params["GET"]["ID_Award"] : "";
                $FILE_IMG = isset($params["FILES"]["award_pic"]) ? $params["FILES"]["award_pic"] : "";
                $result = $this->$action($params["POST"] ,$FILE_IMG, $ID_Award);
                echo $result;
                break;
            case "delete_award":
                $params = isset($params["GET"]["ID_Award"]) ? $params["GET"]["ID_Award"] : "";
                $result = $this->$action($params);
                // print_r($params);
                echo $result;
                break;
            case "show_award_status":
                session_start();
                $employee = $_SESSION['employee'];
                $emp_id = $employee->getID_Employee();
                $show_row = 4;
                if ($employee->getUser_Status_Employee() == "Admin") {
                    include Router::getSourcePath() . "views/index_admin.inc.php";
                } else if ($employee->getUser_Status_Employee() == "Sales") {
                    # retrieve data
                    $countAll = Award::fetchCountAll($employee->getID_Employee());
                    $countRowAll = Award::fetchCountRowAll($employee->getID_Employee());
                    
                    $n = $countRowAll[0];
                    $count_page = ceil($n/$show_row);
                    $start = 0;
                    $get_page = 1;
                    if(isset($_GET['page'])){
                        $get_page = $_GET['page'];
                    }
                    $start = ($get_page*$show_row)-$show_row;
                    $award = Award::fetchAllwithInnerLimit($employee->getID_Employee(),$start,$show_row);
                    //print_r($award);
                    //echo $n.':'.$count_page;
                    //exit;
                    include Router::getSourcePath() . "views/sales/index_award.inc.php";
                } else if ($employee->getUser_Status_Employee() == "User") {
                    # retrieve data
                    $countAll = Award::fetchCountAll($employee->getID_Employee());
                    $countRowAll = Award::fetchCountRowAll($employee->getID_Employee());
                    $n = $countRowAll[0];
                    $count_page = ceil($n/$show_row);
                    $start = 0;
                    $get_page = 1;
                    if(isset($_GET['page'])){
                        $get_page = $_GET['page'];
                    }
                    $start = ($get_page*$show_row)-$show_row;
                    $award = Award::fetchAllwithInnerLimit($employee->getID_Employee(),$start,$show_row);
                    //echo $n.':'.$count_page;
                    //exit;
                    include Router::getSourcePath() . "views/sales/index_award.inc.php";
                }
                break;
            case "update_status_award":
                session_start();
                $employee = $_SESSION['employee'];
                $ID_Award = isset($params["GET"]["ID_Award"]) ? $params["GET"]["ID_Award"] : "";
                if ($employee->getUser_Status_Employee() == "Admin") {
                    include Router::getSourcePath() . "views/index_admin.inc.php";
                } else if ($employee->getUser_Status_Employee() == "Sales") {
                    # retrieve data
                    $award = Award::update_award_status2($employee->getID_Employee(), $ID_Award);
                    include Router::getSourcePath() . "views/sales/redirect_index_award.inc.php";
                } else if ($employee->getUser_Status_Employee() == "User") {
                    # retrieve data
                    $award = Award::update_award_status2($employee->getID_Employee(), $ID_Award);
                    include Router::getSourcePath() . "views/sales/redirect_index_award.inc.php";

                }
                break;
            case "show" :
                session_start();
                $employee = $_SESSION['employee'];
                Award::update_award_status2($employee->getID_Employee(), $_GET['id']);
                $this->show();
                break;
            default:
                break;
        }
    }
    private function create_award($params, $FILE_IMG)
    {
        $access_award = new Award();

        // # สร้างรางวัล
        $awardid = $access_award->geneateDateTimemd() ;
        $award_title =  $params["Tittle_Award"] ;
     
        $award_datetime = $access_award->geneateDateTime();

        $award_ID_Employee = $params["ID_Employee"];
        if(isset($FILE_IMG)){
            for($i=0;$i<=count($FILE_IMG['name']);$i++){
                if(!empty($FILE_IMG) && !empty($FILE_IMG['name'][$i])){
                    $name_file =  $FILE_IMG['name'][$i];
                    $ex =  explode('.',$name_file) ;
                    $name_file_type = end($ex);
                    $tmp_name =  $FILE_IMG['tmp_name'][$i];
                    $message_filename = md5(date('YmdHis').rand(1,999999)).".".$name_file_type;
                    $locate_img = Router::getSourcePath() . "images/" . $message_filename;
                    move_uploaded_file($tmp_name, $locate_img);
                    $award_image = new Award_Image();
                    $award_image->create_images([
                        'ID_Award ' => $awardid,
                        'Image_name' => $message_filename
                    ]);
                }
            }
        }
        $access_award_params = array(
            "ID_Award" => $awardid,
            "Tittle_Award" => $award_title,
            "Date_Award"=> $award_datetime,
            "ID_Employee" => !empty($award_ID_Employee) ? $award_ID_Employee : null ,
        );

        $result = $access_award->create_award(
            $access_award_params
        );
        
        return json_encode($result);
    }



    private function findAwardbyID_Award($findbyID_Award)
    {
        $award = Award::findAward_byID($findbyID_Award);//echo json_encode($employee);
        $img =  Award_Image::get_images($findbyID_Award);
        // echo json_encode(array("data" => $data_sendback));
        $img_arr = [];
        if(count($img) > 0 ){
            foreach($img as $val){
                $img_arr[] = Router::getSourcePath() . "images/".$val->getImage_name();
            }
        }
        // echo json_encode(array("data" => $data_sendback));

        $data_sendback = array(
            "ID_Award" => $award->getID_Award(),
            "Tittle_Award" => $award->getTittle_Award(),
            "Date_Award" => $award->getDate_Award(),
            "ID_Employee" => $award->getID_Employee(),
            "img" => $img_arr
        );

        echo json_encode(array("data" => $data_sendback));
    }


    private function edit_award($params, $FILE_IMG, $ID_Award)
    {
        $awardOld = Award::findAward_byID($ID_Award);
        // # อัปเดตรางวัล
        $access_award = new Award();
        $awardid = $ID_Award ;
        $award_title =  $params["Tittle_Award"] ;
        $award_datetime = $access_award->geneateDateTime();

        if(isset($FILE_IMG) && isset($FILE_IMG['name']) && count($FILE_IMG['name']) > 0){
            for($i=0;$i<=count($FILE_IMG['name']);$i++){
                if(!empty($FILE_IMG) && !empty($FILE_IMG['name'][$i])){
                    $name_file =  $FILE_IMG['name'][$i];
                    $ex =  explode('.',$name_file) ;
                    $name_file_type = end($ex);
                    $tmp_name =  $FILE_IMG['tmp_name'][$i];
                    $message_filename = md5(date('YmdHis').rand(1,999999)).".".$name_file_type;
                    $locate_img = Router::getSourcePath() . "images/" . $message_filename;
                    move_uploaded_file($tmp_name, $locate_img);
                    $award_image = new Award_Image();
                    $award_image->create_images([
                        'ID_Award ' => $awardid,
                        'Image_name' => $message_filename
                    ]);
                }
            }
        }
       
        $access_award_params = array(
            "ID_Award" => $awardid,
            "Tittle_Award" => $award_title,
            "Date_Award"=> $award_datetime,
        );

        //print_r($access_award_params);
        
        $result = $access_award->update_award(
            $access_award_params
        );

        if(isset($_POST['del_files']) && count($_POST['del_files']) > 0){
            foreach($_POST['del_files'] as $val){
                $this->delete_img($val);
            }
        }
        
        return json_encode($result);

    }

    private function delete_award($params) {
        $access_award = new Award();
        $result = $access_award->delete_award(
            $params
        );
        // print_r($result);
        return json_encode($result);
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
    //หน้าจัดการรางวัล
    private function manage_award()
    {
        session_start();
        $employee = $_SESSION["employee"];

        # retrieve data

        $employeeList = Employee::findAll();
        $awardList = Award::fetchAll();


        include Router::getSourcePath() . "views/admin/manage_award.inc.php";
    }
    private function show($params = null)
    {

        $employee = $_SESSION["employee"];
        $award = Award::findAward_byID($_GET['id']);
        $img99 =  Award_Image::get_images($_GET['id']);
        //print_r($img99);
        //exit;
        if ($employee->getUser_Status_Employee() == "Admin") {
            include Router::getSourcePath() . "views/index_admin.inc.php";
        } else if ($employee->getUser_Status_Employee() == "Sales") {
            include Router::getSourcePath() . "views/sales/award.inc.php";
        } else if ($employee->getUser_Status_Employee() == "User") {
            include Router::getSourcePath() . "views/sales/award.inc.php";
        }
    }

     private function delete_img($filename) {
        $get_file_name = explode('/',$filename);
        $file_name = end($get_file_name);
        $result = Award_Image::delete_images($file_name);
        return json_encode($result);
    }

}