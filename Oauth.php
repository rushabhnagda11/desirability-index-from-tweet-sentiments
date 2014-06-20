<?php


define('CONSUMER_KEY', 'B2mS4OD8GA1pd9HMzdRSQ');
define('CONSUMER_SECRET', 'jrS4AeC6LDoJ519ngF57QPYxf4DqqvaAGLwJ7XlHYo');
function get_bearer_token(){
	$encoded_consumer_key = urlencode(CONSUMER_KEY);
	$encoded_consumer_secret = urlencode(CONSUMER_SECRET);
	$bearer_token = $encoded_consumer_key.':'.$encoded_consumer_secret;
	$base64_encoded_bearer_token = base64_encode($bearer_token);
	$url = "https://api.twitter.com/oauth2/token"; // url to send data to for authentication
	$headers = array( 
		"POST /oauth2/token HTTP/1.1", 
		"Host: api.twitter.com", 
		"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
		"Authorization: Basic ".$base64_encoded_bearer_token."",
		"Content-Type: application/x-www-form-urlencoded;charset=UTF-8", 
		"Content-Length: 29"
	); 

	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL,$url); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials"); 
	$header = curl_setopt($ch, CURLOPT_HEADER, 1); 
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$retrievedhtml = curl_exec ($ch); 
	curl_close($ch);
	$output = explode("\n", $retrievedhtml);
	$bearer_token = '';
	foreach($output as $line)
	{
		if($line === false)
		{
			// there was no bearer token
		}else{
			$bearer_token = $line;
		}
	}
	$bearer_token = json_decode($bearer_token);
	return $bearer_token->{'access_token'};
}

function invalidate_bearer_token($bearer_token){
	$encoded_consumer_key = urlencode(CONSUMER_KEY);
	$encoded_consumer_secret = urlencode(CONSUMER_SECRET);
	$consumer_token = $encoded_consumer_key.':'.$encoded_consumer_secret;
	$base64_encoded_consumer_token = base64_encode($consumer_token);
	// step 2
	$url = "https://api.twitter.com/oauth2/invalidate_token"; 
	$headers = array( 
		"POST /oauth2/invalidate_token HTTP/1.1", 
		"Host: api.twitter.com", 
		"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
		"Authorization: Basic ".$base64_encoded_consumer_token."",
		"Accept: */*", 
		"Content-Type: application/x-www-form-urlencoded", 
		"Content-Length: ".(strlen($bearer_token)+13).""
	); 
    
	$ch = curl_init();  
	curl_setopt($ch, CURLOPT_URL,$url);  
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, "access_token=".$bearer_token.""); 
	$header = curl_setopt($ch, CURLOPT_HEADER, 1); 
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$retrievedhtml = curl_exec ($ch); 
	curl_close($ch); 
	return $retrievedhtml;
}

function search_for_a_term($bearer_token, $query, $result_type='mixed', $count='15'){
	$url = "https://api.twitter.com/1.1/search/tweets.json"; 
	$q = urlencode(trim($query)); 
	$formed_url ='?q='.$q; 
	if($result_type!='mixed'){$formed_url = $formed_url.'&result_type='.$result_type;} 
	if($count!='15'){$formed_url = $formed_url.'&count='.$count;} 
	$formed_url = $formed_url.'&include_entities=true'; 
	$headers = array( 
		"GET /1.1/search/tweets.json".$formed_url." HTTP/1.1", 
		"Host: api.twitter.com", 
		"User-Agent: jonhurlock Twitter Application-only OAuth App v.1",
		"Authorization: Bearer ".$bearer_token."",
	);
	$ch = curl_init();  // setup a curl
	curl_setopt($ch, CURLOPT_URL,$url.$formed_url);  // set url to send to
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // set custom headers
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return output
	$retrievedhtml = curl_exec ($ch); // execute the curl
	curl_close($ch); // close the curl
	return $retrievedhtml;
}

function getAffinityLike($query) {
    $q = array();
    $q['query'] = $query;
    $q['text'] = "I love ".$query;
    $data = "{\"data\":[".json_encode($q)."]}";
    echo $data;
    $result2  =array();
    $cmd = "curl  -d '".$data."' http://www.sentiment140.com/api/bulkClassifyJson";
    $result2 = exec($cmd,$result2);
    echo $cmd;
    //echo "result 2 = ".print_r($result2,1);
    return $result2;
}

$bearer_token = get_bearer_token(); // get the bearer token
//print search_for_a_term($bearer_token, "\"moto g\"", "recent"); 
getAffinityLike("moto g");
?>
