<?php

class Sales
{
    //------------- Properties
    private $ID_Excel;
    private $Date_Sales;
    private $ID_Company;
    private $Name_Company;
    private $ID_Employee;
    private $Result_Sales;
    private const TABLE = "sales";

    //----------- Getters & Setters
    public function getID_Excel(): int
    {
        return $this->ID_Excel;
    }

    public function setID_Excel(int $ID_Excel)
    {
        $this->ID_Excel = $ID_Excel;
    }

    public function getDate_Sales(): string
    {
        return $this->Date_Sales;
    }

    public function setDate_Sales(string $Date_Sales)
    {
        $this->Date_Sales = $Date_Sales;
    }

    public function getID_Company(): int
    {
        return $this->ID_Company;
    }

    public function setID_Company(int $ID_Company)
    {
        $this->ID_Company = $ID_Company;
    }

    public function getName_Company(): string
    {
        return $this->Name_Company;
    }

    public function setName_Company(string $Name_Company)
    {
        $this->Name_Company;
    }

    public function getName_Employee(): string
    {
        return $this->Name_Employee;
    }

    public function setName_Employee(string $Name_Employee)
    {
        $this->Name_Employee;
    }

    public function getID_Employee(): string
    {
        return $this->ID_Employee;
    }

    public function setID_Employee(int $ID_Employee)
    {
        $this->ID_Employee = $ID_Employee;
    }

    public function getResult_Sales(): float
    {
        return $this->Result_Sales;
    }

