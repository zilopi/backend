<?php
require "database-con.php";
$prevWalletAmount = $_POST['prev'];
$price = $_POST['price'];
$client_id = $_POST['client_id'];
$client_id = intval($client_id);
$item_id = $_POST['item_id'];

//Convert into integer
$item_id = intval($item_id);
$client_id = intval($client_id);

$getPrevWalletAmount = mysqli_fetch_assoc(mysqli_query($conn,"SELECT wallet_balance FROM `client-account` WHERE id = '$client_id'"))['wallet_balance'];



// if($getPrevWalletAmount == $prevWalletAmount){
    if(true){
        //TODO: Have some authentication via the auth token

    //Update the wallet amount
    $finalWalletAmount = intval($prevWalletAmount) - intval($price);
    if($finalWalletAmount < 0){
        echo json_encode(['status'=>'low_balance']);
    }
    mysqli_query($conn, "UPDATE `client-account` SET wallet_balance = $finalWalletAmount WHERE id = $client_id");

    //Update the transaction table
    $uid = uniqid("INV",true);
    $insertIntoTransactions = "INSERT INTO `database`.`client-transactions`
    (`id`,
    `transaction_timestamp`,
    `transcation_id`,
    `item_id`,
    `client_id`)
    VALUES
    (NULL,
    NOW(),
    '$uid',
    $item_id,
    $client_id);";
    mysqli_query($conn,$insertIntoTransactions);
    echo json_encode(['status'=>'ok']);
}else{
    echo json_encode(['status'=>'fail']);
}
?>