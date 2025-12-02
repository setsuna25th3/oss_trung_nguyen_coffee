<?php

require_once __DIR__ . '/../../env.php';
require_once __DIR__ . '/../models/ProductAdmin.php';

class ProductAdminController
{
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
}
