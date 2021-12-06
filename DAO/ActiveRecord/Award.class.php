<?php

class Award
{
    //------------- Properties
    private $ID_Award;
    private $Tittle_Award;
    private $Picture_Award;
    private $Picture_Award2;
    private $Picture_Award3;
    private $Date_Award;
    private $ID_Employee;
    private $fullname_employee;
    private $status;
    private const TABLE = "award";



    //----------- Getters & Setters
	public function getStatus(): int
    {

		return $this->status;
    }
    public function setStatus(int $status)
    {
        $this->status = $status;
    }
    // ---- id Award
    public function getID_Award(): int
    {
        return $this->ID_Award;
    }

    public function setID_Award(int $ID_Award)
    {
        $this->ID_Award = $ID_Award;
    }

    // --- title Award
    public function getTittle_Award(): string 
    {
        return $this->Tittle_Award;
    }

    public function setTittle_Award(string $Tittle_Award)
    {
        $this->Tittle_Award = $Tittle_Award;
    }

    // --- picture Award
    public function getPicture_Award(): string 
    {
        return $this->Picture_Award;
    }

    public function setPicture_Award(string $Picture_Award)
    {
        $this->Picture_Award = $Picture_Award;
    }
    public function getPicture_Award2(): string
    {
        return $this->Picture_Award2;
    }

    public function setPicture_Award2(string $Picture_Award2)
    {
        $this->Picture_Award2 = $Picture_Award2;
    }
    public function getPicture_Award3(): string
    {
        return $this->Picture_Award3;
    }

    public function setPicture_Award3(string $Picture_Award3)
    {
        $this->Picture_Award3 = $Picture_Award3;
    }

    // --- date Award
    public function getDate_Award(): string 
    {
        return $this->Date_Award;
    }

    public function setDate_Award(string $Date_Award)
    {
        $this->Date_Award = $Date_Award;
    }

    public function getID_Employee() : string
    {
        if ($this->ID_Employee == null)
            return "-";
        else
            return $this->ID_Employee;
    }

    public function setID_Employee(string $ID_Employee)
    {
        $this->getID_Employee = $ID_Employee;
    }

    public function getFullname_employee() : string
    {
        if ($this->fullname_employee == null)
            return "-";
        else
        return $this->fullname_employee;
    }

    public function setFullname_employee(string $fullname_employee)
    {
        $this->getFullname_employee = $fullname_employee;
    }

    //CRUD

    public static function fetchCountAll($emp_id): array
    {
        $con = Db::getInstance();
        $query = "select count(*) from award_status where status =0 and ID_Employee = '".$emp_id."'";
        $stmt = $con->prepare($query);
        #$stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        #$list = array();
        #while ($prod = $stmt->fetch()) {
        #    $list[$prod->getID_Message()] = $prod;
        #}
        $prod = $stmt->fetch();

        return $prod;
        #return $list;

    }

