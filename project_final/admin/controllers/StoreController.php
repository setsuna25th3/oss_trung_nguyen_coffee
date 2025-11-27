<?php
    include __DIR__ .'/../env.php';
    include __DIR__ .'/../models/Store.php';

    class StoreController {
        public function getAllStores(){
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $sql = "SELECT * FROM store";
            $result = $db->query($sql);

            $stores = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $store = new Store();
                    $store->Id = $row['Id'];
                    $store->StoreName = $row['StoreName'];
                    $store->Address = $row['Address'];
                    $store->Phone = $row['Phone'];
                    $store->OpenTime = $row['OpenTime'];
                    $store->CloseTime = $row['CloseTime'];
                    $stores[] = $store;
                }
            }

            $db->close();
            return $stores;
        }
    }
?>