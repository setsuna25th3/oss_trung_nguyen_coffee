<?php
    if(!class_exists('ReviewController')){
        require_once __DIR__ ."/../config/env.php";
        require_once __DIR__ . "/../models/Review.php";

        class ReviewController{
            public function getReviewByProductId($productId){
                global $hostname, $username, $password, $dbname, $port;
                $db = new mysqli($hostname, $username, $password, $dbname, $port);
                $sql = "SELECT * FROM productreview WHERE ProductId = " . $productId;

                $result = $db->query($sql);
                $reviews = [];
                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        $review = new Review();
                        $review->Id = $row['Id'];
                        $review->ProductId = $row['ProductId'];
                        $review->CustomerId = $row['CustomerId'];
                        $review->Rate = $row['Rate'];
                        $review->Comment = $row['Comment'];
                        $review->CreatedAt = $row['CreatedAt'];
                        $reviews[] = $review;
                    }
                    $db->close();
                    return $reviews;
                }
            }
        }
    }
?>