    public static function fetchAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT " . self::TABLE . ".*,employee.ID_Employee, concat(employee.Name_Employee, ' ',employee.Surname_Employee) as fullname_employee  FROM " . self::TABLE . " 
        LEFT JOIN employee ON " . self::TABLE . ".ID_Employee = employee.ID_Employee  " ;

        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Award()] = $prod;
        }
        return $list;

    }
    public static function fetchAllwithInner($emp_id): array
    {
        $con = Db::getInstance();
        #$query = "select * from award inner join award_status on award_status.ID_Award = award.ID_Award where award_status.ID_Employee = 's0001'";
        $query = "select *, employee.Name_Employee as fullname_employee from award inner join award_status on award_status.ID_Award = award.ID_Award inner join employee on award.ID_Employee = employee.ID_Employee where award_status.ID_Employee = '".$emp_id."'";

        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Award()] = $prod;
        }
        return $list;

    }
    public static function fetchCountRowAll($emp_id)
    {
        $con = Db::getInstance();
        $query = "select count(*) from award";
        $stmt = $con->prepare($query);
        //$stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $list = array();
        //while ($prod = $stmt->fetch()) {
            //$list[$prod->getID_Message()] = $prod;
        //}
        $prod = $stmt->fetch();

        return $prod;
        //return $list;

    }
    public static function fetchAllwithInnerLimit($emp_id,$start,$limit): array
    {
        $con = Db::getInstance();
        $query = "SELECT *,employee.Name_Employee as fullname_employee  FROM " . self::TABLE . " inner join award_status on award.ID_Award = award_status.ID_Award"." inner join employee on award.ID_Employee = employee.ID_Employee where award_status.ID_Employee = '".$emp_id."' LIMIT ".$start." , ".$limit;

        $query = "SELECT * FROM " . self::TABLE . "  LIMIT ".$start." , ".$limit;
        //echo $query;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Award()] = $prod;
        }
        return $list;

    }

    public static function findAward_byID(int $ID_Award): ?Award
    {
        $con = Db::getInstance();
        $query = "SELECT * ,employee.Name_Employee as fullname_employee  FROM " . self::TABLE ." inner join employee on award.ID_Employee = employee.ID_Employee ". " WHERE ID_Award = '$ID_Award' ";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }

    public static function generateIDAward($title_award)
    {
        $awardid = self::geneateDateTimemd() ;
        return  md5(uniqid($awardid, true)) ;
    }

    public static function geneateDateTimemd()
    {
        date_default_timezone_set('Asia/Bangkok');
        return Date("YmdHis") ;
    }

    public static function geneateDateTime()
    {
        date_default_timezone_set('Asia/Bangkok');
        return date("Y-m-d H:i:s") ;
    }

    public static function generatePictureFilename($imagename, $titleaward)
    {
        $award_picture_filename = "$imagename"."$titleaward".self::geneateDateTimemd() ;
        return  md5(uniqid($award_picture_filename, true)) ;
    }


    //  save data into database

    public static function create_award($awardModel)
    {
        $con = Db::getInstance();
        $values = "";
        $columns = "";
        foreach ($awardModel as $prop => $val) {
            # ถ้า column แรกไม่ต้องเติมลูกน้ำ คอลัมน์อื่นเติมลูกน้ำ
            $columns = empty($columns) ? $columns .= $prop : $columns .= "," . $prop;
            $values .= "'$val',";
        }
        $values = substr($values, 0, -1);
        $query = "INSERT INTO " . self::TABLE . "({$columns}) VALUES ($values)";

        # execute query
        if ($con->exec($query)) {
            $emp = new Employee();
            $result = $emp->findAll();
            # เข้า for loop เพือกระจาย status ของ  awards
            foreach ($result as $prop => $val) {
                $emp_id = $val->getID_Employee();
                $con->exec("insert into award_status (ID_Employee, ID_Award) values('".$emp_id."',".$awardModel['ID_Award'].")");
            }
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }

    }

    public static function update_award($awardUpdateModel)
    {
        $ID_Award = $awardUpdateModel['ID_Award'];
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($awardUpdateModel as $prop => $val) {
            if($val != '') {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Award = '" . $ID_Award . "'";
        //echo $query;
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }

    public static function update_award_status($ID_Employee, $ID_Award)
    {
        //$ID_Message = $params['ID_Message'];
        $query = "UPDATE award_status SET status = 1 ";

        //$query = substr($query, 0, -1);
        $query .= " WHERE ID_Award = ".$ID_Award." and ID_Employee = '".$ID_Employee."'";

        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }

    public static function delete_award($ID_Award)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Award = '{$ID_Award}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }
    public static function select($where=''): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE." ".$where;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Award()] = $prod;
        }
        return $list;

    }

    public static function update_award_status2($ID_Employee, $ID_Award)
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM award_status WHERE ID_Employee='".$ID_Employee."' AND ID_Award='".$ID_Award."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        $read = array();
        while ($prod = $stmt->fetch()) {
            $read[] = $prod;
        }
        
        if(count($read)=='0'){
            $query = 'INSERT INTO award_status(ID_Employee,ID_Award,status) VALUES("'.$ID_Employee.'","'.$ID_Award.'","0")';
            $con->exec($query);
        }
       
        return array("status" => true);

    }

       public static function message_unread($ID_Employee){
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Award");
        $stmt->execute();
        $msg = array();
        while ($prod = $stmt->fetch()) {
            $msg[] = $prod;
        }
        $countMsg = count($msg);

        $query = "SELECT * FROM award_status WHERE ID_Employee='".$ID_Employee."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $read = array();
        while ($prod = $stmt->fetch()) {
            $read[] = $prod;
        }
        return $countMsg-count($read);
    }

}