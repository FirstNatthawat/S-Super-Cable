<?php

class SalesStatisticsController
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
            case "manage_SalesStatistics" :
                $this->$action();
                break;
            case "getReport":
                $result = $this->$action();
                break;
            case "getReport2":
                $result = $this->$action();
                break;
            default:
                break;
        }
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
    //หน้าจัดการSalesStatistics
    private function manage_SalesStatistics($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];
        # retrieve data
        $zoneList = Zone::findAll();
        $employeeList = Employee::findAll();
        $companyList = Company::findAll();
        $amphurList = Amphur::findAll();
        $provinceList = Province::findAll();
        include Router::getSourcePath() . "views/admin/sales_statistics.inc.php";
    }
    private function getReport(){
        $ID_Employee = $_POST['ID_Employee'];
        //$ID_Employee = 'a004';
        $year1 = $_POST['year1'];
        $year2 = $_POST['year2'];
        $year_start = date('Y')-5;
        $year_arr = [$year1,$year2];
        $data = [];
        foreach($year_arr as $i){
            for($m=1;$m<=12;$m++){
                $date_start = $i.'-'.str_pad($m,2,"0",STR_PAD_LEFT).'-01';
                $date_end = $i.'-'.str_pad($m,2,"0",STR_PAD_LEFT).'-31';
                $con = Db::getInstance();
                $sql = 'SELECT SUM(Result_Sales) AS total FROM sales 
                WHERE Date_Sales BETWEEN "'.$date_start.'" AND "'.$date_end.'" 
                AND ID_Employee="'. $ID_Employee.'"';
                //echo $sql;
                $stmt = $con->prepare($sql);
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Date_Sales");
                $stmt->execute();
                while ($prod = $stmt->fetch()) {
                    //print_r($prod);
                    $data[$i]['year'] = $i;
                    $data[$i]['month'][$m] = (int)$prod['total'];
                }
            }
        }

        header('Content-type: application/json');
        echo json_encode([$data]);
    }

    private function getReport2(){
        $ID_Employee = $_POST['ID_Employee'];
        //$ID_Employee = 'a004';
        //$year1 = $_POST['year1'];
        //$year2 = $_POST['year1'];
        $year1 = date('Y');
        $year2 = date('Y')-1;
        $year3 = date('Y')-2;
        $year_start = date('Y')-5;
        $year_arr = [$year1,$year2,$year3];
        $data = [];
        foreach($year_arr as $i){
            for($m=1;$m<=12;$m++){
                $date_start = $i.'-'.str_pad($m,2,"0",STR_PAD_LEFT).'-01';
                $date_end = $i.'-'.str_pad($m,2,"0",STR_PAD_LEFT).'-31';
                $con = Db::getInstance();
                $sql = 'SELECT SUM(Result_Sales) AS total FROM sales 
                WHERE Date_Sales BETWEEN "'.$date_start.'" AND "'.$date_end.'" 
                AND ID_Employee="'. $ID_Employee.'"';
                //echo $sql;
                $stmt = $con->prepare($sql);
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Date_Sales");
                $stmt->execute();
                while ($prod = $stmt->fetch()) {
                    //print_r($prod);
                    $data[$i]['year'] = $i;
                    $data[$i]['month'][$m] = (int)$prod['total'];
                }
            }
        }

        header('Content-type: application/json');
        echo json_encode([$data]);
    }
}