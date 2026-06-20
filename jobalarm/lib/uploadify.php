<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/
include "../inc/config.php";

// Define a destination
$targetFolder = '/uploads'; // Relative to the root
if (isset($_REQUEST['responseId'])) {
//    $responseId = 
//    if (!is_dir($targetFolder.'/'.
}

if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
	
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','pdf','docx','doc'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes)) {
		move_uploaded_file($tempFile,$targetFile);
		echo json_encode(array('success'=>true));
	} else {
		echo json_encode(array('success'=>false,'msg'=>'Invalid File Type'));
	}
}
