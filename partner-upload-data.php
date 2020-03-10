<?php
require "database-con.php";
require "aws-credentials.php";
require "vendor/autoload.php";
use  DonatelloZa\RakePlus\RakePlus;

// Get the file for the mime type
// TODO : upload functionalty to implement the url and the downloads
// $file = $_FILES['DataFile']
// if( (isset($_FILES['DataFile'])) &&
if(
    (isset($_POST['Description']) ) &&
    (isset($_POST['ItemGrade'])) &&
    (isset($_POST['Id']) && ($_POST['Id']!="")) &&
    isset($_POST['CAS']) &&
    (isset($_POST['ItemName']) && ($_POST['ItemName']!="")) &&
    (isset($_POST['Industry']) && ($_POST['Industry']!="")) &&
    (isset($_POST['Price']) && ($_POST['Price']!="")) &&
    (isset($_POST['Currency']) && ($_POST['Currency']!="")) &&
    (isset($_POST['LocationFocus']) && ($_POST['LocationFocus']!="")) &&
    (isset($_POST['TypeOfData']) && ($_POST['TypeOfData']!="")) 
        


)
{

$itemDescription = $_POST['Description'];
$itemGrade = $_POST['ItemGrade'];
$id = $_POST['Id'];
$CAS = $_POST['CAS'];
$itemTitle = $_POST['ItemName'];
$itemIndustry = $_POST['Industry'];
$price = $_POST['Price'];
$currency = $_POST['Currency'];
$locationFocus = $_POST['LocationFocus'];
$typeOfData = $_POST['TypeOfData'];
$File = $_FILES['DataFile'];
$Mime = $_FILES['DataFile']['type'];

//Convert into small case


// $Mime = "Mime";
$titleKeywords = RakePlus::create($itemTitle)->get();

//Convert the keywords to lowercase
for($var = 0 ; $var < sizeof($titleKeywords) ; $var++){
    $titleKeywords[$var] = strtolower($titleKeywords[$var]);
}


$descriptionKeywords = RakePlus::create($itemDescription)->get();
for($var = 0 ; $var < sizeof($descriptionKeywords) ; $var++){
    $descriptionKeywords[$var] = strtolower($descriptionKeywords[$var]);
}

//create an array of all keywords
$allKeywords = array_merge($titleKeywords,$descriptionKeywords);


$keywordString = join(":",$allKeywords);
// echo($keywordString);

$generatedUUID = uniqid("",true);

$generateUniqueHash = substr(md5($File['name']),8).substr(uniqid("",true),10);

// Upload to AWS S3
$result = $s3->putObject(array(
    'Bucket' => $bucket,
    'Key'    => "upload-data/".$generateUniqueHash,
    'SourceFile'   => $File['tmp_name'],
)) or die(" Error in uploading the file to server");

// var_dump($Mime);
// var_dump($generateUniqueHash);
// Insert into the partner-uploads table

$partnerUploadTable = "INSERT INTO `partner-uploads` (`id`,`partner_id`,`uuid`,`information_type`,`mime`,`downloads`,`url`)VALUES(NULL,'$id','$generatedUUID','$typeOfData','$Mime','0','$generateUniqueHash')";
mysqli_query($conn,$partnerUploadTable) or die("error uploading to partner uploads table");

$getItemIndexInUploadTable = mysqli_insert_id($conn);
echo($getItemIndexInUploadTable);

// Insert into codes table

$codesInsert = "INSERT INTO `item-codes` (`id`,`item_id`,`uuid`,`CAS`,`grade`)VALUES(NULL,'$getItemIndexInUploadTable','$generatedUUID','$CAS','$itemGrade')";
mysqli_query($conn,$codesInsert);

$codesIndex = mysqli_insert_id($conn);


//Insert into metadata table
$metaDataInsert = "INSERT INTO `item-metadata` (`id`, `item_id`, `description`, `title`, `data_of_industry`,`location_focus`, `keywords`, `data_rating`,`price`,`currency`, `partner_id`, `code_index`, `uuid`,`upload_date`) VALUES (NULL, '$getItemIndexInUploadTable', '$itemDescription', '$itemTitle', '$itemIndustry','$locationFocus', '$keywordString', '0 ','$price','$currency' ,'$id', '$codesIndex', '$generatedUUID',CURRENT_DATE)";

try{
    mysqli_query($conn,$metaDataInsert);
}catch(Exception $e){
    echo $e->getMessage();
}
// echo( " Item ID".$getItemIndexInUploadTable." codes index ".$codesIndex );
}else{
    echo (" all feilds not set");
}
?>
