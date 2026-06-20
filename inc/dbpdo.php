<?php 
include 'dbpdoconfig.php';

try{
	
	 $dbh = new PDO("mysql:host=$host;dbname=$database", $username, $password);  
	//  $dbh = new PDO("mysql:host=$host;dbname=$database", $username, $password);  
	 //$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	 $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	   
   }catch(PDOException $e) {
	    echo $e->getMessage();
	}

?>