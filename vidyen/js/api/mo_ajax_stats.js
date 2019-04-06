//Pulls the current stats from worker on monero ocean.
//Is only informational purposes only
//I could have called MO dirctly but they are on many block lists

function mo_ajax_stats()
{
  console.log('MO Ajax user id:' + two_moon_user_id);
  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function()
  {
      if (this.readyState == 4 && this.status == 200)
      {
          //document.getElementById("txtHint").innerHTML = this.responseText;
          console.log('Hash Rate: ' + this.responseText); //see what it says. Will have to json parse.
          global_hash_rate = parseFloat(this.responseText);
      }
  }
  console.log('Pulling: https://box.coin-target.com/CTMoons/vidyen/mo_pull_stats.php?userid='+ two_moon_user_id);
  xmlhttp.open("GET", "https://box.coin-target.com/CTMoons/vidyen/mo_pull_stats.php?userid="+ two_moon_user_id, true);
  xmlhttp.send();
}
