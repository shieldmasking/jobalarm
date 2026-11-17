<?php
session_name('PRNADMIN');
session_start();
session_destroy();
header('location: /admin/login.php');
exit();
