<?php
class Page
{
    public static $developerID;
    public static $developerName;
    public static $title = "Please set your title!";

    static function header()
    {
        ?>
        <!-- Start the page 'header' -->
        <!DOCTYPE html>
        <html>
            <head>
                <title></title>
                <meta charset="utf-8">
                <meta name="author" content="Bambang">
                <title><?php echo self::$title; ?></title>   
                <link href="css/styles.css" rel="stylesheet">     
            </head>
            <body>
                <header>
                    <h1>Assignment 6: PDO and Auth by Ellie (300367481)</h1>
                </header>
                <article>
                    
    <?php
    }

    static function mainToSide()
    {
        echo "<section class=\"sides\">";
    }
    static function showErrorNotifications($errorData)
    {
        ?>
        <section class="main">
        <div class="error">
           <?php echo "All inputs are required. Please fix the folowing:"; ?>
                   <ul>
                       <?php foreach ($errorData as $err) {
                           echo "<li>{$err}</li>";
                       } ?>
                   </ul>
               </div> 
           <?php
    }

    static function showBookList(array $bookData)
    {
        ?>
        <div class="data">
            <h2>Current Data</h2>
            <table>
                <thead>
                    <tr>
                        <th class="table">ISBN</th>                        
                        <th class="table">Title</th>
                        <?php
                        if (LoginManager::verifyLogin()) {
                            echo "<th class=\"table\">Author</th>";
                            echo "<th class=\"table\">Price</th>";
    
                            $username = $_SESSION["username"] ?? "";
                            $user = UsersDAO::getUser($username);
    
                            if ($user && $user->getPrivilege() == 1) {
                                echo "<th class=\"table\">Delete</th>";
                            }
                        }
                        ?>                      
                    </tr>
                </thead>
                <tbody class="oddRow">
                    <?php foreach ($bookData as $book) {
                        $deleteUrl = "?action=delete&id={$book->getISBN()}";
                        echo "<tr>";
                        echo "<td class=\"table\">" . $book->getISBN() . "</td>";
                        echo "<td class=\"table\">" . $book->getTitle() . "</td>";
                        if (LoginManager::verifyLogin()) {
                            echo "<td class=\"table\">" . $book->getAuthor() . "</td>";
                            echo "<td class=\"table\">" . $book->getPrice() . "</td>";
    
                            $username = $_SESSION["username"] ?? "";
                            $user = UsersDAO::getUser($username);
    
                            if ($user && $user->getPrivilege() == 1) {
                                echo "<td><span class='delete'><a href='{$deleteUrl}'>Delete</a></span></td>";
                            }
                        }
                        echo "</tr>";
                    } ?>
                </tbody>
            </table>
        </div>            
        </section>
        <?php
    }
    



    static function showLoginForm()
    {
        ?>
        <section class="side">
        
        <div class="form login">
                <h3>Login</h3>
                <form action="" method="post">
                    <table>
                        <tr>
                            <td class="table">Username</td>
                            <td class="table"><input type="text" name="username" placeholder="Your username"></td>
                        </tr>                        
                        <tr>
                            <td class="table">Password</td>
                            <td class="table"><input type="password" name="password" placeholder="Your Password"></td>
                        </tr>    
                        <tr>
                            <td colspan="2" class="text">
                                Do not have an account? Please <strong> <a href="page2.php">Register here</strong></a>
                            </td>
                        </tr>                                                                    
                    </table>
                    <input type="hidden" name="action" value="login">
                    <input type="submit" value="Login">
                </form>
                </div>
    

    <?php
    }

    static function showLogoutForm($user)
    {   
        ?>
<section class="side">
                <div class="form logout">
                    <form action="" method="post">
                        <table>
                            <tr>
                                <td rowspan="2" class="img"><img src="<?php echo "images/". $user->getProfilePic() . ".png"; ?>"></td>
                                <td >Welcome <?php echo "<b>" .
                                    $user->getFullName() .
                                    "</b>"; ?></td>
                            </tr>                        
                            <tr>                            
                                <td>Depending on your privilege, you can see detail or add/delete new book</td>
                            </tr>    
                                                                                                
                        </table>
                        <input type="hidden" name="action" value="logout">
                        <input type="submit" value="Logout">
                    </form>
                </div>

   <?php
    }


