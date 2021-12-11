<?php

class BorrowController
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
            case "borrow":
                session_start();
                $this->borrow();
                break;
            case "borrow_insert":
                session_start();
                $this->borrowInsert();
                break;
            case "borrow_delete":
                session_start();
                $this->borrowDelete();
                break;
            case "borrow_approve_list":
                session_start();
                $this->borrowApproveList();
                break;
            case "borrow_approve_dis":
                session_start();
                $this->borrowDisApprove();
                break;
            case "borrow_approve_save":
                session_start();
                $this->borrowApproveSave();
                break;
            case "borrow_approve_history":
                session_start();
                $this->borrowApproveHistory();
                break;
            case "borrowById":
                session_start();
                $this->borrowById();
                break;
            case "borrow_update":
                $this->borrow_update();
                break;
            case "fetch_borrow":
                $this->fetch_borrow();
                break;
            default:
                break;
        }
    }

    private function borrow()
    {
        //print_r($_SESSION);
        $employee = $_SESSION['employee'];
        $promotionList = Promotion::listArray();
        //print_r($promotion);
        //exit;
        $borrow = BorrowOrReturn::find(['ID_Employee'=>$employee->getID_Employee()]);
        //print_r($borrow);
        //exit;
        //print_r($_SESSION['employee']->getID_Employee());
        //$b = BorrowOrReturn::findAll();
        include Router::getSourcePath() . "views/admin/borrow.inc.php";
    }

    private function borrowInsert(){
        $access = new BorrowOrReturn();
        $promotion = Promotion::findById($_POST['ID_Promotion']);
        $qty = $promotion->getUnit_Promotion();
        if($_POST['Amount_BorrowOrReturn']>$qty && $_POST['Type_BorrowOrReturn']=='1'){
            header('Content-type: application/json');
            echo json_encode(["status" => false, "message" => "จำนวนสินค้าไม่พอ"]);
            exit;
        }
        $access_params = array(
            'ID_Promotion' => $_POST['ID_Promotion'],
            'Amount_BorrowOrReturn' => $_POST['Amount_BorrowOrReturn'],
            'Date_BorrowOrReturn' => date('Y-m-d'),
            'Detail_BorrowOrReturn' => $_POST['Detail_BorrowOrReturn'],
            'ID_Employee' => $_SESSION['employee']->getID_Employee(),
            'Type_BorrowOrReturn' => $_POST['Type_BorrowOrReturn'],
            'Approve_BorrowOrReturn' => '0'
        );

        $result = $access->create($access_params);
        header('Content-type: application/json');
        echo json_encode(["status" => true]);
    }

    private function borrowDelete(){
        $access = new BorrowOrReturn();
        $result = $access->delete($_GET['id']);
        header('Content-type: application/json');
        echo json_encode(["status" => true]);
    }

    private function borrowApproveList()
    {
        $employee = $_SESSION['employee'];
        $promotion = Promotion::listArray();
        $promotionList = Promotion::listArray();
        $borrow = BorrowOrReturn::find(['Approve_BorrowOrReturn' => '0']);
        include Router::getSourcePath() . "views/admin/borrow_approve_list.inc.php";
    }

    private function borrowDisApprove(){
        $access = new BorrowOrReturn();
        $result = $access->edit(['Approve_BorrowOrReturn' => '2' , 'Comment_BorrowOrReturn' => $_GET['comment'] ], $_GET['id']);
        header('Content-type: application/json');
        echo json_encode(["status" => true]);
    }

    private function borrowApproveSave(){
        $borrow = BorrowOrReturn::find(['ID_BorrowOrReturn' => $_GET['id']]);
        $borrow_qty = $borrow[0]->getAmount_BorrowOrReturn();
        $promotion = Promotion::findById($borrow[0]->getID_Promotion());
        if($promotion->getUnit_Promotion() < $borrow_qty && $borrow->getType_BorrowOrReturn()=='1'){
            header('Content-type: application/json');
            echo json_encode(["status" => false,"msg" => "จำนวนสินค้าไม่พอ"]);
        }

        if($borrow[0]->getType_BorrowOrReturn()=='1'){
            $qty = $promotion->getUnit_Promotion() - $borrow_qty;
        }else{
            $qty = $promotion->getUnit_Promotion() + $borrow_qty;
        }
        $access = new BorrowOrReturn();
        $result = $access->edit(['Approve_BorrowOrReturn' => '1'], $_GET['id']);

        $access = new Promotion();
        $access->edit_promotion(['Unit_Promotion' => $qty],$borrow[0]->getID_Promotion());
        header('Content-type: application/json');
        echo json_encode(["status" => true]);
       
    }


    private function borrowApproveHistory()
    {
        $employee = $_SESSION['employee'];
        $promotion = Promotion::listArray();
        $borrow = BorrowOrReturn::findApproveHistory();
        include Router::getSourcePath() . "views/admin/borrow_approve_history.inc.php";
    }


    // ควรมีสำหรับ controller ทุกตัว
    private function index()
    {
        include Router::getSourcePath() . "views/login.inc.php";
    }

    private function borrowById(){
       
        $row =  BorrowOrReturn::findById($_GET['ID_BorrowOrReturn']);
        $data = [
            'ID_BorrowOrReturn' => $row->getID_BorrowOrReturn(),
            'ID_Promotion' => $row->getID_Promotion(),
            'Amount_BorrowOrReturn' => $row->getAmount_BorrowOrReturn()
        ];
        header('Content-type: application/json');
        echo json_encode(["status" => true,"data" => $data]);
    }


    private function borrow_update(){
        /*
        $borrow = BorrowOrReturn::find(['ID_BorrowOrReturn' => $_GET['id']]);
        $borrow_qty = $borrow[0]->getAmount_BorrowOrReturn();
        $promotion = Promotion::findById($borrow[0]->getID_Promotion());
        if($promotion->getUnit_Promotion() < $borrow_qty && $borrow->getType_BorrowOrReturn()=='1'){
            header('Content-type: application/json');
            echo json_encode(["status" => false,"msg" => "จำนวนสินค้าไม่พอ"]);
        }

        if($borrow[0]->getType_BorrowOrReturn()=='1'){
            $qty = $promotion->getUnit_Promotion() - $borrow_qty;
        }else{
            $qty = $promotion->getUnit_Promotion() + $borrow_qty;
        }
        */
        $access = new BorrowOrReturn();
        $result = $access->edit(['Amount_BorrowOrReturn' => $_POST['Amount_BorrowOrReturn']], $_POST['hid']);

        //$access = new Promotion();
        //$access->edit_promotion(['Unit_Promotion' => $qty],$borrow[0]->getID_Promotion());
        header('Content-type: application/json');
        echo json_encode(["status" => true]);
       
    }

    private function fetch_borrow(){
        $access_borrow = new BorrowOrReturn();
        $fetch_borrowranking = $access_borrow->fetch_borrowranking();
       
        echo json_encode(["data" => $fetch_borrowranking]);
    }

}