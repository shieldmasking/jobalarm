<?php
include 'inc/class.db.php';
include 'inc/class.jatwitter.php';
include 'inc/config.php';

$local_file = '/home/tweetedjobs/feeds/cvs/CVSCaremark_Feed.xml';

/*if(get_feed_data()==1){
	
}*/

getDataFeed();

function get_feed_data() {
	
	// define some variables
$local_file = '/home/tweetedjobs/feeds/snag/Snagajob-Snagajob_Product_Catalog.txt';
$server_file = '/datatransfer/files/4773591/outgoing/productcatalog/189178/Snagajob-Snagajob_Product_Catalog.txt.gz';

$ftp_server = 'http://datatransfer.cj.com/datatransfer/files/4773591/outgoing/productcatalog/189178/Snagajob-Snagajob_Product_Catalog.txt.gz';
$ftp_user_name = '4773591';
$ftp_user_pass ='U@khgW76';

$success=0;

try{
		// set up basic connection
		$conn_id = ftp_connect($ftp_server);
		
		// login with username and password
		$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
		
		// try to download $server_file and save to $local_file
		if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
			$success=1;
		    echo "Successfully written to $local_file\n";
		} else {
		    echo "There was a problem downloading cvs data feed\n";
		}
		
		// close the connection
		ftp_close($conn_id);

	}catch(Exception $e){
		throw new Exception('Error retrieving data:'.$e->getMessage());
	}
 
    return $success;
	
}

	

function getDataFeed(){
		
	$output_filename = '/home/tweetedjobs/feeds/snag/Snagajob-Snagajob_Product_Catalog.txt';
	
    $user_name = '4773591';
	$user_pass ='U@khgW76';


		set_time_limit(0);
		//This is the file where we save the    information
		$fp = fopen ($output_filename, 'w+');
		//Here is the file we are downloading, replace spaces with %20
		//$ch = curl_init(str_replace(" ","%20",$url));
		 $host = "http://datatransfer.cj.com/datatransfer/files/4773591/outgoing/productcatalog/189178/Snagajob-Snagajob_Product_Catalog.txt.gz";
		 $ch = curl_init();
		 curl_setopt($ch, CURLOPT_URL, $host);
		curl_setopt($ch, CURLOPT_TIMEOUT, 50);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERPWD, "$user_name:$user_pass");
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		// write curl response to file
		curl_setopt($ch, CURLOPT_FILE, $fp); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// get curl response
		$result = curl_exec($ch); 
		
		if(curl_error($c))
		{
    	echo 'error:' . curl_error($c);
		}
		
		curl_close($ch);
		fclose($fp);
		
   /* $host = "http://datatransfer.cj.com/datatransfer/files/4773591/outgoing/productcatalog/189178/Snagajob-Snagajob_Product_Catalog.txt.gz";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $host);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, false);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERPWD, "$user_name:$user_pass");
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    $result = curl_exec($ch);
    curl_close($ch);
    */
    
/*    print_r($result); // prints the contents of the collected file before writing..


    // the following lines write the contents to a file in the same directory (provided permissions etc)
    $fp = fopen($output_filename, 'w');
    fwrite($fp, $result);
    fclose($fp);
 */
}


/*'twitterId' => sku
				'postDate' => startdate
				'city' => keywords parse->city
				'state' => keywords parse->state
				
				'text' => description,
				'title' => name,
				'brand' => 25,
				'userName' => "JOBALARM",
				'urls' => impressionurl
				
				*/
				
 
?>