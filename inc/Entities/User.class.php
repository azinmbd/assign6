<?php

// nothing to be done here

/*
+----+----------+--------------------+------------------+------------+-----------+
| Id | UserName | FullName           | Password         | ProfilePic | Privilege |
+----+----------+--------------------+------------------+------------+-----------+
*/

class User{
    private $Id;
    private $UserName;
    private $FullName;
    private $Password;
    private $ProfilePic;
    private $Privilege=0;

    function getId(){
        return $this->Id;
    }
    function setId($Id){
        $this->Id = $Id;
    }
    function getUserName(){
        return $this->UserName;
    }
    function setUserName($UserName){
        $this->UserName = $UserName;
    }
    function getFullName(){
        return $this->FullName;
    }
    function setFullName($FullName){
        $this->FullName = $FullName;
    }
    function getPassword(){
        return $this->Password;
    }
    function setPassword($Password){
        $this->Password = $Password;
    }
    function getProfilePic(){
        return $this->ProfilePic;
    }
    function setProfilePic($ProfilePic){
        $this->ProfilePic = $ProfilePic;
    }
    function getPrivilege(){
        return $this->Privilege;
    }
    function setPrivilege(){
        $this->Privilege = 0;
    }


    function verifyPassword(string $passwordToVerify) {
        
        return password_verify($passwordToVerify, $this->Password);
    }
}

?>