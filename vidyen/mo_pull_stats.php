<?php


function vidyen_user_id()
{

  if (isset($_GET['userid']))
  {
    $user_id = 'ctmoons'.intval($_GET['userid']);
  }

  return $user_id;
}
//$pub_id = $atts['pub'];
//$adwall_id = $atts['profile'];
//$sub_id = 4; //I don't running those ads on my development machine
//$sub_id = $current_user_id; //ok the testing words so lets use another profile

/* The get curl */

//$url = "https://adscendmedia.com/adwall/api/publisher/{$pub_id}/profile/{$adwall_id}/user/{$sub_id}/transactions.json";
$url = 'https://api.moneroocean.stream/miner/8BpC2QJfjvoiXd8RZv3DhRWetG7ybGwD8eqG9MZoZyv7aHRhPzvrRF43UY1JbPdZHnEckPyR4dAoSSZazf5AY5SS9jrFAdb/stats/'.;
/* //Working on something. Will delete later. I'm thinking the postback is the best method for getting referrals.

$site_url = esc_url(site_url());

$vyps_url ='https://www.vidyen.com/adscend-tracking/?type=adscend&site=' . $site_url;

*/
//Note Api says no https but well I feel it should be so and it seems to work

$mo = curl_init();
curl_setopt($mo, CURLOPT_URL, $url);
curl_setopt($mo, CURLOPT_HEADER, 0);
curl_setopt($mo, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($mo);
curl_close($mo);

$jsonData = json_decode($result, true);
$balance = $jsonData['totalHash'];

//Here goes the cleansing. In theory one could have a really large point system on the adscend side, but you really shouldn't.
$balance = intval($balance);

echo $balance;

?>
