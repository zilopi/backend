<?php
require 'vendor/autoload.php';
require "database-con.php";
require "aws-credentials.php";
$uri = $_POST['uri'];
$mime = $_POST['mime'];
$url = $s3->getCommand('GetObject', [
    'Bucket' => $bucket,
    'Key'    => "upload-data/".$uri
]);
$request = $s3->createPresignedRequest($url, '+20 minutes');
    $presignedUrl = (string) $request->getUri();
    echo $presignedUrl;


    
?>