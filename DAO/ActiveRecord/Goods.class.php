<?php

class Goods
{

    //------------- Properties
    private $ID_Goods;
    private $Name_Goods;
    private $Detail_Goods;
    private $Price_Goods;
    private const TABLE = "goods";

    //----------- Getters & Setters
    public function getID_Goods(): int
    {
        return $this->ID_Goods;
    }

    public function setID_Goods(int $ID_Goods)
    {
        $this->ID_Goods = $ID_Goods;
    }

    public function getName_Goods() : string
    {
        return $this->Name_Goods;
    }

    public function setName_Goods(string $Name_Goods)
    {
        $this->Name_Goods = $Name_Goods;
    }

    public function getDetail_Goods() : string
    {
        if ($this->Detail_Goods == null)
            return "-";
        else
            return $this->Detail_Goods;
    }

    public function setDetail_Goods(string $Detail_Goods)
    {
        $this->Detail_Goods = $Detail_Goods;
    }

    public function getPrice_Goods() : float
    {
        return $this->Price_Goods;
    }

    public function setPrice_Goods(float $Price_Goods)
    {
        $this->Price_Goods = $Price_Goods;
    }
    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Goods");
        $stmt->execute();
        $goodsList = array();
        while ($prod = $stmt->fetch()) {
            $goodsList[$prod->getID_Goods()] = $prod;
        }
        return $goodsList;
    }
    public static function findById(string $ID_Goods): ?Goods
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Goods = '$ID_Goods'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Goods");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }
    # จัดการสินค้า  ( เพิ่มสินค้า )
    public function create_goods(array $params)
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
        # execute query
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }
    }
    # จัดการสินค้า  ( เพิ่มสินค้า excel )
    public function create_goods_at_once(array $params)
    {
        $con = Db::getInstance();
        // turn off auto commit (ปิดคำสั่งสำหรับการยืนยันการเปลี่ยนแปลงข้อมูลที่เกิดขึ้น)
        $con->beginTransaction();
        foreach ($params as $k => $v) {
            $values = "";
            $columns = "";
            foreach ($v as $prop => $val) {
                # ถ้า column แรกไม่ต้องเติมลูกน้ำ คอลัมน์อื่นเติมลูกน้ำ ..
                $columns = empty($columns) ? $columns .= $prop : $columns .= "," . $prop;
                $values .= "'$val',";
            }
            # insert ลง db
            $values = substr($values, 0, -1);
            $query = "INSERT INTO " . self::TABLE . "({$columns}) VALUES ($values)";
            //echo $query;exit();
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
    # แก้ไขสินค้า
    public function edit_goods(array $params, string $ID_Goods)
    {
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if (!empty($val)) {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Goods = '" . $ID_Goods . "'";
        //echo $query;exit();
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }

    # ลบสินค้า
    public function delete_goods($ID_Goods)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Goods = '{$ID_Goods}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }
    public function export_excel(string $page)
    {

    }
    public static function chkData(string $ID_Goods)
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM Invoice_Detail WHERE ID_Goods = '$ID_Goods'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice_Detail");
        $stmt->execute();
        $goods = [];
        while ($prod = $stmt->fetch()) {
            $goods[] = $prod;
        }
        if(count($goods)=='0'){
            return true;
        }else{
            return false;
        }
    }

}
?>