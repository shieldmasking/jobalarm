<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
ini_set('mongo.native_long', 1);
error_reporting(-1);

include "lib/class.db.php";
include "lib/class.jatwitter.php";
include "inc/config.php";

JATwitter::retroFixIndustry();
