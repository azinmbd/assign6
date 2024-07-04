<?php

// nothing to be done here

class BooksDAO {

    //Place to store the PDO Agent/Service class 
    static private $db;   

    // you must initialize the PDOAgent
    static function initialize(string $className)   {                
        self::$db = new PDOAgent($className);
    }

    // mysql> DESC books;
    // +--------+------------+------+-----+---------+-------+
    // | Field  | Type       | Null | Key | Default | Extra |
    // +--------+------------+------+-----+---------+-------+
    // | ISBN   | char(13)   | NO   | PRI | NULL    |       |
    // | Author | char(50)   | YES  |     | NULL    |       |
    // | Title  | char(100)  | YES  |     | NULL    |       |
    // | Price  | float(4,2) | YES  |     | NULL    |       |
    // +--------+------------+------+-----+---------+-------+
    // 4 rows in set (0.03 sec)
    
    // function to create (insert) book
    static function createBook(Book $newBook) : int   {
        $insert = "INSERT INTO books (ISBN, Author, Title, Price) 
                    VALUES(:isbn, :author, :title, :price)";

        self::$db->query($insert);

        self::$db->bind(":isbn",$newBook->getISBN());
        self::$db->bind(":author",$newBook->getAuthor());
        self::$db->bind(":title",$newBook->getTitle());        
        self::$db->bind(":price",$newBook->getPrice());

        self::$db->execute();

        return self::$db->lastInsertId();
    }

    static function getBook(string $isbn) : Book{
        $book = null;
        $selectOne = "SELECT * FROM books WHERE ISBN=:isbn";
        self::$db->query($selectOne);
        self::$db->bind(":isbn",$isbn);
        self::$db->execute();

        return self::$db->singleResult();
    }

    // function to get (select) book(s)
    static function getBooks() : Array {        
        $select = "SELECT * FROM books";
        self::$db->query($select);
        self::$db->execute();
        return self::$db->multipleResult();
    }

    // function to delete book, we should use try catch
    static function deleteBook(string $isbn) : bool {
        $delete = "DELETE FROM books WHERE ISBN=:isbn";
        try{
            // query bind execute of the prepared statements
            self::$db->query($delete);
            self::$db->bind(":isbn",$isbn);
            self::$db->execute();
            
            if(self::$db->rowCount() != 1){
                throw new Exception("Problem deleting book {$isbn}");
            }

        }catch(Exception $e){
            echo $e->getMessage();
            self::$db->debugDumpParams();
            return false;
        }

        return true;
    }

}

?>