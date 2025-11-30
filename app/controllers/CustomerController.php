<?php
    if (!class_exists('CustomerController')) {
        require_once __DIR__ .'/../env.php';
        require_once __DIR__ .'/../models/Customer.php';

        class CustomerController {
            public function getCustomerByEmail($email){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, '', $dbname, $port);

                $sql = "SELECT * FROM customer WHERE Email = '" . $email . "'";

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

            public function getCustomerById($id){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);

                $sql = "SELECT * FROM customer WHERE Id = " . (int)$id;
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

            public function checkDuplicateByEmail($customer){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
                $sql = "SELECT * FROM customer WHERE Email = '" . $customer->Email . "'";
                $result = $db->query($sql);
                return $result->num_rows;
            }

            public function checkDuplicateByPhone($customer){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
                $sql = "SELECT * FROM customer WHERE Phone = '" . $customer->Phone . "'";
                $result = $db->query($sql);
                return $result->num_rows;
            }

            public function signUp($customer) {
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
                $sql = "INSERT INTO customer (FirstName, LastName, Address, Phone, Email, Img, RegisteredAt, DateOfBirth, Password, RandomKey, IsActive, Role)
                        VALUES (?, ?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?, 0)";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ssssssdssi", 
                    $customer->FirstName,
                    $customer->LastName,
                    $customer->Address,
                    $customer->Phone,
                    $customer->Email,
                    $customer->Img,
                    $customer->DateOfBirth,
                    $customer->Password,
                    $customer->RandomKey,
                    $customer->IsActive
                );
                $result = $stmt->execute();
                return $result && ($stmt->affected_rows > 0);
            }

            public function updateCustomer($customer) {
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
                $sql = "UPDATE customer SET 
                            FirstName = ?,
                            LastName = ?,
                            Address = ?,
                            Phone = ?,
                            Email = ?,
                            Img = ?,
                            UpdateAt = NOW(),
                            DateOfBirth = ?,
                            Password = ?,
                            RandomKey = ?,
                            IsActive = ?,
                        WHERE Id = ?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ssssssssssi",
                    $customer->FirstName,
                    $customer->LastName,
                    $customer->Address,
                    $customer->Phone,
                    $customer->Email,
                    $customer->Img,
                    $customer->DateOfBirth,
                    $customer->Password,
                    $customer->RandomKey,
                    $customer->IsActive,
                    $customer->Id
                );
                $result = $stmt->execute();
                return $result && ($stmt->affected_rows > 0);
            }
        }
    }
?>