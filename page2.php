<?php

// require the files
require_once "inc/config.inc.php";
require_once "inc/Entities/Book.class.php";
require_once "inc/Entities/User.class.php";
require_once "inc/Utility/Page.class.php";
require_once "inc/Utility/PDOWrapper.class.php";
require_once "inc/Utility/BooksDAO.class.php";
require_once "inc/Utility/UsersDAO.class.php";
require_once "inc/Utility/LoginManager.class.php";
require_once "inc/Utility/Validate.class.php";

UsersDAO::initialize("user");
// Check if the form was posted
if (!empty($_POST)) {
    if (Validate::validateForm()) {
        $user = new User();
        $user->setUserName($_POST["username"]);
        $hashedPassword = password_hash($_POST["password"], PASSWORD_DEFAULT);
        $user->setPassword($hashedPassword);
        $user->setFullName($_POST["fullname"]);
        $user->setProfilePic($_POST["picture"]);
        $user->setPrivilege();
        // If the submission was valid, create a normal user

        if ($_POST["action"] == "register") {
            UsersDAO::createUser($user);
            header("Location: page1.php");
            exit();
        }
    }
}

// set the Page's static information
// set the header
Page::header();
// display the registration form
Page::showRegistrationForm();
// display the error if any
// set the footer
Page::footer();
?>
