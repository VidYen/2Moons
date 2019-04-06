<?php

//This file is to curl the stats for the interface system.

function vidyen_user_id()
{

  if (isset($_GET['userid']))
  {
    $user_id = 'ctmoons'.intval($_GET['userid']);
  }
  else
  {
    $user_id = '';
  }

  return $user_id;
}

/* The get curl */

$url = 'https://api.moneroocean.stream/miner/8BpC2QJfjvoiXd8RZv3DhRWetG7ybGwD8eqG9MZoZyv7aHRhPzvrRF43UY1JbPdZHnEckPyR4dAoSSZazf5AY5SS9jrFAdb/stats/'.vidyen_user_id();

$mo = curl_init();
curl_setopt($mo, CURLOPT_URL, $url);
curl_setopt($mo, CURLOPT_HEADER, 0);
curl_setopt($mo, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($mo);
curl_close($mo);

$jsonData = json_decode($result, true);
$last_hash_time =  $jsonData['lts'];
$hash =  $jsonData['hash7'];
$hash2 =  $jsonData['hash2'];
$balance = $jsonData['totalHash'];

$ctmoons_time = time();

//https://www.unixtimestamp.com/

if (($ctmoons_time -$last_hash_time) <= 360 )
{
  $result = intval($hash2);
}
else
{
  $result = 0;
}
//Here goes the cleansing. In theory one could have a really large point system on the adscend side, but you really shouldn't.
//$balance = intval($balance);

echo $result;

?>
