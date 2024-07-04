<?php

// nothing to be done here
// almost similar to the one given by Rahim. But resultSet() is changed into multipleResult()

//Reference https://culttt.com/2012/10/01/roll-your-own-pdo-php-class/
// Construct the DSN!

class PDOAgent    {

//Database connection details
private $host = DB_HOST;
private $user = DB_USER;
private $pass = DB_PASS;
private $dbname = DB_NAME;
private $dbport = DB_PORT;

//Set the DSN
private $dsn = "";

//Set the class
private $className;

//Error
private $error;

//Prepared Statement
private $stmt;

//Store the PDO object
private $pdo;

public function __construct(string $className)  {  

    //Copy the class name
    $this->className = $className;

    //Build DSN AlWAYS CHECK YOUR MYSQL PORT!
    $this->dsn = "mysql:host={$this->host};dbname={$this->dbname};port={$this->dbport}";
    
    $options = array(
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    );

    try {
        $this->pdo = new PDO($this->dsn, $this->user, $this->pass, $options);
    } catch (PDOException $e)   {
        $this->error = $e->getMessage();
    }
}

//Prepare the satemet for execution
public function query(string $query)    {
    $this->stmt = $this->pdo->prepare($query);
}

public function bind($param, $value, $type = null)  {  
    if (is_null($type)) {  
        switch (true) {  
            case is_int($value):  
                $type = PDO::PARAM_INT;  
                break;  
            case is_bool($value):  
                $type = PDO::PARAM_BOOL;  
                break;  
            case is_null($value):  
                $type = PDO::PARAM_NULL;  
                break;  
            default:  
                $type = PDO::PARAM_STR;  
                break;
        }  
    }  
    $this->stmt->bindValue($param, $value, $type);  
}
   //Execute
   public function execute($data = null)  {
       // data can be not null $sth->execute(array(':calories' => $calories, ':colour' => $colour));
       if (is_null($data))  {
           return $this->stmt->execute();
       } else {
        return $this->stmt->execute($data);
       }
   }
   
   //Return a single result
   public function singleResult()   {
       
        //Executethe statement
        $this->stmt->execute();
        //set fetch mode to return classes
        $this->stmt->setFetchMode(PDO::FETCH_CLASS, $this->className);
        return $this->stmt->fetch(PDO::FETCH_CLASS);
   }

   //Return resultSet
   public function multipleResult()  {
       //Executethe statement
       $this->stmt->execute();
    
       return $this->stmt->fetchAll(PDO::FETCH_CLASS,$this->className);
   }

   //Return the rowcount
   public function rowCount() : int   {
       return $this->stmt->rowCount();
   }
   
    //Get the lastInsertedID
    public function lastInsertId(): int{  
        return $this->pdo->lastInsertId();  
    }
   //Get the debug info
   public function debugDumpParams()    {
       return $this->stmt->debugDumpParams();
   }
}
?>