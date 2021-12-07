<?php

class Invoice_Detail
{
    //------------- Properties
    private $ID_Invoice_Detail;
    private $Name_Goods;
    private $Quantity_Goods;
    private $Price_Goods;
    private $Total;
    private $ID_Goods;
    private $ID_Invoice;
    private $Discount_Price;
    private const TABLE = "invoice_detail";

    //----------- Getters & Setters
    public function getID_Invoice_Detail(): int
    {
        return $this->ID_Invoice_Detail;
    }
    public function setID_Invoice_Detail(int $ID_Invoice_Detail)
    {
        $this->ID_Invoice_Detail = $ID_Invoice_Detail;
    }
    public function getName_Goods(): string
    {
        return $this->Name_Goods;
    }
    public function setName_Goods(string $Name_Goods)
    {
        $this->Name_Goods = $Name_Goods;
    }
    public function getQuantity_Goods(): int
    {
        return $this->Quantity_Goods;
    }
    public function setQuantity_Goods(int $Quantity_Goods)
    {
        $this->Quantity_Goods = $Quantity_Goods;
    }
    public function getPrice_Goods(): float
    {
        return $this->Price_Goods;
    }
    public function setPrice_Goods(float $Price_Goods)
    {
        $this->Price_Goods = $Price_Goods;
    }
    public function getTotal(): float
    {
        return $this->Total;
    }
    public function setTotal(float $Total)
    {
        $this->Total = $Total;
    }
    public function getID_Goods(): int
    {
        return $this->ID_Goods;
    }
    public function setID_Goods(int $ID_Goods)
    {
        $this->ID_Goods = $ID_Goods;
    }
    public function getID_Invoice(): int
    {
        return $this->ID_Invoice;
    }
    public function setID_Invoice(int $ID_Invoice)
    {
        $this->ID_Invoice = $ID_Invoice;
    }
    public function getDiscount_Price(): float
    {
        return $this->Discount_Price;
    }

    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice_Detail");
        $stmt->execute();
        $invoice_detailList = array();
        while ($prod = $stmt->fetch()) {
            $invoice_detailList[$prod->getID_Invoice_Detail()] = $prod;
        }
        return $invoice_detailList;
    }
    public static function findByInv($ID_Invoice): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Invoice= '$ID_Invoice' ";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice_Detail");
        $stmt->execute();
        $invoice_detailList = array();
        while ($prod = $stmt->fetch()) {
            $invoice_detailList[] = $prod;
        }
        return $invoice_detailList;
    }
    public static function findById(string $ID_Invoice_Detail): ?Invoice_Detail
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Invoice_Detail = '$ID_Invoice_Detail'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice_Detail");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }
    # จัดการรายละเอียดใบเสนอราคา  ( เพิ่มรายละเอียดใบเสนอราคา )
    public function create_invoice_detail(array $params)
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
            $this->ID_Invoice_Detail = $con->lastInsertId();
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }
    }
    # แก้ไขรายละเอียดใบเสนอราคา
    public function edit_invoice_detail(array $params, string $ID_Invoice_Detail)
    {
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if (!empty($val)) {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Invoice_Detail = '" . $ID_Invoice_Detail . "'";
        //echo $query;exit();
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            return array("status" => false);
        }
    }
    # ลบรายละเอียดใบเสนอราคา
    public function delete_invoice_detail($ID_Invoice_Detail)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Invoice_Detail = '{$ID_Invoice_Detail}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }

    public function delete_invoice_detail_by_inv($ID_Invoice)
    {
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Invoice = '{$ID_Invoice}' ";
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }
}