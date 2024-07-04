<?php
// Include necessary files
require_once "inc/config.inc.php";
require_once "inc/Entities/Book.class.php";
require_once "inc/Entities/User.class.php";
require_once "inc/Utility/Page.class.php";
require_once "inc/Utility/PDOWrapper.class.php";
require_once "inc/Utility/BooksDAO.class.php";
require_once "inc/Utility/UsersDAO.class.php";
require_once "inc/Utility/LoginManager.class.php";
require_once "inc/Utility/Validate.class.php";

// Start session if not started
session_start();

// Initialize DAOs
BooksDAO::initialize("book");
UsersDAO::initialize("user");

// Check for form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $error = Validate::validateForm(); // Validate form inputs

    if ($error) { // If there are validation errors
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
                    // Check if user is logged in and has privilege to create books
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

// Check for book deletion action
if (isset($_GET["action"]) && $_GET["action"] == "delete") {
    if (LoginManager::verifyLogin() && isset($_SESSION["username"]) && $_SESSION["privilege"] == 1) {
        BooksDAO::deleteBook($_GET["id"]);
    }
}

// Set up the page
Page::header(); // Display header

// Display error messages if any
if (!empty(Validate::$validStatus)) {
    Page::showErrorNotifications(Validate::$validStatus);
}

// Display book list
$books = BooksDAO::getBooks();
Page::showBookList($books);

// Display login/logout forms
if (!LoginManager::verifyLogin()) {
    Page::showLoginForm();
} else {
    $username = $_SESSION["username"] ?? "";
    $user = UsersDAO::getUser($username);
    Page::showLogoutForm($user);

    // Display add book form for privileged users
    if ($user && $user->getPrivilege() == 1) {
        Page::showAddBookForm();
    }
}

Page::footer(); // Display footer

// Verify login status
var_dump(LoginManager::verifyLogin());
