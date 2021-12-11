<?php

class File
{
    //------------- Properties
    private $ID_File;
    private $Name_File;
    private $Path_File;
    private $Detail_File;
    private $Date_Upload_File;
    private const TABLE = "file";

    //----------- Getters & Setters
    public function getID_File(): int
    {
        return $this->ID_File;
    }
    public function setID_File(int $ID_File)
    {
        $this->ID_File = $ID_File;
    }
    public function getName_File(): string
    {
        return $this->Name_File;
    }
    public function setName_File(string $Name_File)
    {
        $this->Name_File = $Name_File;
    }
    public function getPath_File(): string
    {
        return $this->Path_File;
    }
    public function setPath_File(string $Path_File)
    {
        $this->Path_File = $Path_File;
    }
    public function getDetail_File(): string
    {
        if ($this->Detail_File == null)
            return "-";
        else
            return $this->Detail_File;
    }
    public function setDetail_File(string $Detail_File)
    {
        $this->Detail_File = $Detail_File;
    }
    public function getDate_Upload_File(): string
    {
        date_default_timezone_set('Asia/Bangkok');
        return $this->Date_Upload_File;
    }
    public function setDate_Upload_File(string $Date_Upload_File)
    {
        date_default_timezone_set('Asia/Bangkok');
        $this->Date_Upload_File = $Date_Upload_File;
    }
    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "File");
        $stmt->execute();
        $fileList = array();
        while ($prod = $stmt->fetch()) {
            $fileList[$prod->getID_File()] = $prod;
        }
        return $fileList;
    }
    public static function findById(int $ID_File): ?File
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_File = '$ID_File'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "File");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }
    # จัดการไฟล์  ( เพิ่มไฟล์ )
    public function create_file(array $params)
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
    # แก้ไขไฟล์
    public function edit_file(array $params)
    {
        $ID_File = $params['ID_File'];
        $Name_File = $params['Name_File'];
        $Path_File = $params['Path_File'];
        $Detail_File = $params['Detail_File'];


        $query = "UPDATE " . self::TABLE . " SET " ;
        foreach ($params as $prop => $val) {
            if($val != '') {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_File = '" . $ID_File . "'";
        
        //print_r($query);
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }
    # ลบไฟล์
    public function delete_file($ID_File)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_File = '{$ID_File}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }
}
?>