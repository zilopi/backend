<?php
require "database-con.php";
if(
    isset($_POST['FirstName']) &&
    isset($_POST['LastName']) &&
    isset($_POST['Email']) &&
    isset($_POST['Country'] )&&
    isset($_POST['Industry']) &&
    isset($_POST['Phone']) &&
    isset($_POST['Password'])&&
    isset($_POST['AboutMe'])
     ){
        $FName = $_POST['FirstName'];
        $LName = $_POST['LastName'];
        $email=$_POST['Email'];
        $country  =$_POST['Country'] ;
        $industry = $_POST['Industry'];
        $phone  =$_POST['Phone'];
        $password = $_POST['Password'];
        $aboutMe = $_POST['AboutMe'];
        
    //Convert to lower
        $email = strtolower($email);

        $select = "SELECT * FROM `client-account` WHERE email = \"$email\" OR phone_number = $phone";
        $getRow = mysqli_query($conn,$select);
        if(mysqli_num_rows($getRow)>0){
            // header("Access-Control-Allow-Origin: *");
            echo json_encode(['status'=>'exist']);
         }else{

            $query = "INSERT INTO `client-account` (`id`, `first_name`, `last_name`,`email` ,`password`, `phone_number`, `country`, `industry`,  `about_me`,`wallet_balance`)
            VALUES (NULL, '$FName', '$LName','$email' ,'$password', '$phone', '$country', '$industry',   '$aboutMe',100);";

            $insert = mysqli_query($conn,$query) or die("Failed Query");

            if($insert){
                // header("Access-Control-Allow-Origin: *");
                echo json_encode(['status'=>'ok']);
            }else{
                // header("Access-Control-Allow-Origin: *");
                echo json_encode(['status'=>'fail']);
            }

         }
        
    }else{
        echo json_encode(['status'=>'bad-form']);
    }


?>