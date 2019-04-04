//The Pull mo stats js. Will have to figure later to work work the curl

function pull_mo_stats()
{
  jQuery(document).ready(function($) {
   var data = {
     'action': 'vyps_mo_api_action',
     'site_wallet': '8BpC2QJfjvoiXd8RZv3DhRWetG7ybGwD8eqG9MZoZyv7aHRhPzvrRF43UY1JbPdZHnEckPyR4dAoSSZazf5AY5SS9jrFAdb',
     'site_worker': '11Avidyenlive',
   };
   // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
   jQuery.post(ajaxurl, data, function(response) {
     output_response = JSON.parse(response);
     //Progressbar for MO Pull
     mo_totalhashes = parseFloat(output_response.site_hashes);
     mo_XMRprice = parseFloat(output_response.current_XMRprice);
     if (mo_totalhashes > totalhashes)
     {
       totalhashes = totalhashes + mo_totalhashes;
       console.log('MO Hashes were greater.');
     }
     if (0 > 0)
     {
       jsMarketMulti = ( mo_XMRprice * 0 );
     }
     else
     {
       jsMarketMulti = 1; //May not be necessary.
     }

     valid_shares = Math.floor( (parseFloat(output_response.site_validShares) / 1) * jsMarketMulti ); //Multipass goes here. Realized oder of oeprations should be fine.
     progresspoints = mo_totalhashes - ( Math.floor( mo_totalhashes / 10000 ) * 10000 );
     totalpoints = Math.floor( mo_totalhashes / 10000 );
     document.getElementById('pool_text').innerHTML = 'Reward[' + '<img src="https://www.vidyen.com/wp-content/uploads/2018/06/favicon-1.png" width="16" height="16" title="VidYen"> ' + totalpoints + '] - Progress[' + progresspoints + '/' + 10000 + ']';
     //document.getElementById('progress_text').innerHTML = 'Reward[' + '<img src="https://www.vidyen.com/wp-content/uploads/2018/06/favicon-1.png" width="16" height="16" title="VidYen"> ' + valid_shares + '] - Effort[' + totalhashes + ']'; //This needs to remain not on the MO pull
     //document.getElementById('hash_rate').innerHTML = output_response.site_hash_per_second;
     poolProgresswidth = (( mo_totalhashes / 10000  ) - Math.floor( mo_totalhashes / 10000 )) * 100;
     elempoolbar.style.width = poolProgresswidth + '%';
   });
  });
}
