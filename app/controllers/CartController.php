<?php
if (!class_exists('CartController')) {
    require_once __DIR__ . '/../env.php';
    require_once __DIR__ . '/../models/CartItem.php';

    class CartController
    {
        public function getCartByCustomerId($customerId, $storeId)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);
            $sql = "SELECT * FROM cart WHERE CustomerId = " . (int)$customerId;
            if ($storeId) {
                $sql .= " AND StoreId = " . (int)$storeId;
            }
            $result = $db->query($sql);
            $cartItems = [];
            while ($row = $result->fetch_assoc()) {
                $cartItem = new CartItem();
                $cartItem->Id = $row['Id'];
                $cartItem->CustomerId = $customerId;
                $cartItem->ProductId = $row['ProductId'];
                $cartItem->StoreId = $row['StoreId'];
                $cartItem->Quantity = $row['Quantity'];
                $cartItem->CreatedAt = $row['CreatedAt'];
                $cartItem->isSelected = false;
                $cartItems[] = $cartItem;
            }
            $db->close();
            return $cartItems;
        }

        public function addToCart($customerId, $productId, $storeId, $quantity)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $cartItems = $this->getCartByCustomerId($customerId, $storeId);
            foreach ($cartItems as $item) {
                if ($item->ProductId == $productId && $item->StoreId == $storeId) {
                    $newQuantity = $item->Quantity + $quantity;
                    $sql = "UPDATE cart SET Quantity = ? WHERE CustomerId = ? AND ProductId = ? AND StoreId = ?";
                    $stmt = $db->prepare($sql);
                    $stmt->bind_param("iiii", $newQuantity, $customerId, $productId, $storeId);
                    $result = $stmt->execute();
                    return $result && ($stmt->affected_rows > 0);
                }
            }
            $sql = "INSERT INTO cart (CustomerId, ProductId, StoreId, Quantity, CreatedAt) VALUES (?, ?, ?, ?, NOW())";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iiii", $customerId, $productId, $storeId, $quantity);
            $result = $stmt->execute();
            return $result && ($stmt->affected_rows > 0);
        }

        public function getTotal($customerId)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);
            $sql = "SELECT * FROM cart WHERE CustomerId = " . (int)$customerId;
            $result = $db->query($sql);
            $total = 0;
            while ($row = $result->fetch_assoc()) {
                $total += (int)$row['Quantity'];
            }
            $db->close();
            return $total;
        }

        public function checkOut($customerId, $storeId)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);
            $carts = $this->getCartByCustomerId($customerId, $storeId);
            $total = 0;
            foreach ($carts as $cart) {
                if ($cart->isSelected) {
                    $product = (new ProductController())->getProductById($cart->ProductId);
                    $total += $product->Price * $cart->Quantity;
                    $sql = "DELETE FROM cart WHERE Id = " . (int)$cart->Id;
                    $db->query($sql);
                }
            }
        }


        public function deleteItemInCart($customerId, $productId, $storeId)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);
            $sql = "DELETE FROM cart WHERE CustomerId = ? AND ProductId =  ? AND StoreId = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iii", $customerId, $productId, $storeId);
            $result = $stmt->execute();
            $db->close();
            return $result && ($stmt->affected_rows > 0);
        }

        public function updateQuantity($customerId, $productId, $storeId, $quantity)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $sql = "UPDATE cart SET Quantity = ? WHERE CustomerId = ? AND ProductId = ? AND StoreId = ?";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("iiii", $quantity, $customerId, $productId, $storeId);
            $result = $stmt->execute();

            $db->close();
            return $result && ($stmt->affected_rows > 0);
        }
        public function removeFromCart($customerId, $productId, $storeId)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $stmt = $db->prepare("DELETE FROM cart WHERE CustomerId=? AND ProductId=? AND StoreId=?");
            $stmt->bind_param("iii", $customerId, $productId, $storeId);
            $stmt->execute();
            $stmt->close();
            $db->close();

            return true;
        }
    }
}
