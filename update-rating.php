<?php
require "database-con.php";
$uuid = $_POST['uuid'];

$rating = $_POST['rating'];
$rating = intval($rating);
// var_dump($rating,$uuid);
mysqli_query($conn,"CALL ratingUpdate($rating,'$uuid')");

?>