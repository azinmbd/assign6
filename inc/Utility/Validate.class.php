<?php

// it is up to you on how to implement the validation
// you may find storing the error message as associative array 
// and returning boolean may make your life easier

class Validate {

    public static $validStatus = array();
    static function validateForm(): bool{ 
        if (isset($_POST["title"]) && empty($_POST["title"])) {
            self::$validStatus["title"] = "Please select a title";
        }   
        
        if (isset($_POST["isbn"]) && !empty($_POST["isbn"])) {
            $options = array(
                "options" => array(
                    "regexp" => "/^\d{1}-\d{3}-\d{5}-\d{1}$/"
                    )
            );
            $filter = filter_input(INPUT_POST, "isbn", FILTER_VALIDATE_REGEXP, $options);

            if (!$filter) {
                self::$validStatus["isbn"] = "ISBN is not valid";
            }
        }
        if (isset($_POST["isbn"]) && empty($_POST["isbn"])) {
            self::$validStatus["isbn"] = "Please select an isbn";
        }
        if (isset($_POST["author"]) && empty($_POST["author"])) {
            self::$validStatus["author"] = "Please select an Author";
        } 
        
        if (isset($_POST["price"]) && empty($_POST["price"])) {
         
                $options = array(
                    "options" => array(
                        "min_range" => 1,
                        "max_range" => 1000,
                    )
                );
                $filtered_price = filter_input(INPUT_POST,"price", FILTER_VALIDATE_FLOAT, $options);
            
                if (!$filtered_price) {
                    self::$validStatus["price"] = "Please enter a valid price between 1 to 1000 integer";
                }
        } 

           // Validate usernamelogin
        //    if (isset($_POST["username"]) && empty($_POST["username"])) {
        //     // You might want to add additional username validation logic here if needed
        //     self::$validStatus["username"] = "Username is required.";
        // }
        // if (isset($_POST["username"]) && isset($_POST["password"]) ){
        //    $username = $_POST["username"];
        //  $password = $_POST['password'];
        //     $user = UsersDAO::getUser($username);
        //      $u= new User();
        //     if (!($user && $u->verifyPassword($password))){
        //          self::$validStatus["usename"] = "Wrong Username or password.";

        //      }


        // }
        
         // Validate username
         if (isset($_POST["action"]) && $_POST["action"]=="register" && empty($_POST["username"])) {
            // You might want to add additional username validation logic here if needed
            self::$validStatus["username"] = "Username is required.";
        }

        if (isset($_POST["action"]) && $_POST["action"]=="register" && !empty($_POST["username"])) {
            $username = $_POST["username"];
            if (UsersDAO::getUser($username)) {
                self::$validStatus["username"] = "This username is already taken. Please choose another one.";
            }
        }
        if (isset($_POST["action"]) && $_POST["action"]=="register" && empty($_POST["password"])) {
            // You might want to add additional username validation logic here if needed
            self::$validStatus["password"] = "password is required.";
        }

        // Validate passwords
        if (isset($_POST["password"]) && isset($_POST["password2"])) {
            $password = $_POST["password"];
            $password2 = $_POST["password2"];
        
            // Check if both passwords are empty
            if (empty($password) && empty($password2)) {
                self::$validStatus["password"] = "Both password fields are required.";
            } elseif ($password !== $password2) {
                // Check if passwords do not match
                self::$validStatus["password"] = "Passwords do not match.";

            }  elseif (isset($_POST["password"]) && !empty($_POST["password"])) {
                $options = array(
                    "options" => array(
                        "regexp" => "/^\d{5}$/"
                        )
                );
                $filter = filter_input(INPUT_POST, "password", FILTER_VALIDATE_REGEXP, $options);
    
                if (!$filter) {
                    self::$validStatus["password"] = "password must be ay leat 5 digits";
                }
            }
        }

               

        // Validate full name
        if (isset($_POST["fullname"]) && empty($_POST["fullname"])) {
            // You might want to add additional full name validation logic here if needed
            self::$validStatus["fullname"] = "Full Name is required.";
        }

        // Validate profile picture
        if (isset($_POST["action"]) && $_POST["action"]=="register" &&  empty($_POST["picture"])) {
            // You might want to add additional profile picture validation logic here if needed
            self::$validStatus["picture"] = "Profile Picture is required.";
        }

        

        if (empty(self::$validStatus)) {
            return true;
        } else {
            return false;
        }
    }
}



?>