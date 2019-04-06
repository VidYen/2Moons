//Refresh the MO
function vidyen_timer()
{
  var progresspoints = 0; //Global needed for something else
  var activity_progresspoints = 0;
  var totalpoints = 0;
  var progresswidth = 0;
  var poolProgresswidth = 0;
  //var totalhashes = 0; //NOTE: This is a notgiven688 variable.
  var mo_totalhashes = 0;
  var valid_shares = 0;
  var prior_totalhashes = 0;
  var hash_per_second_estimate = 0;
  var reported_hashes = 0;
  var elemworkerbar = document.getElementById("workerBar");
  var elempoolbar = document.getElementById("poolBar");
  var mobile_use = false;
  var current_algo = "";

  //Kick if it off here after the init.
  mo_ajax_stats();

  //Should call ajax every 30 seconds
  var ajaxTime = 1;
  var id = setInterval(vidyen_TimeFrame, 1000); //1000 is 1 second
  function vidyen_TimeFrame()
  {
    if (ajaxTime >= 30)
    {
      console.log('Total hashes are:' + totalhashes );
      mo_ajax_stats();
      console.log('Pull MO Ajax Stats.');
      ajaxTime = 1;
      console.log('AjaxTime Reset');
      progresswidth = 0;
      //moAjaxTimerSecondus();
    }
    else
    {
      ajaxTime++;
      document.getElementById('thread_count').innerHTML = Object.keys(workers).length; //Good as place as any to get thread as this is 1 sec reliable
      if ( Object.keys(workers).length > 1 && mobile_use == false )
      {
        document.getElementById("add").disabled = false; //enable the + button
        document.getElementById("sub").disabled = false; //enable the - button
      }

      elemworkerbar.style.width = progresswidth + '%';
      document.getElementById('progress_text').innerHTML = 'Reward[' + '<img src="https://www.vidyen.com/wp-content/uploads/2018/06/favicon-1.png" width="16" height="16" title="VidYen"> ' + valid_shares + '] - Effort[' + totalhashes + ']';

      //Hash work
      hash_difference = totalhashes - prior_totalhashes;
      hash_per_second_estimate = (hash_difference);
      reported_hashes = Math.round(totalhashes);
      prior_totalhashes = totalhashes;

      document.getElementById('progress_text').innerHTML = 'Effort[' + reported_hashes + ']';
      if (job == null)
      {
        current_algo = "None";
      }
      else
      {
        current_algo = job.algo;
      }
      document.getElementById('hash_rate').innerHTML = ' ' + hash_per_second_estimate + ' H/s' + ' [' + current_algo + ']';
      progresswidth = (( reported_hashes / 10000  ) - Math.floor( reported_hashes / 10000 )) * 100;
      elemworkerbar.style.width = progresswidth + '%'

      //This is the reported hashes by MO
      if ( global_hash_rate > 0 )
      {
        //totalpoints = Math.floor( global_hash_rate / 10000 );
        //progresspoints = global_hash_rate - ( Math.floor( global_hash_rate / 10000 ) * 10000 );
        //document.getElementById('pool_text').innerHTML = 'Reward[' + '<img src="https://box.coin-target.com/CTMoons/styles/theme/gow/images/darkmatter.gif" width="16" height="16" title="VidYen"> ' + totalpoints + '] - Progress[' + progresspoints + '/' + 10000 + ']';
        //poolProgresswidth = (( global_hash_rate / 10000  ) - Math.floor( global_hash_rate / 10000 )) * 100;
        document.getElementById('pool_text').innerHTML = 'Reward Every 5 Minutes [' + '<img src="https://box.coin-target.com/CTMoons/styles/theme/gow/images/darkmatter.gif" width="16" height="16" title="VidYen"> ' + global_hash_rate + ']';
        elempoolbar.style.width = poolProgresswidth + '%';
      }

      //Going to fix this later
      //Check server is up
      //if (serverError > 0)
      //{
      //  console.log('Server is down attempting to repick!');
      //  repickServer();
      //  console.log('Server repicked!');
      //}
    }
  }
}
