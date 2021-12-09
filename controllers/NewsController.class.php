<?php

class NewsController
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
            case "manage_news" :
                $this->$action();
                break;
            case "create_news" :
                $ID_Employee = isset($params["GET"]["ID_Employee"]) ? $params["GET"]["ID_Employee"] : "";
                $FILE_IMG = isset($params["FILES"]["profile_news"]) ? $params["FILES"]["profile_news"] : "";
                $result = $this->$action($params["POST"], $FILE_IMG, $ID_Employee);
                echo $result;
                break;
            case "findbyID_Message":
                $ID_Message = isset($params["POST"]["ID_Message"]) ? $params["POST"]["ID_Message"] : "";
                if (!empty($ID_Message)) {
                    $result = $this->$action($ID_Message);
                    echo $result;
                }
                break;
            case "edit_news":
                $FILE_IMG = isset($params["FILES"]) ? $params["FILES"] : "";
                $Params= isset($params["POST"]) ? $params["POST"] : "";
                $ID_Message = isset($params["GET"]["ID_Message"]) ? $params["GET"]["ID_Message"] : "";
                $FILE_IMG = isset($params["FILES"]["profile_news"]) ? $params["FILES"]["profile_news"] : "";
                $result = $this->$action($params["POST"] ,$FILE_IMG, $ID_Message);
                echo $result;
                break;
            case "delete_news":
                $params = isset($params["GET"]["ID_Message"]) ? $params["GET"]["ID_Message"] : "";
                $result = $this->$action($params);
                // print_r($params);
                echo $result;
                break;
            case "delete_img":
                $params = $_GET["file_name"];
                $result = $this->$action($params);
                // print_r($params);
                echo $result;
                break;
            case "show_news_status":
                session_start();
                $employee = $_SESSION['employee'];
                $show_row = 4;
                if ($employee->getUser_Status_Employee() == "Admin") {
                    include Router::getSourcePath() . "views/index_admin.inc.php";
                } else if ($employee->getUser_Status_Employee() == "Sales") {
                    # retrieve data
                    $countAll = Message::fetchCountAll($employee->getID_Employee());
                    $countRowAll = Message::fetchCountRowAll($employee->getID_Employee());
                    $n = $countRowAll[0];
                    $count_page = ceil($n/$show_row);
                    $start = 0;
                    $get_page = 1;
                    if(isset($_GET['page'])){
                        $get_page = $_GET['page'];
                    }
                    $start = ($get_page*$show_row)-$show_row;
                    $message = Message::fetchAllwithInnerLimit($employee->getID_Employee(),$start,$show_row);
                    //echo $n.':'.$count_page;
                    //exit;
                    $emp_id = $employee->getID_Employee();
                    include Router::getSourcePath() . "views/sales/index_news.inc.php";
                } else if ($employee->getUser_Status_Employee() == "User") {
                    # retrieve data
                    $countAll = Message::fetchCountAll($employee->getID_Employee());
                    $countRowAll = Message::fetchCountRowAll($employee->getID_Employee());
                    $n = $countRowAll[0];
                    $count_page = ceil($n/$show_row);
                    $start = 0;
                    $get_page = 1;
                    if(isset($_GET['page'])){
                        $get_page = $_GET['page'];
                    }
                    $start = ($get_page*$show_row)-$show_row;
                    $message = Message::fetchAllwithInnerLimit($employee->getID_Employee(),$start,$show_row);
                    //echo $n.':'.$count_page;
                    //exit;
                    $emp_id = $employee->getID_Employee();
                    include Router::getSourcePath() . "views/sales/index_news.inc.php";
                }
                break;
            case "update_status_news":
                session_start();
                $employee = $_SESSION['employee'];
                $ID_Message = isset($params["GET"]["ID_Message"]) ? $params["GET"]["ID_Message"] : "";
                if ($employee->getUser_Status_Employee() == "Admin") {
                    include Router::getSourcePath() . "views/index_admin.inc.php";
                } else if ($employee->getUser_Status_Employee() == "Sales") {
                    # retrieve data
                    $message = Message::update_news_status($employee->getID_Employee(), $ID_Message);
                    include Router::getSourcePath() . "views/sales/redirect_index_news.inc.php";
                } else if ($employee->getUser_Status_Employee() == "User") {
                    # retrieve data
                    $message = Message::update_news_status($employee->getID_Employee(), $ID_Message);
                    include Router::getSourcePath() . "views/sales/redirect_index_news.inc.php";
                }
                break;
             case "show" :
                session_start();
                $employee = $_SESSION['employee'];
                Message::update_message_status($employee->getID_Employee(), $_GET['id']);
                $this->show();
                break;
            default:
                break;
        }
    }
    private static function create_news($params, $FILE_IMG, $emp_id)
    {
        // # สร้างข่าวสารร
        $access_news = new Message();
        $messageid = $access_news->geneateDateTimemd() ;
        $message_title =  $params["Tittle_Message"] ;
        $message_text  =  isset($params["Text_Message"]) ?  $params["Text_Message"] : "";

        $message_datetime = $access_news->geneateDateTime();
        for($i=0;$i<=count($FILE_IMG['name']);$i++){
            if(!empty($FILE_IMG) && !empty($FILE_IMG['name'][$i])){
                $name_file =  $FILE_IMG['name'][$i];
                $ex =  explode('.',$name_file) ;
                $name_file_type = end($ex);
                $tmp_name =  $FILE_IMG['tmp_name'][$i];
                $message_filename = md5(date('YmdHis').rand(1,999999)).".".$name_file_type;
                $locate_img = Router::getSourcePath() . "images/" . $message_filename;
                move_uploaded_file($tmp_name, $locate_img);
                $message_image = new Message_Image();
                $message_image->create_images([
                    'ID_Message' => $messageid,
                    'Image_name' => $message_filename
                ]);
            }
        }
       

        $access_news_params = array(
            "ID_Message" => $messageid,
            "Tittle_Message" => $message_title,
            "Text_Message" => $message_text,
            "Date_Message"=> $message_datetime,
        );

        $result = $access_news->create_news(
            $access_news_params, $emp_id
        );
        return json_encode($result);
    }


    private function findbyID_Message($findbyID_Message)
    {
        $message = Message::findById($findbyID_Message);//echo json_encode($employee);
        $img =  Message_Image::get_images($findbyID_Message);
        // echo json_encode(array("data" => $data_sendback));
        $img_arr = [];
        if(count($img) > 0 ){
            foreach($img as $val){
                $img_arr[] = Router::getSourcePath() . "images/".$val->getImage_name();
            }
        }
        $data_sendback = array(
            "ID_Message" => $message->getID_Message(),
            "Tittle_Message" => $message->getTittle_Message(),
            "Text_Message" => $message->getText_Message(),
            "Date_Message" => $message->getDate_Message(),
            "img" => $img_arr
        );

        echo json_encode(array("data" => $data_sendback));

    }

    private function edit_news($params, $FILE_IMG, $ID_Message)
    {

        // # สร้างข่าวสารร
        $access_news = new Message();
        $messageid = $ID_Message ;
        $message_title =  $params["Tittle_Message"] ;
        $message_text  =  isset($params["Text_Message"]) ?  $params["Text_Message"] : "";
        $message_datetime = $access_news->geneateDateTime();
        
        if(isset($FILE_IMG['name'])){
            for($i=0;$i<=count($FILE_IMG['name']);$i++){
                if(!empty($FILE_IMG) && !empty($FILE_IMG['name'][$i])){
                    $name_file =  $FILE_IMG['name'][$i];
                    $ex =  explode('.',$name_file) ;
                    $name_file_type = end($ex);
                    $tmp_name =  $FILE_IMG['tmp_name'][$i];
                    $message_filename = md5(date('YmdHis').rand(1,999999)).".".$name_file_type;
                    $locate_img = Router::getSourcePath() . "images/" . $message_filename;
                    move_uploaded_file($tmp_name, $locate_img);
                    $message_image = new Message_Image();
                    $message_image->create_images([
                        'ID_Message' => $messageid,
                        'Image_name' => $message_filename
                    ]);
                }
            }
        }
       

        $access_news_params = array(
            "ID_Message" => $messageid,
            "Tittle_Message" => $message_title,
            "Text_Message" => $message_text,
            "Date_Message"=> $message_datetime,
        );


        $result = $access_news->update_news(
            $access_news_params
        );

        if(isset($_POST['del_files']) && count($_POST['del_files']) > 0){
            foreach($_POST['del_files'] as $val){
                $this->delete_img($val);
            }
        }

        return json_encode($result);
    }

    private function delete_news($params) {
        $access_message = new Message();
        $result = $access_message->delete_news(
            $params
        );
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
    //หน้าจัดการข่าวสาร
    private function manage_news($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];

        # retrieve data
        $employeeList = Employee::findAll();
        $message = Message::fetchAll();
        include Router::getSourcePath() . "views/admin/manage_news.inc.php";

    }

    private function show($params = null)
    {
        
        $employee = $_SESSION["employee"];
        $message = Message::findById($_GET['id']);
        $img = Message_Image::get_images($_GET['id']);
        if ($employee->getUser_Status_Employee() == "Admin") {
            include Router::getSourcePath() . "views/index_admin.inc.php";
        } else if ($employee->getUser_Status_Employee() == "Sales") {
            include Router::getSourcePath() . "views/sales/news.inc.php";
        } else if ($employee->getUser_Status_Employee() == "User") {
            include Router::getSourcePath() . "views/sales/news.inc.php";
        }
    }

    private function delete_img($filename) {
        $get_file_name = explode('/',$filename);
        $file_name = end($get_file_name);
        $result = Message_Image::delete_images($file_name);
        return json_encode($result);
    }

}