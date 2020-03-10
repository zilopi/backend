<?php
require "database-con.php";
$uuid = $_POST['uuid'];
$query = "SELECT * from `database`.`item-metadata` INNER JOIN `database`.`item-codes` ON `item-metadata`.`item_id` = `item-codes`.`item_id` WHERE `item-metadata`.`uuid` = '$uuid'";
$res = mysqli_query($conn,$query);
$data = mysqli_fetch_assoc($res);
$partner_id = $data['partner_id'];
var_dump($partner_id);
$getPartner = mysqli_query($conn,"SELECT first_name,last_name from  `database`.`client-account` WHERE `id` = $partner_id");
$partnerDetails  = mysqli_fetch_assoc($getPartner);
$data['uploaded_by'] = $partnerDetails;
echo json_encode($data);

?>