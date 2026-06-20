<?php

/*
 * Because of the size of tweets per day, the db can only handle 1 months worth of data
 * This process will be executed from a cron job (setup in controlpanel) nightly
 * 
 */
	include '../inc/dbpdo.php';

	$query = 'delete from job where postdate < DATE_SUB(NOW(), INTERVAL 1 MONTH);';
 try {
   $stmt =  $dbh->prepare($query);	
   $stmt->execute();
   	
	echo "deleted ".$stmt->rowCount() ." rows";
 }catch(PDOException $e){ echo "error:".$e->getMessage();}

        
