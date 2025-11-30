<?php
class Customer {
    public $Id;
    public $FirstName;
    public $LastName;
    public $Address;
    public $Phone;
    public $Email;
    public $Img;
    public $RegisteredAt;
    public $UpdateAt;
    public $DateOfBirth;
    public $Password;
    public $RandomKey;
    public $RandomKeyExpiresAt; // Thuộc tính mới
    public $IsActive;
    public $Role;

    public function __construct(array $data = []) {
        if ($data) {
            $this->Id = $data['Id'] ?? null;
            $this->FirstName = $data['FirstName'] ?? '';
            $this->LastName = $data['LastName'] ?? '';
            $this->Address = $data['Address'] ?? null;
            $this->Phone = $data['Phone'] ?? null;
            $this->Email = $data['Email'] ?? '';
            $this->Img = $data['Img'] ?? null;
            $this->RegisteredAt = $data['RegisteredAt'] ?? null;
            $this->UpdateAt = $data['UpdateAt'] ?? null;
            $this->DateOfBirth = $data['DateOfBirth'] ?? null;
            $this->Password = $data['Password'] ?? '';
            $this->RandomKey = $data['RandomKey'] ?? null;
            $this->RandomKeyExpiresAt = $data['RandomKeyExpiresAt'] ?? null; // Khởi tạo
            $this->IsActive = $data['IsActive'] ?? 1;
            $this->Role = $data['Role'] ?? 0;
        }
    }
}
//?>