<?php

class Invoice
{
    //------------- Properties
    private $ID_Invoice;
    private $Invoice_No;
    private $Invoice_Date;
    private $Credit_Term_Company;
    private $Name_Company;
    private $Contact_Name_Company;
    private $Address_Company;
    private $PROVINCE_ID;
    private $AMPHUR_ID;
    private $Email_Company;
    private $Tel_Company;
    private $Tax_Number_Company;
    private $Vat_Type;
    private $Percent_Vat;
    private $Vat;
    private $Total;
    private $Grand_Total;
    private $ID_Company;
    private $ID_Setting_Vat;
    private const TABLE = "invoice";

    //----------- Getters & Setters
    public function getID_Invoice(): int
    {
        return $this->ID_Invoice;
    }
    public function setID_Invoice(int $ID_Invoice)
    {
        $this->ID_Invoice = $ID_Invoice;
    }
    public function getInvoice_No(): string
    {
        if ($this->Invoice_No == null)
            return "-";
        else
            return $this->Invoice_No;
    }
    public function setInvoice_No(string $Invoice_No)
    {
        $this->Invoice_No = $Invoice_No;
    }
    public function getInvoice_Date(): string
    {
        return $this->Invoice_Date;
    }
    public function setInvoice_Date(string $Invoice_Date)
    {
        $this->Invoice_Date = $Invoice_Date;
    }
    public function getCredit_Term_Company(): string
    {
        return $this->Credit_Term_Company;
    }
    public function setCredit_Term_Company(string $Credit_Term_Company)
    {
        $this->Credit_Term_Company = $Credit_Term_Company;
    }
    public function getName_Company(): string
    {
        return $this->Name_Company;
    }
    public function setName_Company(string $Name_Company)
    {
        $this->Name_Company = $Name_Company;
    }
    public function getContact_Name_Company(): string
    {
        if ($this->Contact_Name_Company == null)
            return "-";
        else
            return $this->Contact_Name_Company;
    }
    public function setContact_Name_Company(string $Contact_Name_Company)
    {
        $this->Contact_Name_Company = $Contact_Name_Company;
    }
    public function getAddress_Company(): string
    {
        return $this->Address_Company;
    }
    public function setAddress_Company(string $Address_Company)
    {
        $this->Address_Company = $Address_Company;
    }
    public function getPROVINCE_ID(): int
    {
        if ($this->PROVINCE_ID == null)
            return "-";
        else
            return $this->PROVINCE_ID;
    }
    public function setPROVINCE_ID(int $PROVINCE_ID)
    {
        $this->PROVINCE_ID = $PROVINCE_ID;
    }
    public function getAMPHUR_ID(): string
    {
        if ($this->AMPHUR_ID == null)
            return "-";
        else
            return $this->AMPHUR_ID;
    }
    public function setAMPHUR_ID(string $AMPHUR_ID)
    {
        $this->AMPHUR_ID = $AMPHUR_ID;
    }
    public function getEmail_Company(): string
    {
        return $this->Email_Company;
    }
    public function setEmail_Company(string $Email_Company)
    {
        $this->Email_Company = $Email_Company;
    }
    public function getTel_Company(): string
    {
        return $this->Tel_Company;
    }
    public function setTel_Company(string $Tel_Company)
    {
        $this->Tel_Company = $Tel_Company;
    }
    public function getTax_Number_Company(): string
    {
        return $this->Tax_Number_Company;
    }
    public function setTax_Number_Company(string $Tax_Number_Company)
    {
        $this->Tax_Number_Company = $Tax_Number_Company;
    }
    public function getVat_Type(): string
    {
        return $this->Vat_Type;
    }
    public function setVat_Type(string $Vat_Type)
    {
        $this->Vat_Type = $Vat_Type;
    }
    public function getPercent_Vat(): int
    {
        return $this->Percent_Vat;
    }
    public function setPercent_Vat(int $Percent_Vat)
    {
        $this->Percent_Vat = $Percent_Vat;
    }
    public function getVat(): float
    {
        return $this->Vat;
    }
    public function setVat(float $Vat)
    {
        $this->Vat = $Vat;
    }

    public function getTotal(): float
    {
        return $this->Total;
    }
    public function setTotal(float $Total)
    {
        $this->Total = $Total;
    }
    public function getGrand_Total(): float
    {
        return $this->Grand_Total;
    }
    public function setGrand_Total(float $Grand_Total)
    {
        $this->Grand_Total = $Grand_Total;
    }
    public function getID_Company(): int
    {
        return $this->ID_Company;
    }
    public function setID_Company(int $ID_Company)
    {
        $this->ID_Company = $ID_Company;
    }
    public function getID_Setting_Vat(): int
    {
        return $this->ID_Setting_Vat;
    }
    public function setID_Setting_Vat(int $ID_Setting_Vat)
    {
        $this->ID_Setting_Vat = $ID_Setting_Vat;
    }


    //----------- CRUD
    public static function findAll(): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE;
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice");
        $stmt->execute();
        $invoiceList = array();
        while ($prod = $stmt->fetch()) {
            $invoiceList[$prod->getID_Invoice()] = $prod;
        }
        return $invoiceList;
    }
    public static function findById(string $ID_Invoice): ?Invoice
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " WHERE ID_Invoice = '$ID_Invoice'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }
    # จัดการใบเสนอราคา  ( เพิ่มใบเสนอราคา )
    public function create_invoice(array $params)
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
//        echo $query;
//        exit;
        //return $query;
        # execute query
        if ($con->exec($query)) {
            $this->ID_Invoice = $con->lastInsertId();
            return array("status" => true);
        } else {
            $message = "มีบางอย่างผิดพลาด , กรุณาตรวจสอบข้อมูล ";
            return array("status" => false, "message" => $message);
        }
    }
    # แก้ไขใบเสนอราคา
    public function edit_invoice(array $params, string $ID_Invoice)
    {
        $query = "UPDATE " . self::TABLE . " SET ";
        foreach ($params as $prop => $val) {
            if (!empty($val)) {
                $query .= " $prop='$val',";
            }
        }
        $query = substr($query, 0, -1);
        $query .= " WHERE ID_Invoice = '" . $ID_Invoice . "'";
        //echo $query;
        //exit;
        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {

            //return array("status" => false);
            return array("status" => true);
        }
    }
    # ลบใบเสนอราคา
    public function delete_invoice($ID_Invoice)
    {
        $query = "DELETE FROM  invoice_detail WHERE ID_Invoice = '{$ID_Invoice}' ";
        $con = Db::getInstance();
        $con->exec($query);
        $query = "DELETE FROM " . self::TABLE . " WHERE ID_Invoice = '{$ID_Invoice}' ";

        $con = Db::getInstance();
        if ($con->exec($query)) {
            return array("status" => true);
        } else {
            return array("status" => false);
        }
    }
    # ดึง Id ล่าสุด
    public static function maxId(): ?Invoice
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE . " ORDER BY ID_Invoice DESC LIMIT 1";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice");
        $stmt->execute();
        if ($prod = $stmt->fetch()) {
            return $prod;
        }
        return null;
    }

    public static function findAllByUser($emp_id): array
    {
        $con = Db::getInstance();
        $query = "SELECT * FROM " . self::TABLE. " WHERE ID_Employee='".$emp_id."'";
        $stmt = $con->prepare($query);
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Invoice");
        $stmt->execute();
        $invoiceList = array();
        while ($prod = $stmt->fetch()) {
            $invoiceList[$prod->getID_Invoice()] = $prod;
        }
        return $invoiceList;
    }

}