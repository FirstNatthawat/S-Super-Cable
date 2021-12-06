<?php

class TrendController
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
            case "trend" :
                $this->$action();
                break;
            default:
                break;
        }
    }
  
    private function trend($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];
        # retrieve data
        $zoneList = Zone::findAll();
        $employeeList = Employee::findAll();
        $companyList = Company::findAll();
        $amphurList = Amphur::findAll();
        $provinceList = Province::findAll();
        if(isset($_GET['type'])){
            $type = $_GET['type'];
            $day = [];
            $day_start = date('Y-m-d');
            //
            $year = date('Y')-2;
            for( $i = $year; $i < date('Y'); $i++){
                for($a=1; $a<=12; $a++){
                    $d = $i.'-'.str_pad($a,2,"0",STR_PAD_LEFT).'-01';
                    //$day[] = $d;
                }
            }
            //
            for($i=0; $i<$type; $i++){
                $day[] = date('Y-m-d', strtotime('+'.$i.' month', strtotime($day_start)));
            }
        }
        include Router::getSourcePath() . "views/admin/trend.inc.php";
    }

    private function m($m = null)
    {

        $month = [
                '01' => 'มกราคม',
                '02' => 'กุมภาพันธ์',
                '03' => 'มีนาคม',
                '04' => 'เมษายน',
                '05' => 'พฤษภาคม',
                '06' => 'มิถุนายน',
                '07' => 'กรกฎาคม',
                '08' => 'สิงหาคม',
                '09' => 'กันยายน',
                '10' => 'ตุลาคม',
                '11' => 'พฤศจิกายน',
                '12' => 'ธันวาคม',
            ];
        return $month[$m];
    }
}