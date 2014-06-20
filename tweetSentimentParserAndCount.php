<?php
$product = urlencode(trim($argv[1]));
$file = $argv[2];
$cmd = "curl --data-binary @".$file." http://www.sentiment140.com/api/bulkClassify?query=".$product;
$result2 = array();
echo $cmd;
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
echo print_r($sentiments,1);
$netPositiveSentiment = $sentiments['"4"'] - $sentiments['"0"'];
echo $product."\t".$netPositiveSentiment/count($result2);
?>

