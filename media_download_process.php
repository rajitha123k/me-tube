<?php
session_start();
include_once "function.php";



$username=$_SESSION['username'];
$mediaid=$_GET['id'];


$insertDownload="insert into download(downloadid,username,mediaid) values(NULL,'$username','$mediaid')";
$queryresult = mysqli_query($con, $insertDownload);

$query = "SELECT filepath, filename FROM media WHERE mediaid='$mediaid'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_row($result);
$file = $row[0].$row[1];

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    echo "Read";
    exit;
}
	
?>


