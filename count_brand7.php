<?php
$db = new mysqli('localhost', 'tweetedj_admin', 'Premier2000!', 'tweetedj_tweetedjobs');
if ($db->connect_error) die("Connection failed: " . $db->connect_error . "\n");
$result = $db->query("SELECT COUNT(*) as count FROM jobs WHERE brand=7");
$row = $result->fetch_assoc();
echo "Jobs with brand=7: " . $row['count'] . "\n";
$db->close();
