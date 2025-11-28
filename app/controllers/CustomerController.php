<?php
    if (!class_exists('CustomerController')) {
        require_once __DIR__ .'/../env.php';
        require_once __DIR__ .'/../models/CustomerItem.php';

        class CustomerController {
            public function getCustomerByEmail($email){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);

                $sql = "SELECT * FROM customer WHERE Email = " . $email;

                $result = $db->query($sql);

                $customer = new Customer();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $customer->Id = $row['Id'];
                    $customer->FirstName = $row['FirstName'];
                    $customer->LastName = $row['LastName'];
                    $customer->Address = $row['Address'];
                    $customer->Phone = $row['Phone'];
                    $customer->Email = $row['Email'];
                    $customer->Img = $row['Img'];
                    $customer->RegisteredAt = $row['RegisteredAt'];
                    $customer->UpdateAt = $row['UpdateAt'];
                    $customer->DateOfBirth = $row['DateOfBirth'];
                    $customer->Password = $row['Password'];
                    $customer->RandomKey = $row['RandomKey'];
                    $customer->IsActive = $row['IsActive'];
                    $customer->Role = $row['Role'];
                }

                $db->close();
                return $customer;
            }

            public function getCustomerById(int $id){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);

                $sql = "SELECT * FROM customer WHERE Id = " . $id;
                $result = $db->query($sql);

                $customer = new Customer();
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $customer->Id = $row['Id'];
                    $customer->FirstName = $row['FirstName'];
                    $customer->LastName = $row['LastName'];
                    $customer->Address = $row['Address'];
                    $customer->Phone = $row['Phone'];
                    $customer->Email = $row['Email'];
                    $customer->Img = $row['Img'];
                    $customer->RegisteredAt = $row['RegisteredAt'];
                    $customer->UpdateAt = $row['UpdateAt'];
                    $customer->DateOfBirth = $row['DateOfBirth'];
                    $customer->Password = $row['Password'];
                    $customer->RandomKey = $row['RandomKey'];
                    $customer->IsActive = $row['IsActive'];
                    $customer->Role = $row['Role'];
                }

                $db->close();
                return $customer;
            }

            public function SignUp($customer) {
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
            }

            public function UpdateCustomer($customer) {
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
            }
        }
    }
?>