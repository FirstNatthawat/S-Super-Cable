<?php

class Message_Image
{
    //------------- Properties
    private $ID_Message;
    private $Image_name;
    private const TABLE = "message_image";



    //----------- Getters & Setters
	public function getID_Message(): int
    {
        return $this->ID_Message;
    }
    public function setID_Message(int $ID_Message)
    {
        $this->$ID_Message = $ID_Message;
    }
    public function getImage_name(): string
    {
        return $this->Image_name;
    }
    public function setImage_name(int $Image_name)
    {
        $this->$Image_name = $Image_name;
    }
   
    //----------- CRUD

    // save data to
    // insert data into server.
    public static function create_images($params)
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
//        print_r($query);
//        exit();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
       
    }

    public static function update_images($params)
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
    
    public static function get_images($ID_Message): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE." WHERE ID_Message='".$ID_Message."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Message_Image");
        $stmt->execute();
        $list = array();
        while ($prod = $stmt->fetch()) {
            $list[] = $prod;
        }
        return $list;

    }

    public static function delete_images($Image_name)
    {
        $con = Db::getInstance();
        $values = "";
        $columns = "";
        $query = "DELETE FROM message_image WHERE Image_name='".$Image_name."'";
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
       
    }

}

