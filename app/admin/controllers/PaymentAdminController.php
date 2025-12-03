<?php
require_once __DIR__ . '/../../config/env.php';
require_once __DIR__ . '/../models/PaymentAdmin.php';

class PaymentAdminController
{
    public function getAllPayments()
    {
        global $hostname, $username, $password, $dbname, $port;
        $db = new mysqli($hostname, $username, $password, $dbname, $port);
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $sql = "SELECT * FROM payment";
        $result = $db->query($sql);

        $payments = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $payment = new PaymentAdmin();
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

    public function getPaymentById($id)
    {
        global $hostname, $username, $password, $dbname, $port;
        $db = new mysqli($hostname, $username, $password, $dbname, $port);
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $stmt = $db->prepare("SELECT * FROM payment WHERE Id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        $payment = null;
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $payment = new PaymentAdmin();
            $payment->Id = $row['Id'];
            $payment->CustomerId = $row['CustomerId'];
            $payment->StoreId = $row['StoreId'];
            $payment->Total = $row['Total'];
            $payment->Carrier = $row['Carrier'];
            $payment->TrackingCode = $row['TrackingCode'];
            $payment->Status = $row['Status'];
            $payment->CreatedAt = $row['CreatedAt'];
        }

        $stmt->close();
        $db->close();
        return $payment;
    }

    public function updatePaymentStatus($paymentId, $status)
    {
        global $hostname, $username, $password, $dbname, $port;
        $db = new mysqli($hostname, $username, $password, $dbname, $port);
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $stmt = $db->prepare("UPDATE payment SET Status = ? WHERE Id = ?");
        $stmt->bind_param("si", $status, $paymentId);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    }

    public function deletePayment($paymentId)
    {
        global $hostname, $username, $password, $dbname, $port;
        $db = new mysqli($hostname, $username, $password, $dbname, $port);
        if ($db->connect_error) {
            die("Connection failed: " . $db->connect_error);
        }

        $stmt = $db->prepare("DELETE FROM payment WHERE Id = ?");
        $stmt->bind_param("i", $paymentId);
        $result = $stmt->execute();
        $stmt->close();
        $db->close();
        return $result;
    }
}
