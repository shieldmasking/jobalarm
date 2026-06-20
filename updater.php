<?php
include_once 'inc/class.db.php';
include_once 'inc/class.jatwitter.php';
include_once 'inc/config.php';
include_once 'inc/pagination.class.php';

require 'vendor/autoload.php';

$query = "select s.*, c.city, c.state_code, c.zip as Czip from sms_stores s LEFT JOIN cities_extended as c on c.city = s.city and c.state_code = s.st where s.id > 1324 and (s.zip=0 or s.zip='') group by s.id";
    $dbData = Config::get('db')->get_results($query);
	
foreach($dbData as $store){
	 $zip = $store['Czip'];
	 $sid = $store['id'];
	 Config::get('db')->query("update sms_stores set zip=$zip where id=$sid");
	
	
}



?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<body>

<!-- END JAVASCRIPTS -->
</body>
<!-- END BODY -->
</html>
