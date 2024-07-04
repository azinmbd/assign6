-- Make sure to change the database name according 
-- to the naming convention

-- DROP the database if it exists
-- CREATE THE DATABASE
DROP DATABASE IF EXISTS assign6_ab_xx;
CREATE DATABASE IF NOT EXISTS assign6_ab_XX;

-- Use the database
USE assign6_ab_XX;

-- Create Users table
CREATE TABLE Users (
	Id INT AUTO_INCREMENT PRIMARY KEY,
	UserName VARCHAR(20),	
  FullName VARCHAR(50),		
	Password VARCHAR(250),
  ProfilePic VARCHAR(50),
  Privilege TINYINT
) Engine=InnoDB;

-- Create Books table
CREATE TABLE Books
(  ISBN CHAR(13) NOT NULL PRIMARY KEY,
   Author CHAR(50),
   Title CHAR(100),
   Price FLOAT(4,2)
) ENGINE=InnoDB;

-- INSERT Users records
-- Password is Assign6Admin
-- Privilege 1 is for admin, 0 is for normal user
INSERT INTO Users (Id, UserName, FullName, Password, ProfilePic,Privilege) VALUES 
  (1,'admin','Assignment 6 Admin','$2y$10$pDWwscXgsjodUin1ZzB5.OLjzTfJGVdWreixmF00xIIgK7PrIvZK6','profile1',1);



-- INSERT Books records
INSERT INTO Books VALUES
  ('0-672-31697-8', 'Michael Morgan', 
   'Java 2 for Professional Developers', 35),
  ('0-672-31745-1', 'Thomas Down', 'Installing Debian GNU/Linux', 25),
  ('0-672-31769-9', 'Thomas Schenk', 
   'Caldera OpenLinux System Administration Unleashed', 45);

