<?php

class TrendTwoController
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
        $x = [];//เดือน
        $y = [];//ยอดขาย
        if(isset($_GET['type'])){
            $type = $_GET['type'];
            $day = [];
            $day_start = date('Y-m-d');
            // case : ย้อนหลัง x เดือน
            for($j=$type; $j>0; $j--){
                $day[] = array ( "date" => date('Y-m-d', strtotime('-'.$j.' month', strtotime($day_start))) , "type" => "last_month");  
            }
            // case : ก่อนหน้า x เดือน
            for($i=0; $i<=$type; $i++){
                $day[] = array ( "date" =>  date('Y-m-d', strtotime('+'.$i.' month', strtotime($day_start))), "type" => "previous_month"); 
            }
          
            
          
            $current_year = date('Y');
            $current_month = number_format(date('m'));
            for($i=1; $i<=$current_month; $i++){
                $startDate = $current_year.'-'.str_pad($i,2,'0',STR_PAD_LEFT).'-01';
                $endDate = $current_year.'-'.str_pad($i,2,'0',STR_PAD_LEFT).'-31';
                $total = Sales::sumDate($startDate,$endDate);
                if($total['p']==''){
                    $total['p'] = 0;
                }
                $y[] = $total['p'];
                $x[] = $i;
            }
          
            
            $si = $this->linear_regression($x,$y);
        }
        include Router::getSourcePath() . "views/admin/trend2.inc.php";
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

    private function linear_regression( $x, $y ) {
     
        $n     = count($x);     // number of items in the array
        $x_sum = array_sum($x); // sum of all X values
        $y_sum = array_sum($y); // sum of all Y values
         
     
        $xx_sum = 0;
        $xy_sum = 0;
         
        for($i = 0; $i < $n; $i++) {
            $xy_sum += ( $x[$i]*$y[$i] );
            $xx_sum += ( $x[$i]*$x[$i] );
           
        }
         
            // Slope
        $slope = ( ( $n * $xy_sum ) - ( $x_sum * $y_sum ) ) / ( ( $n * $xx_sum ) - ( $x_sum * $x_sum ) );
         
        // calculate intercept
        $intercept = ( $y_sum - ( $slope * $x_sum ) ) / $n;
    
        return array( 
            'slope'     => $slope,
            'intercept' => $intercept,
            'sum' => $y_sum
        );
    }
}