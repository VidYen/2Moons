//Refresh the MO
function vidyen_timer()
{
  //Should call ajax every 30 seconds
  var ajaxTime = 1;
  var id = setInterval(vidyen_TimeFrame, 1000); //1000 is 1 second
  function vidyen_TimeFrame()
  {
    if (ajaxTime >= 30)
    {
      //pull_mo_stats();
      console.log('Ping MoneroOcean');
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
    }
    //Hash work
    hash_difference = totalhashes - prior_totalhashes;
    hash_per_second_estimate = (hash_difference);
    reported_hashes = Math.round(totalhashes);
    prior_totalhashes = totalhashes;
    //progresspoints = totalhashes - ( Math.floor( totalhashes / 10000 ) * 10000 );
    totalpoints = Math.floor( totalhashes / 10000 );
    //document.getElementById('progress_text').innerHTML = 'Reward[' + '<img src="https://www.vidyen.com/wp-content/uploads/2018/06/favicon-1.png" width="16" height="16" title="VidYen"> ' + totalpoints + '] - Progress[' + progresspoints + '/' + 10000 + ']';
    document.getElementById('progress_text').innerHTML = 'Effort[' + reported_hashes + ']';
    document.getElementById('hash_rate').innerHTML = ' ' + hash_per_second_estimate + ' H/s' + ' [' + job.algo + ']';
    progresswidth = (( reported_hashes / 10000  ) - Math.floor( reported_hashes / 10000 )) * 100;
    elemworkerbar.style.width = progresswidth + '%'

    //Check server is up
    if (serverError > 0)
    {
      console.log('Server is down attempting to repick!');
      repickServer();
      console.log('Server repicked!');
    }
  }