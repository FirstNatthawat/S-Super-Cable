<?php
class Promotion
{
    //------------- Properties
    private $ID_Promotion;
    private $Name_Promotion;
    private $Unit_Promotion;
    private $Price_Unit_Promotion;
    private $Have_To_Return;
    private const TABLE = "promotion";

    //----------- Getters & Setters
    public function getID_Promotion(): int
    {
        return $this->ID_Promotion;
    }

    public function setID_Promotion(int $ID_Promotion)
    {
        $this->ID_Promotion = $ID_Promotion;
    }

    public function getName_Promotion() : string
    {
        return $this->Name_Promotion;
    }

    public function setName_Promotion(string $Name_Promotion)
    {
        $this->Name_Promotion = $Name_Promotion;
    }

    public function getUnit_Promotion() : int
    {
        return $this->Unit_Promotion;
    }

    public function setUnit_Promotion(int $Unit_Promotion)
    {
        $this->Unit_Promotion = $Unit_Promotion;
    }

    public function getPrice_Unit_Promotion() : float
    {
        return $this->Price_Unit_Promotion;
    }

    public function setPrice_Unit_Promotion(float $Price_Unit_Promotion)
    {
        $this->Price_Unit_Promotion = $Price_Unit_Promotion;
    }

    public function getHave_To_Return() : int
    {
        return $this->Have_To_Return;
    }

    public function setHave_To_Return(int $Have_To_Return)
    {
        $this->Have_To_Return = $Have_To_Return;
    }
    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Promotion");
        $stmt->execute();
        $promotionList = array();
        while ($prod = $stmt->fetch()) {
            $promotionList[$prod->getID_Promotion()] = $prod;
        }
        return $promotionList;
    }
    public static function findById(string $ID_Promotion): ?Promotion
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Promotion = '$ID_Promotion'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Promotion");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }
    # จัดการสินค้าส่งเสริมการขาย  ( เพิ่มสินค้าส่งเสริมการขาย )
    public function create_promotion(array $params)
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
    # จัดการสินค้าส่งเสริมการขาย  ( เพิ่มสินค้าส่งเสริมการขาย excel )
    public function create_promotion_at_once(array $params)
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
    # แก้ไขสินค้าส่งเสริมการขาย
    public function edit_promotion(array $params, string $ID_Promotion)
    {
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if (!empty($val)) {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Promotion = '" . $ID_Promotion . "'";
        //echo $query;exit();
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }
    # ลบสินค้าส่งเสริมการขาย
    public function delete_promotion($ID_Promotion)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Promotion = '{$ID_Promotion}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }

    public static function listArray(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Promotion");
        $stmt->execute();
        $promotionList = array();
        while ($prod = $stmt->fetch()) {
            $promotionList[] = $prod;
        }
        return $promotionList;
    }
}
?>