<?php
class FileController
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
            case "manage_file" :
                $this->$action();
                break;
            case "create_file":
                $FILE = isset($params["FILES"]["Path_File"]) ? $params["FILES"]["Path_File"] : "";
                $result = $this->$action($params["POST"], $FILE);
                echo $result;
                break;
            case "findById" :
                $ID_File = isset($params["POST"]["ID_File"]) ? $params["POST"]["ID_File"] : "";
                if (!empty($ID_File)) {
                    $result = $this->$action($ID_File);
                    echo $result;
                }
                break;
            case "edit_file" :
                $FILE = isset($params["FILES"]) ? $params["FILES"] : "";
                $Params= isset($params["POST"]) ? $params["POST"] : "";
                $ID_File = isset($params["GET"]["ID_File"]) ? $params["GET"]["ID_File"] : "";
                $result = $this->$action($params["POST"] ,$FILE, $ID_File);
                echo $result;
                break;
            case "delete_file":
                $params = isset($params["GET"]["ID_File"]) ? $params["GET"]["ID_File"] : "";
                $result = $this->$action($params);
                // print_r($params);
                echo $result;
                break;
            case "show_index_download_file" :
                $this->$action();
                break;
            case "download_file":
                //$ID_File = isset($params["POST"]["ID_File"]) ? $params["POST"]["ID_File"] : "";
                $ID_File = $_GET['ID_File'];
                if (!empty($ID_File)) {
                    $result = $this->$action($ID_File);
                    //echo $result;
                }

                break;
            default:
                break;
        }
    }
    private function create_file($params, $FILE)
    {
        $access_file = new File();

         # สร้างไฟล์
        $Name_File =  $params["Name_File"] ;
        $Path_File = !empty($FILE) ?  $FILE['name'][0] : "" ;
        $Detail_File =  $params["Detail_File"] ;
        $locate = "";

        if (!empty($FILE) && !empty($FILE['name']))
        {
            $name_file =  $FILE['name'][0];
            $name_file_type =  explode('.',$name_file)[1] ;
            $tmp_name =  $FILE['tmp_name'][0];
            $locate = Router::getSourcePath() . "uploads/"  . $Path_File ;

            // copy original file to destination file
            move_uploaded_file($tmp_name, $locate);
        }

        $access_file_params = array(
            "Name_File" => $Name_File,
            "Path_File" => $locate,
            "Detail_File" => $Detail_File,
        );

        $result = $access_file->create_file(
            $access_file_params
        );

        return json_encode($result);
    }
    private function findById(int $ID_File)
    {
        $file = File::findById($ID_File);//echo json_encode($employee);

        // echo json_encode(array("data" => $data_sendback));

        $data_sendback = array(
            "ID_File" => $file->getID_File(),
            "Name_File" => $file->getName_File(),
            "Path_File" => $file->getPath_File(),
            "Detail_File" => $file->getDetail_File(),
            "Date_Upload_File" => $file->getDate_Upload_File(),
        );

        echo json_encode(array("data" => $data_sendback));

    }
    private function edit_file($params, $FILE, $ID_File)
    {
        $access_file = new File();
        $fileOld = File::findById($ID_File);
        # อัปเดตไฟล์
        $ID_file = $ID_File;
        $Name_File =  $params["Name_File"] ;
        $Path_File = !empty($FILE) ?  $FILE['Path_File']['name'][0] : "" ;
        $Detail_File = $params["Detail_File"]  ;
        $locate = $fileOld->getPath_File();
        if (!empty($FILE) && !empty($FILE['Path_File']['name']))
        {
            $name_file =  $FILE['Path_File']['name'][0];
            $name_file_type =  explode('.',$name_file)[1] ;
            $tmp_name =  $FILE['Path_File']['tmp_name'][0];
            $locate = Router::getSourcePath() . "uploads/"  . $Path_File  ;
            // copy original file to destination file
            move_uploaded_file($tmp_name, $locate);
        }

        $access_file_params = array(
            "ID_File" => $ID_file,
            "Name_File" => $Name_File,
            "Path_File" => $locate,
            "Detail_File" => $Detail_File,
        );
        
        $result = $access_file->edit_file(
            $access_file_params
        );
        

        return json_encode($result);
    }
    private function delete_file($params) {
        $access_file = new File();
        $result = $access_file->delete_file(
            $params
        );
        return json_encode($result);
    }
    private function download_file($params)
    {
        
        $access_file = new File();
        $result = $access_file->findById($_GET['ID_File']);
        print_r($result);
        $filepath = $result->getPath_File();
        if (file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($filepath).'"');
            //header('Expires: 0');
            //header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filepath ));
            readfile($filepath,true);

        }

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
    //หน้าจัดการไฟล์
    private function manage_file($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];

        # retrieve data
        $employeeList = Employee::findAll();
        $fileList = File::findAll();
        include Router::getSourcePath() . "views/admin/manage_file.inc.php";

    }
    private function show_index_download_file($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];

        # retrieve data
        $employeeList = Employee::findAll();
        $fileList = File::findAll();
        $user_status = $_SESSION['employee']->getUser_Status_Employee();
        if(strtolower($user_status)=='sales'){
            include Router::getSourcePath() ."views/admin/index_download_file.inc.php";
        }else if(strtolower($user_status)=='admin') {
            include Router::getSourcePath() . "views/admin/index_download_file.inc.php";
        }
    }

}