<?php

class Message
{
    //------------- Properties
    private $ID_Message;
    private $Tittle_Message;
    private $Text_Message;
    private $Date_Message;
    private $status;
    private $unread;
    private const TABLE = "message";



    //----------- Getters & Setters
	public function getUnread(): int
    {
        return $this->unread;
    }
    public function setUnread(int $unread)
    {
        $this->$unread = $unread;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    public function setStatus(int $status)
    {
        $this->$status = $status;
    }
    // ---- id message
    public function getID_Message(): int
    {
        return $this->ID_Message;
    }

    public function setID_Message(int $ID_Message)
    {
        $this->ID_Message = $ID_Message;
    }

    // --- title message
    public function getTittle_Message(): string 
    {
        return $this->Tittle_Message;
    }

    public function setTittle_Message(string $Tittle_Message)
    {
        $this->Tittle_Message = $Tittle_Message;
    }

    // - text message
    public function getText_Message(): string 
    {
        return $this->Text_Message;
    }

    public function setText_Message(string $Text_Message)
    {
        $this->Text_Message = $Text_Message;
    }



    // --- date message
    public function getDate_Message(): string 
    {
        return $this->Date_Message;
    }

    public function setDate_Message(string $Date_Message)
    {
        $this->Date_Message = $Date_Message;
    }


    //----------- CRUD
    public static function fetchCountAll($emp_id): array
    {
        $con = Db::getInstance();
        $query = "select count(*) from message_status where status =0 and ID_Employee = '".$emp_id."'";
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

    public static function fetchCountRowAll($emp_id): array
    {
        $con = Db::getInstance();
        $query = "select count(*) from message";
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
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Message()] = $prod;
        }
        return $list;

    }

    public static function fetchAllwithInner($emp_id): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " inner join message_status on message.ID_Message = message_status.ID_Message"." where message_status.ID_Employee = '".$emp_id."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Message()] = $prod;
        }
        return $list;
    }

    public static function fetchAllwithInnerLimit($emp_id,$start,$limit): array
    {
        $con = Db::getInstance();
        //$query = "SELECT * FROM " . self::TABLE . " inner join message_status on message.ID_Message = message_status.ID_Message"." where message_status.ID_Employee = '".$emp_id."' GROUP BY message_status.ID_Message LIMIT ".$start." , ".$limit.' ';
        $query = "SELECT * FROM " . self::TABLE . "  LIMIT ".$start." , ".$limit.' ';
       //echo $query;
        //exit;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Message()] = $prod;
        }
        return $list;

    }

    public static function findById(int $ID_Message): ?Message
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Message = '$ID_Message'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }


    public static function generateIDMessage($title_message)
    {
        $messageid = self::geneateDateTimemd() ;
        return  md5(uniqid($messageid, true)) ;
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

    public static function generatePictureFilename($imagename, $titlemessage)
    {
        $message_picture_filename = "$imagename"."$titlemessage".self::geneateDateTimemd() ;
        return  md5(uniqid($message_picture_filename, true)) ;
    }

    // save data to
    // insert data into server.
    public static function create_news($params, $emp_id)
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
//print_r($query);
//exit();
        # execute query
        if ($con->exec($query)) {
            $emp = new Employee();
            $result = $emp->findAll();
            # เข้า for loop เพือกระจาย status ของ  news
            foreach ($result as $prop => $val) {
                $emp_id = $val->getID_Employee();
                $con->exec("insert into message_status (ID_Employee, ID_Message) values('".$emp_id."',".$params['ID_Message'].")");
            }
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }

    }

    // update data at database
    public static function update_news($params)
    {

        $ID_Message = $params['ID_Message'];
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if($val != '') {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Message = '" . $ID_Message . "'";
        //print_r($query);

        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }

    }
    // update data at database
    public static function update_news_status($ID_Employee, $ID_Message)
    {

        //$ID_Message = $params['ID_Message'];
        $query = "UPDATE message_status SET status = 1 ";

        //$query = substr($query, 0, -1);
        $query .= " WHERE ID_Message = ".$ID_Message." and ID_Employee = '".$ID_Employee."'";

        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }

    }

    // update data at database
    public static function update_award_status($ID_Employee, $ID_Award)
    {

        //$ID_Award = $params['ID_Award'];
        $query = "UPDATE message_status SET status = 1 ";

        //$query = substr($query, 0, -1);
        $query .= " WHERE ID_Award = ".$ID_Award." and ID_Employee = '".$ID_Employee."'";

        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }

    }

    # ลบ company
    public function delete_news($ID_Message)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Message = '{$ID_Message}' ";
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
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[$prod->getID_Message()] = $prod;
        }
        return $list;

    }

    public static function message_unread($ID_Employee){
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $msg = array();
        while ($prod = $stmt->fetch()) {
            $msg[] = $prod;
        }
        $countMsg = count($msg);

        $query = "SELECT * FROM message_status WHERE ID_Employee='".$ID_Employee."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $read = array();
        while ($prod = $stmt->fetch()) {
            $read[] = $prod;
        }
        return $countMsg-count($read);
    }


     public static function update_message_status($ID_Employee, $ID_message)
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM message_status WHERE ID_Employee='".$ID_Employee."' AND ID_Message='".$ID_message."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message");
        $stmt->execute();
        $read = array();
        while ($prod = $stmt->fetch()) {
            $read[] = $prod;
        }
        
        if(count($read)=='0'){
            $query = 'INSERT INTO message_status(ID_Employee,ID_Message,status) VALUES("'.$ID_Employee.'","'.$ID_message.'","0")';
            $con->exec($query);
        }
       
        return array("status" => true);

    }


}

