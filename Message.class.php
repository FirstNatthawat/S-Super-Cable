<?php

class Message
{
    //------------- Properties
    private $ID_Message;
    private $Tittle_Message;
    private $Text_Message;
    private $Picture_Message;
    private $Date_Message;
    private const TABLE = "message";
	private $status;
	private $unread;


    //----------- Getters & Setters
	public function getUnread(): int
    {
        return $this->unread;
    }
    public function getStatus(): int
    {
        return $this->status;
    }
    // ---- id message
    public function getID_Message(): int
    {
        return $this->ID_Message;
    }

    public function setID_Message(int $ID_Message)
    {
        $this->getID_Message = $getID_Message;
    }

    // --- title message
    public function getTittle_Message(): string 
    {
        return $this->Tittle_Message;
    }

    public function setTittle_Message(string $Tittle_Message)
    {
        $this->getTittle_Message = $getTittle_Message;
    }

    // - text message
    public function getText_Message(): string 
    {
        return $this->Text_Message;
    }

    public function setText_Message(string $Text_Message)
    {
        $this->getText_Message = $getText_Message;
    }


    // --- picture message
    public function getPicture_Message(): string 
    {
        return $this->Picture_Message;
    }

    public function setPicture_Message(string $Picture_Message)
    {
        $this->getPicture_Message = $getPicture_Message;
    }

    // --- date message
    public function getDate_Message(): string 
    {
        return $this->Date_Message;
    }

    public function setDate_Message(string $Date_Message)
    {
        $this->getDate_Message = $getDate_Message;
    }


    //----------- CRUD
	//----------- CRUD
    public static function fetchCountAll(): array
    {
        $con = Db::getInstance();
        $query = "select count(*) from news_status where status =0 and ID_Employee = 's0001'";
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
        $query = "SELECT * FROM " . self::TABLE . " inner join news_status on message.ID_Message = news_status.ID_Message"." where news_status.ID_Employee = '".$emp_id."'";
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
        return Date("YmdHis") ;
    }

    public static function geneateDateTime()
    {
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
        # execute query
        if ($con->exec($query)) {
			$emp = new Employee();
			$result = $emp->findAll();
			foreach ($result as $prop => $val) {
				$emp_id = $val->getID_Employee();
				$con->exec("insert into news_status (ID_Employee, ID_Message) values('".$emp_id."',".$params['ID_Message'].")");
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
        $query = "UPDATE news_status SET status = 1 ";
        
        //$query = substr($query, 0, -1);
        $query .= " WHERE ID_Message = ".$ID_Message." and ID_Employee = '".$ID_Employee."'";
        
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
    

}
