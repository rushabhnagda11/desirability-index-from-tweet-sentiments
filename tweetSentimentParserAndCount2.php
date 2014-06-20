<?php
require_once 'lib/Unirest.php';
$product = urlencode(trim($argv[1]));
$file = $argv[2];
/*$cmd = "curl --data-binary @".$file." http://www.sentiment140.com/api/bulkClassify?query=\"".$product."\"";
$result2 = array();
#echo $cmd;
exec($cmd,$result2);
$sentiments = array();

foreach($result2 as $result) {
    $rArray = split(",",$result);
    $sentiment =  $rArray[0];
    if(isset($sentiments[$sentiment])) {
        $sentiments[$sentiment]++;
    }
    else {
        $sentiments[$sentiment] = 1;
    }
    
}
#echo print_r($sentiments,1);
if(!isset($sentiments['"4"'])) {
    $sentiments['"4"'] = 0;
}
if(!isset($sentiments['"0"'])) {
    $sentiments['"0"'] = 0;
}
$netPositiveSentiment = $sentiments['"4"'] - $sentiments['"0"'];
echo $argv[1]."\t".$sentiments['"4"']."\t". $sentiments['"0"']."\t".$netPositiveSentiment/count($result2);
*/
$response = Unirest::post(
  "http://text-processing.com/api/sentiment/",
  array(
    "X-Mashape-Authorization" => "jiBmMceod1ieZfH1Kmx6PHCbVy5fFzHK"
  ),
  array(
    "text" => urlencode(file_get_contents($file)),
    "language" => "english"
  )
);

$probability = $response->__get('body')->probability;

?>