    public function setResult_Sales(float $Result_Sales)
    {
        $this->Result_Sales = $Result_Sales;
    }

    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        //case: จัดการยอดขายเปลี่ยนไอดีบริษัทเป็นชื่อบริษัทเปลี่ยนไอดีพนักงานเป็นชื่อพนักงาน
        $query = "SELECT " . self::TABLE . ".* ,company.Name_Company,employee.Name_Employee  FROM " . self::TABLE . " 
        join company on " . self::TABLE . ".ID_Company=company.ID_Company 
        join employee on " . self::TABLE . ".ID_Employee=employee.ID_Employee 
         ";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Sales");
        $stmt->execute();
        $salesList = array();
        while ($prod = $stmt->fetch()) {
            $salesList[$prod->getID_Excel()] = $prod;
        }
        return $salesList;
    }
    public static function findcompany(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM ". self::TABLE ."inner join company on company.ID_Company =". self::TABLE .".ID_Company where Name_Company = $Name_Company ";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Sales");
        $stmt->execute();
        $salesList = array();
        while ($prod = $stmt->fetch()) {
            $salesList[$prod->getID_Excel()] = $prod;
        }
        return $salesList;
    }

    public static function findById(int $ID_Excel): ?Sales
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Excel = '$ID_Excel'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Sales");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }

    # จัดการยอดขาย  ( เพิ่มยอดขาย )
    public function create_sales(array $params)
    {
        $con = Db::getInstance();
        $values = "";
        $columns = "";
        foreach ($params as $prop => $val) {
            # ถ้า column แรกไม่ต้องเติมลูกน้ำ คอลัมน์อื่นเติมลูกน้ำ
            $columns = empty($columns) ? $columns .= $prop : $columns .= "," . $prop;
            $values .= "'$val',";
        }
        $values = substr($values, 0, -1);
        $query = "INSERT INTO " . self::TABLE . " ({$columns}) VALUES ($values)";
        //return $query;
        # execute query
        if ($con->exec($query)) {
            $this->ID_Excel = $con->lastInsertId();
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }
    }

    # จัดการยอดขาย  ( เพิ่มยอดขาย excel )
    public function create_sales_at_once(array $params)
    {
        error_reporting(0); // Turn off all error reporting
        $con = Db::getInstance();
        // turn off auto commit (ปิดคำสั่งสำหรับการยืนยันการเปลี่ยนแปลงข้อมูลที่เกิดขึ้น)
        $con->beginTransaction();
        foreach ($params as $k => $v) {
            $values = "";
            $columns = "";
            $chk = 0;
            foreach ($v as $prop => $val) {
                # ถ้า column แรกไม่ต้องเติมลูกน้ำ คอลัมน์อื่นเติมลูกน้ำ ..
                
                if($prop == "Date_Sales"){
                    // convert format from  day/month/year to year-month-day 
                   
                    $explode_string = explode("/" , $val);
                    $date = $explode_string[0];
                    $month= $explode_string[1];
                    $year = intval($explode_string[2]) - 543; // แปลงพศ. เป็น คศ
                   //echo date("Y-m-d", strtotime("{$val}"));exit();
                    $val =  date("Y-m-d", strtotime("{$year}-{$month}-{$date}"));
                 
                    
               
                }
                $columns = empty($columns) ? $columns .= $prop : $columns .= "," . $prop;
                if($chk=='0'){
                    $values .= "'$val'";
                }else{
                    $values .= ",'$val'";
                }
                $chk++;
            }
            # insert ลง db
            //$values-> findcompany();
            //$values = substr($values, 0, -1);
            $query = "INSERT INTO " . self::TABLE . "({$columns}) VALUES ($values)" ;
            //echo $query; exit();
            # execute query
            if ($con->exec($query)) {
                # do something
            } else {
                # rollback when got error
                $con->rollBack();
                $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
                return array("status" => false, "message" => $message);
            }
        }
        # commit
        $con->commit();
        return array("status" => true);
    }

    public function file_log(string $file_name, int $id)
    {
        $query = "UPDATE file_log SET file_name = '{$file_name}' where id = {$id} ";
        //echo $query;exit();
        $con = Db::getInstance();
        if ($con->exec($query)) {

            return array("status" => true);
        }

    }

    # แก้ไข ยอดขาย
    public function edit_sales(array $params, string $ID_Excel)
    {
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if (!empty($val)) {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Excel = '" . $ID_Excel . "'";
        //echo $query;exit();
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }

    # ลบ ยอดขาย
    public function delete_sales($ID_Excel)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Excel = '{$ID_Excel}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }

    public static function sumDate($startDate,$endDate)
    {
        $con = Db::getInstance();
        $query = "SELECT SUM(Result_Sales) AS p FROM " . self::TABLE . " WHERE sales.Date_Sales BETWEEN '".$startDate."' AND '".$endDate."'";
       
        $stmt = $con->prepare($query);
        //$stmt->setFetchMode(PDO::FETCH_CLASS, "Sales");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }

    public static function customerNotMovingReport($startDate,$endDate) : array
    {

        $con = Db::getInstance();
        #$where = "SELECT sales.ID_Company FROM sales WHERE sales.Date_Sales BETWEEN '".$startDate."'
        #           AND '".$endDate."'  ";
      
        #$query = "SELECT * FROM company WHERE company.ID_Company NOT IN (".$where.") ";
        // $query = "SELECT * FROM `company` inner join sales on company.`ID_Company` = sales.ID_Company ".$where;
        /* case : new query 2021-12-10              */
        
        $where = "where r1.Date_Sales  NOT BETWEEN '".$startDate."' AND '".$endDate."' OR r1.Date_Sales is null";
        $query = " SELECT * FROM (
            SELECT company.ID_Company,company.Name_Company,sales.Date_Sales FROM `company` left join sales on company.`ID_Company` = sales.ID_Company
            GROUP BY company.ID_Company
            ORDER BY sales.Date_Sales DESC
        )r1
        {$where}
         ";

        /* ======================================== */
        //$query = "SELECT *, DATEDIFF( CURRENT_DATE(),MAX(sales.Date_Sales)) AS SUM   FROM `company` inner join sales on company.`ID_Company` = sales.ID_Company where ID_Company ='{$ID_Company}'";
        //echo $query;exit();
        #die($query);
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Company");
        $stmt->execute();
        $rows = array();
      
        while ($prod = $stmt->fetch()) {

            $rows[] = $prod;
        }
      
        return $rows;
    }
    public static function customerReport($Cluster_Shop_ID,$startDate,$endDate)
    {
        $con = Db::getInstance();
        /*
        $where = " invoice.Invoice_Date BETWEEN '".$startDate."' AND '".$endDate."'";
        $where .= " AND cluster_shop.Cluster_Shop_ID='".$Cluster_Shop_ID."' ";
        $query = "SELECT SUM(Grand_Total) AS TOTAL_SUM FROM invoice
                    LEFT JOIN company ON company.ID_Company = invoice.ID_Company
                    LEFT JOIN cluster_shop ON cluster_shop.Cluster_Shop_ID = company.Cluster_Shop_ID
                    WHERE ".$where." ";
        //echo $query;
        //exit;
        */
        $where = " sales.Date_Sales BETWEEN '".$startDate."' AND '".$endDate."'";
        $where .= " AND cluster_shop.Cluster_Shop_ID='".$Cluster_Shop_ID."' ";
        $query = "SELECT SUM(Result_Sales) AS TOTAL_SUM FROM sales
                    LEFT JOIN company ON company.ID_Company = sales.ID_Company 
                    LEFT JOIN cluster_shop ON cluster_shop.Cluster_Shop_ID = company.Cluster_Shop_ID 
                    WHERE ".$where." ";
        //echo $query;
        //exit;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "sales");
        $stmt->execute();
        $total = array();
        while ($prod = $stmt->fetch()) {
            $total = $prod->TOTAL_SUM;
        }
        return $total;
    }

    public static function customerReport2($Cluster_Shop_ID,$startDate,$endDate)
    {
        $con = Db::getInstance();
        /*
        $where = " invoice.Invoice_Date BETWEEN '".$startDate."' AND '".$endDate."'";
        $where .= " AND cluster_shop.Cluster_Shop_ID='".$Cluster_Shop_ID."' ";
        $query = "SELECT SUM(Grand_Total) AS TOTAL_SUM FROM invoice
                    LEFT JOIN company ON company.ID_Company = invoice.ID_Company
                    LEFT JOIN cluster_shop ON cluster_shop.Cluster_Shop_ID = company.Cluster_Shop_ID
                    WHERE ".$where." ";
        //echo $query;
        //exit;
        */
        $where = " sales.Date_Sales BETWEEN '".$startDate."' AND '".$endDate."'";
        $where .= " AND cluster_shop.Cluster_Shop_ID='".$Cluster_Shop_ID."' ";
        $query = "SELECT COUNT(Result_Sales) AS TOTAL_SUM FROM sales
                    LEFT JOIN company ON company.ID_Company = sales.ID_Company 
                    LEFT JOIN cluster_shop ON cluster_shop.Cluster_Shop_ID = company.Cluster_Shop_ID 
                    WHERE ".$where." ";
        //echo $query;
        //exit;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "sales");
        $stmt->execute();
        $total = array();
        while ($prod = $stmt->fetch()) {
            $total = $prod->TOTAL_SUM;
        }
        return $total;
    }

    public static function getDay($ID_Company)
    {
        $con = Db::getInstance();
        $query = "SELECT Date_Sales FROM sales WHERE sales.ID_Company='".$ID_Company."' ORDER BY Date_Sales LIMIT 0,1";
        //echo $query;
        //exit;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "sales");
        $stmt->execute();
        $Date_Sales = '';
        while ($prod = $stmt->fetch()) {
            $Date_Sales = $prod->Date_Sales;
        }
        return $Date_Sales;
    }
}

?>

