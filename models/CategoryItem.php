<?php
class CategoryItem {
    public $Id;
    public $Title;
    public $Content;
    public $CreateAt;
    public $UpdateAt;

    public function __construct(array $data = []) {
        if ($data) {
            $this->Id = $data['Id'] ?? null;
            $this->Title = $data['Title'] ?? '';
            $this->Content = $data['Content'] ?? null;
            $this->CreateAt = $data['CreateAt'] ?? null;
            $this->UpdateAt = $data['UpdateAt'] ?? null;
        }
    }
}
?>