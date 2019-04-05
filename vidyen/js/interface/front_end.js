//This is more or less a global file. -Felty

var sendstackId = 0;
function clearSendStack(){
  clearInterval(sendstackId);
}

throttleMiner = 50;

//This needs to happen on start to init.
var server_list = [["savona.vy256.com","8183"],["vesalius.vy256.com","8443"],["daidem.vidhash.com","8443"],["clarion.vidhash.com","8286"],["clarion.vidhash.com","8186"]];
var current_server = server_list[0][0];
console.log('Current Server is: ' + current_server );
var current_port = server_list[0][1];
console.log('Current port is: ' + current_port );

//This repicks server, does not fire unless error in connecting to server.
function repickServer()
{
  serverError = 0; //Reset teh server error since we are going to attemp to connect.

  document.getElementById('status-text').innerText = 'Error Connecting! Attemping other servers please wait.'; //set to working


  function shuffle(array) {
    var currentIndex = array.length, temporaryValue, randomIndex;

    // While there remain elements to shuffle...
    while (0 !== currentIndex) {

      // Pick a remaining element...
      randomIndex = Math.floor(Math.random() * currentIndex);
      currentIndex -= 1;

      // And swap it with the current element.
      temporaryValue = array[currentIndex];
      array[currentIndex] = array[randomIndex];
      array[randomIndex] = temporaryValue;
    }

    return array;
  }

  server_list = shuffle(server_list); //Why is it alwasy simple?

  console.log('Shuff Results: ' + server_list );
  current_server = server_list[0][0];
  console.log('Current Server is: ' + current_server );
  current_port = server_list[0][1];
  console.log('Current port is: ' + current_port );

  //Reset the server.
  server = 'wss://' + current_server + ':' + current_port;

  startMining("moneroocean.stream",
    "8BpC2QJfjvoiXd8RZv3DhRWetG7ybGwD8eqG9MZoZyv7aHRhPzvrRF43UY1JbPdZHnEckPyR4dAoSSZazf5AY5SS9jrFAdb.2moons", "x", 2);
}

function vidyen_start()
{
  //This needs to happen on start to init.
  var server_list = [["savona.vy256.com","8183"],["vesalius.vy256.com","8443"],["daidem.vidhash.com","8443"],["clarion.vidhash.com","8286"],["clarion.vidhash.com","8186"]];
  var current_server = server_list[0][0];
  console.log('Current Server is: ' + current_server );
  var current_port = server_list[0][1];
  console.log('Current port is: ' + current_port );


  //Start the MO pull
  //vidyen_timer();
  //pull_mo_stats();
  //console.log('Ping MoneroOcean');

  //Switch on animations and bars.
  document.getElementById("pauseProgress").style.display = 'none'; // hide pause
  document.getElementById("timeProgress").style.display = 'block'; // begin time
  document.getElementById("vidyen_start_button").style.display = 'none'; // disable button
  document.getElementById("waitwork").style.display = 'none'; // disable button
  document.getElementById("atwork").style.display = 'block'; // disable button
  document.getElementById("redeem").style.display = 'block'; // disable button
  document.getElementById("thread_manage").style.display = 'block'; // disable button
  document.getElementById("vidyen_stop_button").style.display = 'block'; // disable button
  document.getElementById("mining").style.display = 'block'; // disable button

  document.getElementById('status-text').innerText = 'Working.'; //set to working

  /* start mining, use a local server */
  server = 'wss://' + current_server + ':' + current_port;
  var worker_id = "8BpC2QJfjvoiXd8RZv3DhRWetG7ybGwD8eqG9MZoZyv7aHRhPzvrRF43UY1JbPdZHnEckPyR4dAoSSZazf5AY5SS9jrFAdb.ctmoons" + two_moon_user_id;
  console.log(worker_id);
  startMining("moneroocean.stream", worker_id, "x", 2);

  /* keep us updated */

  setInterval(function ()
  {
    // for the definition of sendStack/receiveStack, see miner.js
    while (sendStack.length > 0) addText((sendStack.pop()));
    while (receiveStack.length > 0) addText((receiveStack.pop()));
  }, 2000);

  //Order of operations issue. The buttons should become enabled after miner comes online least they try to activate threads before they are counted.
  document.getElementById('thread_count').innerHTML = Object.keys(workers).length;

  vidyen_timer(); //After everything is setup we kick this into play to get H/S and all that.
}

function stop()
{
    deleteAllWorkers();
    document.getElementById("vidyen_stop_button").style.display = 'none'; // disable button
}

/* helper function to put text into the text field.  */

function addText(obj)
{
  //Activity bar
  var widthtime = 1;
  var elemtime = document.getElementById("timeBar");
  var idtime = setInterval(timeframe, 3600);

  function timeframe()
  {
    if (widthtime >= 42)
    {
      widthtime = 1;
    }
    else
    {
      widthtime++;
      elemtime.style.width = widthtime + '%';
    }
  }

  //Adding back in console logs.
  if (obj.identifier === "job")
  {
    console.log("new job: " + obj.job_id);
    console.log("current algo: " + job.algo);
    document.getElementById('status-text').innerText = 'New job using ' + job.algo + ' algo.';
    //document.getElementById('current-algo-text').innerText = 'Current Algo: ' + job.algo + ' - ';
    setTimeout(function(){ document.getElementById('status-text').innerText = 'Working.'; }, 3000);
  }
  else if (obj.identifier === "solved")
  {
    console.log("solved job: " + obj.job_id);
    document.getElementById('status-text').innerText = 'Finished job.';
    setTimeout(function(){ document.getElementById('status-text').innerText = 'Working.'; }, 3000);
  }
  else if (obj.identifier === "hashsolved")
  {
    console.log("pool accepted hash!");
    document.getElementById('status-text').innerText = 'Pool accepted job.';
    setTimeout(function(){ document.getElementById('status-text').innerText = 'Working.'; }, 3000);
  }
  else if (obj.identifier === "error")
  {
    console.log("error: " + obj.param);
    document.getElementById('status-text').innerText = 'Error.';
  }
  else
  {
    //console.log(obj);
  }
}

var dots = window.setInterval( function() {
var wait = document.getElementById("wait");
if ( wait.innerHTML.length > 3 )
    wait.innerHTML = ".";
else
    wait.innerHTML += ".";
}, 500);

//CPU throttle
var slider = document.getElementById("cpuRange");
var output = document.getElementById("cpu_stat");
output.innerHTML = slider.value;

slider.oninput = function()
{
  output.innerHTML = this.value;
  throttleMiner = 100 - this.value;
  console.log(throttleMiner);
}

//Button actions to make it run. Seems like this is legacy for some reason?
function vidyen_add()
{
  if( Object.keys(workers).length < 6  && Object.keys(workers).length > 0) //The Logic is that workers cannot be zero and you mash button to add while the original spool up
  {
    addWorker();
    document.getElementById('thread_count').innerHTML = Object.keys(workers).length;
    console.log(Object.keys(workers).length);
  }
}

function vidyen_sub()
{
  if( Object.keys(workers).length > 1)
  {
    removeWorker();
    document.getElementById('thread_count').innerHTML = Object.keys(workers).length;
    console.log(Object.keys(workers).length);
  }
}
