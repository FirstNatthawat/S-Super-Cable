<?php

class BorrowOrReturn
{

    //------------- Properties
    private $ID_BorrowOrReturn;
    private $ID_Promotion;
    private $Amount_BorrowOrReturn;
    private $Date_BorrowOrReturn;
    private $Detail_BorrowOrReturn;
    private $ID_Employee;
    private $Type_BorrowOrReturn;
    private $Approve_BorrowOrReturn;
    private $Name_Promotion;
    private $Name_Employee;
    private $Surname_Employee;
    private $Comment_BorrowOrReturn;
    private const TABLE = "borroworreturn";

    //----------- Getters & Setters
    public function getID_BorrowOrReturn(): int
    {
        return $this->ID_BorrowOrReturn;
    }

    public function setID_BorrowOrReturn(int $ID_BorrowOrReturn)
    {
        $this->ID_BorrowOrReturn = $ID_BorrowOrReturn;
    }

    public function getID_Promotion(): int
    {
        return $this->ID_Promotion;
    }

    public function setID_Promotion(int $ID_Promotion)
    {
        $this->ID_Promotion = $ID_Promotion;
    }

    public function getAmount_BorrowOrReturn() : int
    {
        return $this->Amount_BorrowOrReturn;
    }

    public function setAmount_BorrowOrReturn(string $Amount_BorrowOrReturn)
    {
        $this->Amount_BorrowOrReturn = $Amount_BorrowOrReturn;
    }

    public function getDate_BorrowOrReturn() : string
    {
       return $this->Date_BorrowOrReturn;
    }

    public function setDate_BorrowOrReturn(string $Date_BorrowOrReturn)
    {
        $this->Date_BorrowOrReturn = $Date_BorrowOrReturn;
    }

    public function getDetail_BorrowOrReturn() : string
    {
        return $this->Detail_BorrowOrReturn;
    }

    public function setDetail_BorrowOrReturn(string $Detail_BorrowOrReturn)
    {
        $this->Detail_BorrowOrReturn = $Detail_BorrowOrReturn;
    }

    public function getID_Employee() : int
    {
        return $this->ID_Employee;
    }

    public function setID_Employee(int $ID_Employee)
    {
        $this->ID_Employee = $ID_Employee;
    }

    public function getType_BorrowOrReturn() : int
    {
        return $this->Type_BorrowOrReturn;
    }

    public function setType_BorrowOrReturn(int $Type_BorrowOrReturn)
    {
        $this->Type_BorrowOrReturn = $Type_BorrowOrReturn;
    }

    public function getApprove_BorrowOrReturn() : int
    {
        return $this->Approve_BorrowOrReturn;
    }

    public function setApprove_BorrowOrReturn(int $Approve_BorrowOrReturn)
    {
        $this->Approve_BorrowOrReturn = $Approve_BorrowOrReturn;
    }

    ////
    public function getName_Promotion() : string
    {
        return $this->Name_Promotion;
    }


    public function getName_Employee() : string
    {
        return $this->Name_Employee;
    }


    public function getSurname_Employee() : string
    {
        return $this->Surname_Employee;
    }

    public function getComment_BorrowOrReturn() : string
    {
        return !empty($this->Comment_BorrowOrReturn) ? $this->Comment_BorrowOrReturn : '';
    }

