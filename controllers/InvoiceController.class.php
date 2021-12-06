<?php


class InvoiceController
{
    /**
     * handleRequest จะทำการตรวจสอบ action และพารามิเตอร์ที่ส่งเข้ามาจาก Router
     * แล้วทำการเรียกใช้เมธอดที่เหมาะสมเพื่อประมวลผลแล้วส่งผลลัพธ์กลับ
     *
     * @param string $action ชื่อ action ที่ผู้ใช้ต้องการทำ
     * @param array $params พารามิเตอร์ที่ใช้เพื่อในการทำ action หนึ่งๆ
     */
    public function handleRequest(string $action = "index", array $params)
    {
        switch ($action) {
            case "index":
                $this->index();
                break;
            case "manage_invoice" :
                $this->$action();
                break;
            case "create_invoice" :
                $result = $this->$action($params["POST"]);
                echo $result;
                break;
            case "edit_invoice" :
                $ID_Invoice = isset($params["GET"]["ID_Invoice"]) ? $params["GET"]["ID_Invoice"] : "";
                $result = $this->$action($params["POST"], $ID_Invoice);
                echo $result;
                break;
            case "delete_invoice":
                $result = $this->$action($params["POST"]["ID_Invoice"]);
                echo $result;
                break;
            case "findbyID":
                $ID_Invoice = isset($params["POST"]["ID_Invoice"]) ? $params["POST"]["ID_Invoice"] : "";

                if (!empty($ID_Invoice)) {
                    $result = $this->$action($ID_Invoice);
                    echo $result;
                }
                break;
            case "download" :
                $ID_Invoice = $_GET["ID_Invoice"];
                if (!empty($ID_Invoice)) {
                    $result = $this->$action($ID_Invoice);
                    echo $result;
                }
                break;
            case "get_company":
                $ID_Company = isset($params["POST"]["ID_Company"]) ? $params["POST"]["ID_Company"] : "";

                if (!empty($ID_Company)) {
                    $result = $this->$action($ID_Company);
                    echo $result;
                }
                break;
            default:
                break;
        }
    }
    private function create_invoice($params)
    {
        session_start();
        # สร้างใบเสนอราคา
        $employee = $_SESSION["employee"];
        $emp_id = $employee->getID_Employee();
        $access_invoice = new Invoice();
        $invoice_result = $access_invoice->create_invoice([
            //'Invoice_No' => '9999',
            'Invoice_Date' => isset($params['Invoice_Date']) ? $params['Invoice_Date'] : '' ,
            'Credit_Term_Company' => $params['Credit_Term_Company'],
            'Name_Company' =>  isset($params['Name_Company']) ? $params['Name_Company'] : '' ,
            'Contact_Name_Company' =>  $params['Contact_Name_Company'],
            'Address_Company' =>  $params['Address_Company'],
            'PROVINCE_ID' => isset($params['PROVINCE_ID']) ? $params['PROVINCE_ID'] : '0',
            'AMPHUR_ID' => isset($params['AMPHUR_ID']) ? $params['AMPHUR_ID'] : '0',
            'Email_Company' => $params['Email_Company'],
            'Tel_Company' => $params['Tel_Company'],
            'Tax_Number_Company' => $params['Tax_Number_Company'],
            'Vat_Type' => isset($params['Vat_Type']) ? $params['Vat_Type'] : '0',
            'Percent_Vat' => isset($params['Percent_Vat']) ? $params['Percent_Vat'] : '0',
            'Vat' => isset($params['Vat']) ? $params['Vat'] : '0',
            'Discount' => isset($params['Discount']) ? $params['Discount'] : '0',
            'Total' => isset($params['Total']) ? $params['Total'] : '0',
            'Grand_Total' => isset($params['Grand_Total']) ? $params['Grand_Total'] : '0',
            'ID_Company' => isset($params['ID_Company']) ? $params['ID_Company'] : '0',
            'ID_Setting_Vat' => isset($params['ID_Setting_Vat']) ? $params['ID_Setting_Vat'] : '1',
            'Discount'  => isset($params['Discount']) ? $params['Discount'] : '0',
            'ID_Employee' => $emp_id
        ]);

        $discount  = isset($params['Discount']) ? $params['Discount'] : '0';
        $get_inv = Invoice::maxId();
        //print_r($get_inv);
        $inv_id = $get_inv->getID_Invoice();
        $qty = $params['qty_array'];
        $p_discout_price = $params['p_discout_price'];
        $access_invoice_detail = new Invoice_Detail();
        $Total = 0;
        $percent_Vat = isset($params['Percent_Vat']) ? $params['Percent_Vat'] : '0';
        $inc_vat = 0;
        if($invoice_result['status']==true){
            if(count($params['goods_array'])>0){
                foreach($params['goods_array'] as $key => $item){
                    $goods = Goods::findById($item);
                    $g_pricr = $goods->getPrice_Goods();
                    if($params['Vat_Type']=='include'){
                        $get_vat = $g_pricr*($percent_Vat/(100+$percent_Vat));
                        $g_pricr = number_format($g_pricr-$get_vat, 2,'.', '');
                        $inc_vat = $inc_vat+($get_vat*$qty[$key]);
                    }
                    $access_invoice_detail->create_invoice_detail([
                        'Name_Goods' => $goods->getName_Goods(),
                        'Quantity_Goods' => $qty[$key],
                        'Price_Goods' => $g_pricr,
                        'Total' => ($qty[$key]*$g_pricr)-$p_discout_price[$key],
                        'ID_Goods' => $item,
                        'ID_Invoice' => $inv_id,
                        'Discout_Price' => $p_discout_price[$key]
                    ]);
                    $Total = $Total+$qty[$key]*$goods->getPrice_Goods();
                }
            }

            $Total2 = $Total;
            $discount_price = 0;
            if($discount > 0){
                $discount_price = ($discount/100)*$Total;
                $Total = $Total - $discount_price;
            }

            $vat = 0;
            if($params['Vat_Type']=='exclude'){
                $vat = $Total*($percent_Vat/100);
                $GrandTotal = $Total+$vat;
            }else if($params['Vat_Type']=='include'){
                //$vat = $Total*($percent_Vat/(100+$percent_Vat));
                $vat =  $inc_vat;
                $Total = $Total-$vat;
                $GrandTotal = $Total+$vat;
            }else{
                $GrandTotal = $Total;
            }
            $invoice_result = $access_invoice->edit_invoice(
                [
                    'Vat' => $vat,
                    'Total' => $Total2,
                    'Discount_price' => $discount_price,
                    'Grand_Total' => $GrandTotal
                ], $inv_id
            );
        }
        return json_encode($invoice_result);
    }
    private function edit_invoice($params, $ID_Invoice)
    {
        # อัปเดตสินค้าส่งเสริมการขาย
        $access_invoice = new Invoice();
        $invoice_result = $access_invoice->edit_invoice(
            [
                'Invoice_No' => $params['Invoice_No'],
                'Invoice_Date' => isset($params['Invoice_Date']) ? $params['Invoice_Date'] : '2020-01-12',
                'Credit_Term_Company' => $params['Credit_Term_Company'],
                'Name_Company' =>  isset($params['Name_Company']) ? $params['Name_Company'] : '' ,
                'Contact_Name_Company' =>  $params['Contact_Name_Company'],
                'Address_Company' =>  $params['Address_Company'],
                'PROVINCE_ID' => isset($params['PROVINCE_ID']) ? $params['PROVINCE_ID'] : '0',
                'AMPHUR_ID' => isset($params['AMPHUR_ID']) ? $params['AMPHUR_ID'] : '0',
                'Email_Company' => $params['Email_Company'],
                'Tel_Company' => $params['Tel_Company'],
                'Tax_Number_Company' => $params['Tax_Number_Company'],
                'Vat_Type' => $params['Vat_Type'],
                'Percent_Vat' => isset($params['Percent_Vat']) ? $params['Percent_Vat'] : '',
                'Vat' => isset($params['Vat']) ? $params['Vat'] : '',
                'Discount' => isset($params['Discount']) ? $params['Discount'] : '',
                'Total' => isset($params['Total']) ? $params['Total'] : '',
                'Grand_Total' => isset($params['Grand_Total']) ? $params['Grand_Total'] : '',
                'ID_Company' => isset($params['ID_Company']) ? $params['ID_Company'] : '',
                'ID_Setting_Vat' => isset($params['ID_Setting_Vat']) ? $params['ID_Setting_Vat'] : '1'
            ], $ID_Invoice
        );

        $inv_id = $ID_Invoice;
        $qty = $params['qty_array'];
        $access_invoice_detail = new Invoice_Detail();
        //Invoice_Detail::delete_invoice_detail_by_inv($inv_id);
        $access_invoice_detail->delete_invoice_detail_by_inv($inv_id);
        $Total = 0;
        if($invoice_result['status']==true){
            if(count($params['goods_array'])>0){
                foreach($params['goods_array'] as $key => $item){
                    $goods = Goods::findById($item);
                    $access_invoice_detail->create_invoice_detail([
                        'Name_Goods' => $goods->getName_Goods(),
                        'Quantity_Goods' => $qty[$key],
                        'Price_Goods' => $goods->getPrice_Goods(),
                        'Total' => $qty[$key]*$goods->getPrice_Goods(),
                        'ID_Goods' => $item,
                        'ID_Invoice' => $inv_id
                    ]);
                    $Total = $Total+$qty[$key]*$goods->getPrice_Goods();
                }
            }
            $percent_Vat = isset($params['Percent_Vat']) ? $params['Percent_Vat'] : '0';
            $vat = $Total*($percent_Vat/100);
            $GrandTotal = $Total+$vat;
            $invoice_result = $access_invoice->edit_invoice(
                [
                    'Vat' => $vat,
                    'Total' => $Total,
                    'Grand_Total' => $GrandTotal
                ], $ID_Invoice
            );
        }
        echo json_encode($invoice_result);

    }
    private function delete_invoice($ID_Invoice)
    {
        # ลบใบเสนอราคา
        $access_invoice = new Invoice();
        $invoice_result = $access_invoice->delete_invoice(
            $ID_Invoice
        );
        return json_encode($invoice_result);
    }
    private function findbyID(string $ID_Invoice)
    {
        $invoice = Invoice::findById($ID_Invoice);//echo json_encode($sales);
        $invoice_detail = Invoice_Detail::findByInv($ID_Invoice);
        $invoice_arr = [];
        if(count($invoice_detail)>0){
            foreach($invoice_detail as $val){
                $invoice_arr[] = [
                    'ID_Invoice_Detail' => $val->getID_Invoice_Detail(),
                    'Name_Goods' => $val->getName_Goods(),
                    'Quantity_Goods' => $val->getQuantity_Goods(),
                    'Price_Goods' => $val->getPrice_Goods(),
                    'Total' => $val->getTotal(),
                    'ID_Goods' => $val->getID_Goods(),
                    'ID_Invoice' => $val->getID_Invoice()
                ];
            }
        }

        $data_sendback = array(
            "ID_Invoice" => $invoice->getID_Invoice(),
            "Invoice_No" => $invoice->getInvoice_No(),
            "Invoice_Date" => $invoice->getInvoice_Date(),
            "Credit_Term_Company" => $invoice->getCredit_Term_Company(),
            "Name_Company" => $invoice->getName_Company(),
            "Contact_Name_Company" => $invoice->getContact_Name_Company(),
            "Address_Company" => $invoice->getAddress_Company(),
            "PROVINCE_ID" => $invoice->getPROVINCE_ID(),
            "AMPHUR_ID" => $invoice->getAMPHUR_ID(),
            "Email_Company" => $invoice->getEmail_Company(),
            "Tel_Company" => $invoice->getTel_Company(),
            "Tax_Number_Company" => $invoice->getTax_Number_Company(),
            "Vat_Type" => $invoice->getVat_Type(),
            "Percent_Vat" => $invoice->getPercent_Vat(),
            "Vat" => $invoice->getVat(),
            "Discount" => $invoice->getDiscount(),
            "Total" => $invoice->getTotal(),
            "Grand_Total" => $invoice->getGrand_Total(),
            "ID_Company" => $invoice->getID_Company(),
            "ID_Setting_Vat" => $invoice->getID_Setting_Vat(),
            "invoice_detail" => $invoice_arr
        );
        echo json_encode(array("data" => $data_sendback));

    }
    private function error_handle(string $message)
    {
        $this->index($message);
    }

