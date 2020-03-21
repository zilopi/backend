<?php
require "database-con.php";
$client_id = $_POST['client_id'];
$options = $_POST['options'];

$getDataQuery = "";
if($options == 'all'){
    global $getDataQuery;
    $getDataQuery  = " SELECT * FROM `client-transactions` INNER JOIN `item-metadata` ON `client-transactions`.`item_id` =`item-metadata`.`item_id` WHERE `client-transactions`.`client_id` = 7 ORDER BY transaction_timestamp DESC";



}else if($options == 'limit'){
    global $getDataQuery;
    $getDataQuery  = " SELECT * FROM `client-transactions` INNER JOIN `item-metadata` ON `client-transactions`.`item_id` =`item-metadata`.`item_id` WHERE `client-transactions`.`client_id` = 7 ORDER BY transaction_timestamp DESC LIMIT 5";

}

$finalArray = array();
$result = mysqli_query($conn,$getDataQuery);
while($data = mysqli_fetch_assoc($result)){
    $item_id = $data['item_id'];
    $getURL = mysqli_fetch_assoc(mysqli_query($conn,"SELECT url,mime,extension FROM `partner-uploads` WHERE id = $item_id"));
    $data['url'] = $getURL['url'];
    $data['mime'] = $getURL['mime'];
    $data['extension'] = $getURL['extension'];

    array_push($finalArray,$data);
}
echo json_encode($finalArray);
?>