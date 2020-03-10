<?php
require "database-con.php";
if(isset($_POST['Email'])&&isset($_POST['Password'])){
    $Email = $_POST['Email'];

    //Convert to lower
    $Email = strtolower($Email);

    $Password = $_POST['Password'];
    $select = mysqli_query($conn,"SELECT * FROM `client-account` WHERE `email`= \"$Email\"");
    if(mysqli_num_rows($select)>0){
        $account = mysqli_fetch_assoc($select);
        if($Password==$account['password']){
            $account['Password']="Encrytped";
            $account['status']="200";
            // header("Access-Control-Allow-Origin: *");
            unset($account['password']);
            unset($account['Password']);
            echo json_encode($account);
        }else{
            // header("Access-Control-Allow-Origin: *");
            echo json_encode(['status'=>"PasswordError"]);
        }
        
        
        
    }else{
        // header("Access-Control-Allow-Origin: *");
        echo json_encode(["status"=>"404"]);
    }
}
?>