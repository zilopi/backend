<?php
require "database-con.php";
$uuid = $_POST['uuid'];
$client_id = $_POST['client_id'];
$query = "SELECT * from `database`.`item-metadata` INNER JOIN `database`.`item-codes` ON `item-metadata`.`item_id` = `item-codes`.`item_id` WHERE `item-metadata`.`uuid` = '$uuid'";
$res = mysqli_query($conn,$query);
$data = mysqli_fetch_assoc($res);
$partner_id = $data['partner_id'];
// var_dump($partner_id);
$getPartner = mysqli_query($conn,"SELECT first_name,last_name from  `database`.`client-account` WHERE `id` = $partner_id");
$partnerDetails  = mysqli_fetch_assoc($getPartner);
$data['uploaded_by'] = $partnerDetails;
//Check if the partner has bought the data
$item_id = $data['item_id'];
// $item_id = 3;
$checkIfParnterHasBought = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `client-transactions` WHERE `client_id`=$client_id AND `item_id`= $item_id"));
if($checkIfParnterHasBought != 0){
    $data['purchased']=true;
}else{
    $data['purchased']=false;
}

echo json_encode($data);

?>