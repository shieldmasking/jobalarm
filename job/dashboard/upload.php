<?php

$target_dir = "feeds/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

// Check if image file is a actual image or fake image
if (isset($_POST["submit"])) {

    if ($target_file == "uploads/") {
        $msg = "no file selected.";
        $uploadOk = 0;
    } // Check file size
    else if ($_FILES["fileToUpload"]["size"] > 5000000) {
        $msg = "Sorry, your file is too large.";
        $uploadOk = 0;
    } // Check if $uploadOk is set to 0 by an error
    else if ($uploadOk == 0) {
        $msg = "Sorry, your file was not uploaded.  Please contact your JobAlarm Representative for assistance.";

        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $msg = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded and your jobs will refresh momentarily.";
        }
    }
}

?>