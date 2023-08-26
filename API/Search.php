<?php 
require_once('../vendor/autoload.php');
require_once('key.php'); // API Key
$query=$_POST['searchValue']; // Search value sent via AJAX

$client=new Google\Client(); // Google Library 
$client->setApplicationName("INSERT_APPLICATION_NAME");
$client->setDeveloperKey($key); //Assigning Developer Key

$service=new Google\Service\Books($client);
$results=$service->volumes->listVolumes($query); //Executing Query and getting response in form of array
//Rather than send a large array back I broke it down and only gathered the data I wanted to display / use.
$response_package=array(); // Array I'm sending back to the index
foreach($results->getItems() as $items){ // For each record in the array
    $volInfo=$items['volumeInfo']; // Multidimensional Array
    $title=$volInfo['title']; 
    $subtitle=$volInfo['subtitle']; 
    $authors=$volInfo['authors'];
    if(isset($volInfo['imageLinks'])){ // Ensure that the image isn't blank
        $image=$volInfo['imageLinks']['smallThumbnail'];
    }else{
        $image=null;
    }
    $link=$volInfo['infoLink'];

    //Packaging up all the info
    $response_package[]=array('title'=>$title,'sub'=>$subtitle,'auth'=>$authors,'desc'=>$dsecription,'rate'=>$averageRating,'image'=>$image,'link'=>$link);
}

// Sending it back as a JSON array
echo(json_encode($response_package));

?>