    static function showAddBookForm()
    {
        // Check for validation errors
        if (!Validate::validateForm()) {
            // Repopulate form fields with previously entered values
            $isbnValue = isset($_POST["isbn"]) ? $_POST["isbn"] : "";
            $titleValue = isset($_POST["title"]) ? $_POST["title"] : "";
            $authorValue = isset($_POST["author"]) ? $_POST["author"] : "";
            $priceValue = isset($_POST["price"]) ? $_POST["price"] : "";
        } else {
            // Form submitted successfully, clear previous values
            $isbnValue = $titleValue = $authorValue = $priceValue = "";
        } ?>

      <!-- Start the page's add entry form -->
      <div class="form add">
                    <h3>Add a New Book</h3>
                    <form action="" method="post">
                        <table>
                            <tr>
                                <td class="table">ISBN</td>
                                <td class="table"><input type="text" name="isbn" placeholder="X-XXX-XXXXX-X" value="<?php echo $isbnValue; ?>"></td>
                            </tr>                        
                            <tr>
                                <td class="table">Title</td>
                                <td class="table"><input type="text" name="title" placeholder="Book Title" value="<?php echo $titleValue; ?>"></td>
                            </tr>                                                
                            <tr>
                                <td class="table">Author</td>
                                <td class="table">
                                    <input type="text" name="author" placeholder="Book Author"value="<?php echo $authorValue; ?>"></td>
                            </tr>
                            <tr>
                                <td class="table">Price</td>
                                <td class="table"><input type="text" name="price" placeholder="Book Price between 1 to 1000" value="<?php echo $priceValue; ?>"></td>
                            </tr>
                        </table>
                        <input type="hidden" name="action" value="create">
                        <input type="submit" value="Add New Book">
                    </form>
                </div>
    </section>
<?php
    }

    static function showRegistrationForm() {
        $username = "";
        if (isset($_POST)) {
            // Check for validation errors
            if (!Validate::validateForm()) {
                // Repopulate form fields with previously entered values
                $enteredUsername = isset($_POST["username"])
                    ? htmlspecialchars($_POST["username"])
                    : "";

                // Check if the entered username already exists
                if (UsersDAO::getUser($enteredUsername)) {
                    // If the username exists, clear the input
                    $username = "";
                } else {
                    // If the username is new, retain it
                    $username = $enteredUsername;
                }
            }
        }
        ?>
                    
            <!-- Start the page's side panel -->
                <!-- start of error notification -->  
               
                <?php if (!empty(Validate::$validStatus)) {
                    //    echo '<section class="side">';
                    echo '<section class="side">';
                    echo '<div class="error">';
                    echo "<p>All inputs are required. Please fix the following:</p>";
                    echo "<ul>";
                    foreach (Validate::$validStatus as $error) {
                        echo "<li>" . $error . "</li>";
                    }
                    echo "</ul>";
                    echo "</div>";
                    echo "</section>";
                } ?>
             <div class="form add">   
                    <h2>Registration</h2>        
                <form action="" method="post">
                    <table>
                    <tr>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="2"><p>Do you have an account? <strong><a href="page1.php">Login here</a> </strong></p></td>
                    </tr>
                    <tr>
                        <td><label for="username">Username</label></td>
                        <td><input type="text" name="username" placeholder="Enter username with no whitespace" value="<?php echo $username; ?>"></td>
                    </tr>
                    <tr>
                        <td><label for="password">Password</label></td>
                        <td><input type="password" name="password" placeholder="Password" ></td>
                    </tr>
                    <tr>
                        <td><label for="password">Password confirm</label></td>
                        <td><input type="password" name="password2" placeholder="Password confirm" ></td>
                    </tr>
                    <tr>
                        <td><label for="fullname">Full name</label></td>
                        <td><input type="text" name="fullname" placeholder="Full Name" value="<?php echo isset(
                            $_POST["fullname"]
                        )
                            ? htmlspecialchars($_POST["fullname"])
                            : ""; ?>"></td>
                    </tr>
                    <tr>
                        <td><label for="image">Profile Picture</label> </td>
                        <td><span>
                            <input type="radio" name="picture" value="profile1" <?php echo isset(
                                $_POST["picture"]
                            ) && $_POST["picture"] === "profile1"
                                ? "checked"
                                : ""; ?>><img src="images/profile1.png">
                            <input type="radio" name="picture" value="profile2" <?php echo isset(
                                $_POST["picture"]
                            ) && $_POST["picture"] === "profile2"
                                ? "checked"
                                : ""; ?>><img src="images/profile2.png">                    
                            <input type="radio" name="picture" value="profile3" <?php echo isset(
                                $_POST["picture"]
                            ) && $_POST["picture"] === "profile3"
                                ? "checked"
                                : ""; ?>><img src="images/profile3.png">
                            <br>                            
                            <input type="radio" name="picture" value="profile4" <?php echo isset(
                                $_POST["picture"]
                            ) && $_POST["picture"] === "profile4"
                                ? "checked"
                                : ""; ?>><img src="images/profile4.png">
                            <input type="radio" name="picture" value="profile5" <?php echo isset(
                                $_POST["picture"]
                            ) && $_POST["picture"] === "profile5"
                                ? "checked"
                                : ""; ?>><img src="images/profile5.png">
                            <input type="radio" name="picture" value="profile6" <?php echo isset(
                                $_POST["picture"]
                            ) && $_POST["picture"] === "profile6"
                                ? "checked"
                                : ""; ?>><img src="images/profile6.png">
                        </span></td>
                    </tr>                    
                    <tr>
                        <input type="hidden" name="action" value="register">
                        <td colspan="2"><input type="submit" name="submit" value="Register"></td>
                    </tr>                 
                    <div>
                       
                    </div>
                </form>
                </table>


                </div>
      
    <?php
    }
    static function footer() {   ?>
    <!-- Start the page's footer -->  
            </article>
        </body>

    </html>
<?php
    }
}
