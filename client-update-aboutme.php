<?php
require "database-con.php";
$id = $_POST['id'];
$newAboutMe = $_POST['about'];
$q = "UPDATE `client-account` SET `about_me` = \"$newAboutMe\" WHERE `id` = \"$id\"";
$update = mysqli_query($conn,$q);
// var_dump($q);
if($update){
    echo json_encode(['status'=>'success']);
}else{
    echo json_encode(['status'=>'fail']);
}
?>