    // ควรมีสำหรับ controller ทุกตัว
    private function index($message = null)
    {
        include Router::getSourcePath() . "views/login.inc.php";

    }
    //หน้าจัดการใบเสนอราคา
    private function manage_invoice($params = null)
    {
        session_start();
        $employee = $_SESSION["employee"];
        $emp_id = $employee->getID_Employee();
        $emp_type = $employee->getUser_Status_Employee();
        if($emp_type=='Admin'){
            $invoiceList = Invoice::findAll();
        }else{
            $invoiceList = Invoice::findAllByUser($emp_id);
        }
        # retrieve data
        $company = Company::findAll();
        $provinceList = Province::findAll();
        $amphurList = Amphur::findAll();
        //$invoiceList = Invoice::findAll();
        $vat = Setting_Vat::findAll();
        $invoice_detailList = Invoice_Detail::findAll();
        $goodsList = Goods::findAll();
        if ($employee->getUser_Status_Employee() == "Admin") {
            include Router::getSourcePath() . "views/admin/manage_invoice.inc.php";
        } else if ($employee->getUser_Status_Employee() == "Sales") {   
            include Router::getSourcePath() . "views/admin/manage_invoice.inc.php";
        }
    }

    private function download(string $ID_Invoice)
    {
        $invoice = Invoice::findById($ID_Invoice);//echo json_encode($sales);
        $invoice_detail = Invoice_Detail::findByInv($ID_Invoice);
        $invoice_arr = [];
        if(count($invoice_detail)>0){
            foreach($invoice_detail as $val){
                $invoice_arr[] = [
                    'ID_Invoice_Detail' => $val->getID_Invoice_Detail(),
                    'Name_Goods' => $val->getName_Goods(),
                    'Quantity_Goods' => $val->getQuantity_Goods(),
                    'Price_Goods' => $val->getPrice_Goods(),
                    'Total' => $val->getTotal(),
                    'ID_Goods' => $val->getID_Goods(),
                    'ID_Invoice' => $val->getID_Invoice()
                ];
            }
        }

        
        $data_sendback = array(
            "ID_Invoice" => $invoice->getID_Invoice(),
            "Invoice_No" => $invoice->getInvoice_No(),
            "Invoice_Date" => $invoice->getInvoice_Date(),
            "Credit_Term_Company" => $invoice->getCredit_Term_Company(),
            "Name_Company" => $invoice->getName_Company(),
            "Contact_Name_Company" => $invoice->getContact_Name_Company(),
            "Address_Company" => $invoice->getAddress_Company(),
            "PROVINCE_ID" => $invoice->getPROVINCE_ID(),
            "AMPHUR_ID" => $invoice->getAMPHUR_ID(),
            "Email_Company" => $invoice->getEmail_Company(),
            "Tel_Company" => $invoice->getTel_Company(),
            "Tax_Number_Company" => $invoice->getTax_Number_Company(),
            "Vat_Type" => $invoice->getVat_Type(),
            "Percent_Vat" => $invoice->getPercent_Vat(),
            "Vat" => $invoice->getVat(),
            "Discount" => $invoice->getDiscount(),
            "Discount_price" => $invoice->getDiscount_price(),
            "Total" => $invoice->getTotal(),
            "Grand_Total" => $invoice->getGrand_Total(),
            "ID_Company" => $invoice->getID_Company(),
            "ID_Setting_Vat" => $invoice->getID_Setting_Vat(),
            "invoice_detail" => $invoice_arr
        );
        //echo json_encode(array("data" => $data_sendback));
        include "inv.php";
    }

    private function get_company(string $ID_Company)
    {
        $company = Company::findById($ID_Company);//echo json_encode($sales);
        $data_sendback = array(
            "ID_Company" => $company->getID_Company(),
            "Name_Company" => $company->getName_Company(),
            "Address_Company" => $company->getAddress_Company(),
            "PROVINCE_ID" => $company->getPROVINCE_ID(),
            "AMPHUR_ID" => $company->getAMPHUR_ID(),
            "Tel_Company" => $company->getTel_Company(),
            "Email_Company" => $company->getEmail_Company(),
            "Tax_Number_Company" => $company->getTax_Number_Company(),
            "Credit_Limit_Company" => $company->getCredit_Limit_Company(),
            "Credit_Term_Company" => $company->getCredit_Term_Company(),
            "Cluster_Shop_ID" => $company->getCluster_Shop_ID(),
            "Contact_Name_Company" => $company->getContact_Name_Company(),
            "IS_Blacklist" => $company->getIS_Blacklist(),
            "Cause_Blacklist" => $company->getCause_Blacklist(),

        );
        echo json_encode(array("data" => $data_sendback));
    }
}