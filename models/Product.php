<?php
class Product {
    public $Id;
    public $Title;
    public $Content;
    public $Img;
    public $Price;
    public $Rate;
    public $CreateAt;
    public $UpdateAt;
    public $CategoryId;

    public function __construct(array $data = []) {
        if ($data) {
            $this->Id = $data['Id'] ?? null;
            $this->Title = $data['Title'] ?? '';
            $this->Content = $data['Content'] ?? null;
            $this->Img = $data['Img'] ?? null;
            $this->Price = $data['Price'] ?? 0.00;
            $this->Rate = $data['Rate'] ?? null;
            $this->CreateAt = $data['CreateAt'] ?? null;
            $this->UpdateAt = $data['UpdateAt'] ?? null;
            $this->CategoryId = $data['CategoryId'] ?? null;
        }
    }
    public function getCategory() {
        return null; 
    }
}
?>