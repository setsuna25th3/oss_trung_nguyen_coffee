<?php
    if(!class_exists('PaymentController')){
        require_once __DIR__ . '/../config/env.php';
        require_once __DIR__ . '/../models/Payment.php';
        class PaymentController{
            public function getPaymentByCustomerId(int $customerId, int $storeId){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
                $sql = "SELECT * FROM payment WHERE CustomerId = " . $customerId;
                if($storeId){
                    $sql .= " AND StoreId = " . $storeId;
                }
                $result = $db->query($sql);
                $payments = [];
                if($result->num_rows > 0){
                   while($row = $result->fetch_assoc()){
                    $payment = new Payment();
                    $payment->Id = $row['Id'];
                    $payment->CustomerId = $row['CustomerId'];
                    $payment->StoreId = $row['StoreId'];
                    $payment->Total = $row['Total'];
                    $payment->Carrier = $row['Carrier'];
                    $payment->TrackingCode = $row['TrackingCode'];
                    $payment->Status = $row['Status'];
                    $payment->CreatedAt = $row['CreatedAt'];
                    $payments[] = $payment;
                   }
                }
                $db->close();
                return $payments;
            }
        }
    }
?>