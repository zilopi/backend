<?php
require "database-con.php";
require "vendor/autoload.php";
use  DonatelloZa\RakePlus\RakePlus;

$query = $_POST['query'];
$client_id = null;
$query = strtolower($query);
if(isset($_POST['client_id'])){
    global $client_id;
    $client_id = $_POST['client_id'];
}
$getKeywords = RakePlus::create($query)->get();
for($i = 0 ; $i< sizeof($getKeywords) ; $i++){
    $getKeywords[$i] = strtolower($getKeywords[$i]);
}

$lengthOfKeywords = count($getKeywords);

$query = "SELECT * FROM `item-metadata` WHERE ";

for($var = 0; $var < $lengthOfKeywords -1 ; $var++){
    $query =  $query." `keywords` LIKE '%".$getKeywords[$var]."%' AND";
}

//Fetch the results from the meta table
$query = $query."`keywords` LIKE '%".$getKeywords[$lengthOfKeywords-1]."%'";
$result = mysqli_query($conn,$query);

$results = array();
// $results['results'] = array();
while($item = mysqli_fetch_assoc($result)){
    global $client_id;
    $getFromPartnerTable = "SELECT `information_type`,`mime`,`downloads`,`url`,`partner_id`,`extension`,`id` FROM `partner-uploads` WHERE `id` = ".$item['item_id'];
    $res = mysqli_query($conn,$getFromPartnerTable);
    $itemData = mysqli_fetch_assoc($res);
    $partnerId = $itemData['partner_id'];
    //Get codes
    $getCodes = "SELECT * FROM `item-codes` WHERE `item_id` = ".$item['item_id'];
    $res2 = mysqli_query($conn,$getCodes);

    $codeData = mysqli_fetch_assoc($res2);

    $getPartnerData = "SELECT first_name, last_name,email,phone,country FROM `database`.`partner-account` WHERE `id` = $partnerId";
    $res3 = mysqli_query($conn, $getPartnerData);
    $partnerData = mysqli_fetch_assoc($res3);
    
    $partnerDataArray = array();
    $partnerDataArray['uploaded_by'] = $partnerData;

    if($client_id != null){
        $checkIfBought = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `client-transactions` WHERE `client_id`=$client_id AND `item_id` =". $item['item_id']));
        if($checkIfBought!=0){
            $partnerDataArray['purchased'] = true;

        }else{
            $partnerDataArray['purchased'] = false;

        }
    }else{
        $partnerDataArray['purchased'] = false;

    }

    //Remove the non -essential feilds
    unset($codeData['uuid']);
    unset($codeData['id']);
    unset($codeData['item_id']);
    unset($item['id']);
    unset($item['item_id']);
    unset($item['partner_id']);
    $itemData['codes'] = $codeData;

    // $results['results'] = array_merge($item,$itemData);
    array_push($results,array_merge($item,$itemData,$partnerDataArray));
}
echo json_encode($results);
?>