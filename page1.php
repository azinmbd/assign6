<?php

//Config
require_once "inc/config.inc.php";

//Entities
require_once "inc/Entities/Book.class.php";
require_once "inc/Entities/User.class.php";
require_once "inc/Utility/Page.class.php";

//Utility Classes
require_once "inc/Utility/PDOWrapper.class.php";
require_once "inc/Utility/BooksDAO.class.php";
require_once "inc/Utility/UsersDAO.class.php";
require_once "inc/Utility/LoginManager.class.php";
require_once "inc/Utility/Validate.class.php";

// start the session if it is not started yet
session_start();
// initialize both DAOs
BooksDAO::initialize("book");
UsersDAO::initialize("user");
// Except for the logout. Make sure to VALIDATE all form submissions



// get the books
$books = BooksDAO::getBooks();



// Page::showLogoutForm($username);
// check if there is any POST form method submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $error = Validate::validateForm();

    if ($error) {      
 
        if (isset($_POST["action"])) {
            switch ($_POST["action"]) {
                case "login":

                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $user = UsersDAO::getUser($username);

                    if ($user && $user->verifyPassword($password)) {
                        $_SESSION["username"] = $username;
                        $_SESSION["privilege"] = $user->getPrivilege();
                    }
                    break;

                    case "create":
                        if (LoginManager::verifyLogin() && isset($_SESSION["username"])) {
                            $isbn = $_POST["isbn"] ?? "";
                            $title = $_POST["title"] ?? "";
                            $author = $_POST["author"] ?? "";
                            $price = isset($_POST["price"]) ? (float)$_POST["price"] : 0.0;
    
                            $book = new Book();
                            $book->setISBN($isbn);
                            $book->setTitle($title);
                            $book->setAuthor($author);
                            $book->setPrice($price);
    
                            BooksDAO::createBook($book);
                        }
                        break;
                        case "logout":
                            $_SESSION["username"] = null;
                            break;

                        }
                    }
                }
            }
            
    //    $b = new Book();
    //     $u = new User();
    //     $isbn = isset($_POST["isbn"]) ? $_POST["isbn"] : "";
    //     $title = isset($_POST["title"]) ? $_POST["title"] : "";
    //     $author = isset($_POST["author"]) ? $_POST["author"] : "";
    //     $price = isset($_POST["price"]) ? (float) $_POST["price"] : 0.0;
    //     if ($isbn !== null) {
    //         $b->setISBN($isbn);
    //         $b->setTitle($title);
    //         $b->setAuthor($author);
    //         $b->setPrice($price);
    //     }
      
        // if it is login, process the login
        // if (isset($_POST["action"])) {
        //     if ($_POST["action"] == "login") {
        //         $username = $_POST["username"];
        //         $password = $_POST["password"];
        //         $user = UsersDAO::getUser($username);
        //         var_dump($user);
        //         if ($user && $user->verifyPassword($password)) {
        //             $_SESSION["username"] = $username;
        //             if (LoginManager::verifyLogin()) {
        //                 Page::showLogoutForm($username);
        //                 if ($user->getPrivilege() == 1) {
        //                     Page::showAddBookForm();
        //                     var_dump($_POST["action"] );
        //                     if ($_POST["action"] == "create") {
        //                         echo "clicled";
        //                         // and the user is privileged to create book, process the new book
        //                         $username = isset($_POST["username"]) ? $_POST["username"] : "";
        //                         $user = UsersDAO::getUser($username);
                                
        //                             BooksDAO::createBook($b);
        //                     }
        //                 }
        //             }
        //         }
        //     } elseif ($_POST["action"] == "logout") {
        //         $_SESSION["username"] = null;
        //     } 
        // }

        // }else if (isset($_POST["action"]) && $_POST["action"] == "logout") {

        // }
        // // if it is the book creation
        // if (isset($_POST["action"]) && ) {

        // }

// if (!LoginManager::verifyLogin()) {
//     Page::showLoginForm();
// }
// // check if there is any GET method
if (isset($_GET["action"]) && $_GET["action"] == "delete") {
    var_dump($_SESSION["privilege"]);
    if (LoginManager::verifyLogin() && isset($_SESSION["username"]) && $_SESSION["privilege"] == 1) {
        var_dump($_GET);
        BooksDAO::deleteBook($_GET["id"]);
        $books = BooksDAO::getBooks();
    }
}

// set the Page's static information
// set the header
Page::header();
// display error if any, display the books, display the login/logout, display the add new book (if admin)
if (!empty(Validate::$validStatus)) {
    Page::showErrorNotifications(Validate::$validStatus);
}

Page::showBookList($books);


if (!LoginManager::verifyLogin()) {
    Page::showLoginForm();
} else {
    // Display logout form if logged in
     $username = $_SESSION["username"] ?? "";
    $user = UsersDAO::getUser($username);
    Page::showLogoutForm($user);

    // Display add book form if logged in user is privileged
    if ($user && $user->getPrivilege() == 1) {
        Page::showAddBookForm();
    }
}

// set the footer
Page::footer();

var_dump(LoginManager::verifyLogin());
