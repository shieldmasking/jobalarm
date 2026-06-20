<?php
session_start();
if (isset($_GET['c'])) {
	session_destroy();
	header('Location: /login2.php');
	}else if (isset($_GET['a'])) {
	session_destroy();
	header('Location: /index.html');
	}else{
session_destroy();
header('Location: /index.html');
}
?>