    public function setComment_BorrowOrReturn(int $Comment_BorrowOrReturn)
    {
        $this->Comment_BorrowOrReturn = $Comment_BorrowOrReturn;
    }
    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        $goodsList = array();
        while ($prod = $stmt->fetch()) {
            $goodsList[$prod->getID_BorrowOrReturn()] = $prod;
        }
        return $goodsList;
    }
    public static function findById(string $ID_BorrowOrReturn): ?BorrowOrReturn
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_BorrowOrReturn = '$ID_BorrowOrReturn'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }
    public static function find(array $search): array
    {
        $where = " WHERE 1=1 ";
        if(isset($search['ID_BorrowOrReturn']) && $search['ID_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.ID_BorrowOrReturn = '".$search['ID_BorrowOrReturn']."'";
        }

        if(isset($search['ID_Promotion']) && $search['ID_Promotion']!=''){
            $where .= " AND borroworreturn.ID_Promotion = '".$search['ID_Promotion']."'";
        }

        if(isset($search['Date_BorrowOrReturn']) && $search['Date_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.Date_BorrowOrReturn = '".$search['Date_BorrowOrReturn']."'";
        }

        if(isset($search['ID_Employee']) && $search['ID_Employee']!=''){
            $where .= " AND borroworreturn.ID_Employee = '".$search['ID_Employee']."'";
        }

        if(isset($search['Type_BorrowOrReturn']) && $search['Type_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.Type_BorrowOrReturn = '".$search['Type_BorrowOrReturn']."'";
        }

        if(isset($search['Approve_BorrowOrReturn']) && $search['Approve_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.Approve_BorrowOrReturn = '".$search['Approve_BorrowOrReturn']."'";
        }

        if(isset($search['date_start']) && $search['date_end']!=''){
            $where .= " AND borroworreturn.Date_BorrowOrReturn BETWEEN '".$search['date_start']."' AND '".$search['date_end']."'";
        }

        $con = Db::getInstance();
        $query = "SELECT borroworreturn.* , 
                    employee.Name_Employee , 
                    employee.Surname_Employee ,
                    promotion.Name_Promotion 
                    FROM " . self::TABLE . " 
                    LEFT JOIN employee ON employee.ID_Employee = borroworreturn.ID_Employee 
                    LEFT JOIN promotion ON promotion.ID_Promotion = borroworreturn.ID_Promotion
                    ".$where;

        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        $dataList = array();
        while ($prod = $stmt->fetch()) {
            $dataList[]= $prod;
        }
        return $dataList;
    }
    # insert
    public function create(array $params)
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
        $query = "INSERT INTO " . self::TABLE . "({$columns}) VALUES ($values)";
        # execute query
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }
    }
   
    # แก้ไขสินค้า
    public function edit(array $params, string $ID_BorrowOrReturn)
    {
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if (!empty($val)) {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_BorrowOrReturn = '" . $ID_BorrowOrReturn . "'";
        //echo $query;exit();
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }

    # ลบ
    public function delete($ID_BorrowOrReturn)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_BorrowOrReturn = '{$ID_BorrowOrReturn}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }

    public static function findApproveHistory(): array
    {
        $con = Db::getInstance();
        $where = " WHERE borroworreturn.Approve_BorrowOrReturn='1' OR borroworreturn.Approve_BorrowOrReturn='2'";
        $query = "SELECT borroworreturn.* , 
                    employee.Name_Employee , 
                    employee.Surname_Employee ,
                    promotion.Name_Promotion 
                    FROM " . self::TABLE . " 
                    LEFT JOIN employee ON employee.ID_Employee = borroworreturn.ID_Employee 
                    LEFT JOIN promotion ON promotion.ID_Promotion = borroworreturn.ID_Promotion
                    ".$where;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        $dataList = array();
        while ($prod = $stmt->fetch()) {
            $dataList[] = $prod;
        }
        return $dataList;
    }


    public static function report(array $search): array
    {
        $where = " WHERE borroworreturn.Approve_BorrowOrReturn=1 ";
        if(isset($search['ID_BorrowOrReturn']) && $search['ID_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.ID_BorrowOrReturn = '".$search['ID_BorrowOrReturn']."'";
        }

        if(isset($search['ID_Promotion']) && $search['ID_Promotion']!=''){
            $where .= " AND borroworreturn.ID_Promotion = '".$search['ID_Promotion']."'";
        }

        if(isset($search['Date_BorrowOrReturn']) && $search['Date_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.Date_BorrowOrReturn = '".$search['Date_BorrowOrReturn']."'";
        }

        if(isset($search['ID_Employee']) && $search['ID_Employee']!=''){
            $where .= " AND borroworreturn.ID_Employee = '".$search['ID_Employee']."'";
        }

        if(isset($search['Type_BorrowOrReturn']) && $search['Type_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.Type_BorrowOrReturn = '".$search['Type_BorrowOrReturn']."'";
        }

        if(isset($search['Approve_BorrowOrReturn']) && $search['Approve_BorrowOrReturn']!=''){
            $where .= " AND borroworreturn.Approve_BorrowOrReturn = '".$search['Approve_BorrowOrReturn']."'";
        }

        if(isset($search['date_start']) && $search['date_end']!=''){
            $where .= " AND borroworreturn.Date_BorrowOrReturn BETWEEN '".$search['date_start']."' AND '".$search['date_end']."'";
        }

        $con = Db::getInstance();
        $query = "SELECT SUM(Amount_BorrowOrReturn) AS qty 
                    FROM " . self::TABLE . " ".$where." AND borroworreturn.Type_BorrowOrReturn=1 ";
        //echo $query.'<br/>';            
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        $data1 = 0;
        while ($prod = $stmt->fetch()) {
            $data1 = $prod->qty;
        }

        $query = "SELECT SUM(Amount_BorrowOrReturn) AS qty 
                    FROM " . self::TABLE . " ".$where." AND borroworreturn.Type_BorrowOrReturn=2 ";
                    
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        $data2 = 0;
        while ($prod = $stmt->fetch()) {
            $data2 = $prod->qty;
        }
        return ['borrow' => $data1,'borrow_return' => $data2];
    }


    public static function fetch_borrowranking(): array
    {
        $con = Db::getInstance();
       
        $query = "  
                    select * from (
                        select borroworreturn.ID_Promotion,promotion.Name_Promotion,count(*) as count from borroworreturn
                        inner join promotion on borroworreturn.ID_Promotion = promotion.ID_Promotion
                        where Type_BorrowOrReturn = 1
                        GROUP BY  ID_Promotion
                        order by count(*) desc
                    )r1
                    limit 5
                    
        ";
        
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "borroworreturn");
        $stmt->execute();
        $dataList = array();
        while ($prod = $stmt->fetch()) {
            $dataList[] = array("count" => $prod->count 
            , "ID_Promotion" => $prod->ID_Promotion 
            , "Name_Promotion" => $prod->Name_Promotion);
          
        }
        return $dataList;
    }

    

}
?>