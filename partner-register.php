<?php
require "database-con.php";

// Fetch the feilds
if(
isset($_POST['FirstName']) &&
isset($_POST['LastName']) &&
isset($_POST['Email']) &&
isset($_POST['Country'] )&&
isset($_POST['Industry']) &&
isset($_POST['Experience']) &&
isset($_POST['LinkedInProfile'] )&&
isset($_POST['Phone']) &&
isset($_POST['Password'])&&
isset($_POST['aboutMe'])
 ){
     $FName = $_POST['FirstName'];
     $LName = $_POST['LastName'];
     $email=$_POST['Email'];
     $country  =$_POST['Country'] ;
     $industry = $_POST['Industry'];
     $exp = $_POST['Experience'];
     $linkedIn = $_POST['LinkedInProfile'];
     $phone  =$_POST['Phone'];
     $password = $_POST['Password'];
     $aboutMe = $_POST['aboutMe'];

     //transform to lowercase
     $email = strtolower($email);

     $select = "SELECT * FROM `partner-account` WHERE email = \"$email\" OR phone = $phone";
     $getRow = mysqli_query($conn,$select);
    //  var_dump(mysqli_num_rows($getRow));
     if(mysqli_num_rows($getRow)>0){
        // header("Access-Control-Allow-Origin: *");
        echo json_encode(['status'=>'exist']);
     }else{
         //Generate a unique id
         $uuid = uniqid('',true);
         $query = "INSERT INTO `partner-account` (`id`, `first_name`, `last_name`,`email` ,`password`, `phone`, `country`, `industry`, `linkedin`, `rating`, `about`,`experience`)
                 VALUES (NULL, '$FName', '$LName','$email' ,'$password', '$phone', '$country', '$industry', '$linkedIn', '0', '$aboutMe','$exp');";
        $insert = mysqli_query($conn,$query) or die("Failed Query");
        if($insert){
            // header("Access-Control-Allow-Origin: *");
            echo json_encode(['status'=>'ok']);
        }else{
            // header("Access-Control-Allow-Origin: *");
            echo json_encode(['status'=>'fail']);
        }
     }
     
   
}
?>