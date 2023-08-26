<?php 
require_once('../vendor/autoload.php');
require_once('key.php');
$query=$_POST['searchValue'];

$client=new Google\Client();
$client->setApplicationName("Client_Library_Examples");
$client->setDeveloperKey($key);

$service=new Google\Service\Books($client);
$results=$service->volumes->listVolumes($query);
$response_package=array();
foreach($results->getItems() as $items){
    $volInfo=$items['volumeInfo'];
    $title=$volInfo['title'];
    $subtitle=$volInfo['subtitle'];
    $authors=$volInfo['authors'];
    $dsecription=$volInfo['description'];
    $averageRating=$volInfo['averageRating'];
    if(isset($volInfo['imageLinks'])){
        $image=$volInfo['imageLinks']['smallThumbnail'];
    }else{
        $image=null;
    }
    $link=$volInfo['infoLink'];

    $response_package[]=array('title'=>$title,'sub'=>$subtitle,'auth'=>$authors,'desc'=>$dsecription,'rate'=>$averageRating,'image'=>$image,'link'=>$link);
}

echo(json_encode($response_package));

?>