<?php
class checks {
    private $username;
    private $email;
    private $password;
    private $confirm_password;

    public function __construct($username, $email, $password, $confirm_password) {
        $this->username = $username;
        $this->email = $email;
        $this->password = $password;
        $this->confirm_password = $confirm_password;
    }

    public function validateUsername() {
        if (empty($this->username)) {
            return "Username is required.";
        }
        if (strlen($this->username) < 3) {
            return "Username must be at least 3 characters long.";
        }
        else {
            return true;
        }
    }

    public function validateEmail() {
        if (empty($this->email)) {
            return "Email is required.";
        } 
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        } else {
            return true;
        }
    }

    public function validatePassword() {
        if (empty($this->password)) {
            return "Password is required.";
        } 
        if (strlen($this->password) < 8) {
            return "Password must be at least 8 characters long.";
        } 
        if (!preg_match('/[A-Z]/', $this->password)) {
            return "Password must include at least one uppercase letter.";
        }
        if (!preg_match('/[a-z]/', $this->password)) {
            return "Password must include at least one lowercase letter.";
        }

        if (!preg_match('/[0-9]/', $this->password)) {
            return "Password must include at least one digit.";
        }

        if (!preg_match('/[\W]/', $this->password)) {
            return "Password must include at least one special character.";
        }
        else {
            return true;
        }
    }

    public function validateConfirmPassword() {

        if ($this->password !== $this->confirm_password) {
            return "Passwords do not match.";
        } else {
            return true;
        }
    }

    public function validateAll() {
        $usernameValidate = $this->validateUsername();
        $emailValidate = $this->validateEmail();
        $passwordValidate = $this->validatePassword();
        $confirmPasswordValidate = $this->validateConfirmPassword();
        $errors = [];

        if($usernameValidate !== true) {
            $errors[] = $usernameValidate;
        }
        if($emailValidate !== true) {
            $errors[] = $emailValidate;
        }
        if($passwordValidate !== true) {
            $errors[] = $passwordValidate;
        }
        if($confirmPasswordValidate !== true) {
            $errors[] = $confirmPasswordValidate;
        }
        if(empty($errors)) {
            return true;
        } else {
            return $errors;
        }
    }

} 