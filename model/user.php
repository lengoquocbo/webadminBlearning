<?php


class User {
    private $id;
    private $name;
    private $email;
    private $sdt;
    private $password;

    public function __construct($id, $name, $email, $password, $sdt = null) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->sdt = $sdt;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPhone() {
        return $this->sdt;
    }

    public function getPassword() {
        return $this->password;
    }

    // Setters
    public function setName($name) {
        $this->name = $name;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPhone($phone) {
        $this->sdt = $phone;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // Method to display user info
    public function displayUserInfo() {
        return "User ID: {$this->id}, Name: {$this->name}, Email: {$this->email}, Phone: {$this->sdt}";
    }
}

?>