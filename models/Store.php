<?php
class Store {
    public $Id;
    public $StoreName;
    public $Address;
    public $Phone;
    public $OpenTime;
    public $CloseTime;

    public function __construct(array $data = []) {
        if ($data) {
            $this->Id = $data['Id'] ?? null;
            $this->StoreName = $data['StoreName'] ?? '';
            $this->Address = $data['Address'] ?? null;
            $this->Phone = $data['Phone'] ?? null;
            $this->OpenTime = $data['OpenTime'] ?? null;
            $this->CloseTime = $data['CloseTime'] ?? null;
        }
    }

    public function isProductAvailable(int $productId): bool {
        return true; 
    }
}
?>