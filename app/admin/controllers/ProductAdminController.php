<?php
    require_once __DIR__ .'/../../config/env.php';
    require_once __DIR__ . '/../models/ProductAdmin.php';

    class ProductAdminController {
        public function getAllProducts(int $categoryId)
        {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $sql = "SELECT p.Id, p.Title, p.Content, p.Img, p.Price, p.Rate, p.CreateAt, p.UpdateAt, 
                        c.Id AS CategoryId, c.Title AS CategoryTitle
                    FROM product p
                    JOIN category c ON p.CategoryId = c.Id";
            if ($categoryId > 0) {
                $sql .= " WHERE c.Id = " . intval($categoryId);
            }
            $sql .= " ORDER BY p.Id ASC";
            $result = $db->query($sql);

            $products = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product = new ProductAdmin();
                    $product->Id = $row['Id'];
                    $product->Title = $row['Title'];
                    $product->Content = $row['Content'];
                    $product->Img = $row['Img'];
                    $product->Price = $row['Price'];
                    $product->Rate = $row['Rate'];
                    $product->CreateAt = $row['CreateAt'];
                    $product->UpdateAt = $row['UpdateAt'];
                    $product->CategoryId = $row['CategoryId'];
                    $product->CategoryTitle = $row['CategoryTitle'];
                    $product->StoreId = '';
                    $product->StoreName = '';
                    $products[] = $product;
                }
            }

            $db->close();
            return $products;
        }

        public function getProductById($productId){
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $sql = "SELECT p.Id, p.Title, p.Content, p.Img, p.Price, p.Rate, p.CreateAt, p.UpdateAt, 
                        c.Id AS CategoryId, c.Title AS CategoryTitle
                    FROM product p
                    JOIN category c ON p.CategoryId = c.Id WHERE p.Id = " . (int)$productId;
            $result = $db->query($sql);
            $product = new ProductAdmin();
            if($result->num_rows > 0){
                while($row = $result->fetch_assoc()){
                    $product = new ProductAdmin();
                    $product->Id = $row['Id'];
                    $product->Title = $row['Title'];
                    $product->Content = $row['Content'];
                    $product->Img = $row['Img'];
                    $product->Price = $row['Price'];
                    $product->Rate = $row['Rate'];
                    $product->CreateAt = $row['CreateAt'];
                    $product->UpdateAt = $row['UpdateAt'];
                    $product->CategoryId = $row['CategoryId'];
                    $product->CategoryTitle = $row['CategoryTitle'];
                    $product->StoreId = '';
                    $product->StoreName = '';
                    $products[] = $product;
                }
            }
            $db->close();
            return $product;
        }

        public function addProduct($product){
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);

            $sql = "INSERT INTO product (Title, Content, Img, Price, Rate, CreateAt, CategoryId)
                    VALUES (?, ?, ?, ?, ?, NOW(), ?)";
            $stmt = $db->prepare($sql);
            $stmt->bind_param("sssisi",
                $product->Title,
                $product->Content,
                $product->Img,
                $product->Price,
                $product->Rate,
                $product->CategoryId);
            $isSuccess = $stmt->execute();
            $result = $isSuccess && ($stmt->affected_rows > 0);
            $stmt->close();
            $db->close();
            return $result;
        }

        public function updateProduct($product, $newImage = null) {
            global $hostname, $username, $password, $dbname, $port;
            $db = new mysqli($hostname, $username, $password, $dbname, $port);
            if ($newImage) {
                $sql = "UPDATE product SET Title=?, Content=?, Img=?, Price=?, Rate=?, UpdateAt=NOW(), CategoryId=? WHERE Id=?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("sssisii",
                    $product->Title,
                    $product->Content,
                    $newImage,
                    $product->Price,
                    $product->Rate,
                    $product->CategoryId,
                    $product->Id);
            } else {
                $sql = "UPDATE product SET Title=?, Content=?, Price=?, Rate=?, UpdateAt=NOW(), CategoryId=? WHERE Id=?";
                $stmt = $db->prepare($sql);
                $stmt->bind_param("ssisii",
                    $product->Title,
                    $product->Content,
                    $product->Price,
                    $product->Rate,
                    $product->CategoryId,
                    $product->Id);
            }
            $isSuccess = $stmt->execute();
            $result = $isSuccess && ($stmt->affected_rows > 0);
            $stmt->close();
            $db->close();
            return $result;
        }
    }
?>