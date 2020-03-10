<?php
require "database-con.php";
$id = $_POST['id'];

$select = "SELECT `data_of_industry`,`description`,`title`,`data_rating`,`price`,`downloads`,`url`,`mime`,`total_compounded_rating`,`total_numberof_ratings` FROM `partner-uploads` INNER JOIN `item-metadata` ON `partner-uploads`.`id` = `item-metadata`.`item_id` WHERE `partner-uploads`.`partner_id` = $id ORDER BY `partner-uploads`.`id` DESC";
$query = mysqli_query($conn,$select);
$res = array();

while($i = mysqli_fetch_assoc($query)){
    array_push($res,$i);
}
echo json_encode($res);
?>