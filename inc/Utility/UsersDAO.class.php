<?php

// nothing to be done here

/*
+----+----------+--------------------+------------------+------------+-----------+
| Id | UserName | FullName           | Password         | ProfilePic | Privilege |
+----+----------+--------------------+------------------+------------+-----------+
*/

class UsersDAO{

    static private $db;   
    static function initialize(string $className)   {                
        self::$db = new PDOAgent($className);
    }

    static function getUser(string $username){
        $user = null;
        $selectOne = "SELECT * FROM users WHERE UserName=:user";
        self::$db->query($selectOne);
        self::$db->bind(":user",$username);
        self::$db->execute();
        

        return self::$db->singleResult();
    }

    static function createUser(User $user) : int   {
        $insert = "INSERT INTO users (UserName, FullName, Password, ProfilePic, Privilege) 
                    VALUES(:username, :fullname, :password, :pic, :priv)";

        self::$db->query($insert);
    
        self::$db->bind(":pic", $user->getProfilePic());
        self::$db->bind(":priv", 0);
        self::$db->bind(":fullname", $user->getFullName());
        self::$db->bind(":username", $user->getUserName());
        self::$db->bind(":password", $user->getPassword());
        self::$db->execute();

        return self::$db->rowCount();
    }
}


?>