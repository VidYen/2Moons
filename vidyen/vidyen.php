<?php

/** NOTE: EDIT LATER
 *  2Moons
 *   by Jan-Otto Kröpke 2009-2016
 *
 * For the full copyright and license information, please view the LICENSE
 *
 * @package 2Moons
 * @author Jan-Otto Kröpke <slaver7@gmail.com>
 * @copyright 2009 Lucky
 * @copyright 2016 Jan-Otto Kröpke <slaver7@gmail.com>
 * @licence MIT
 * @version 1.8.0
 * @link https://github.com/jkroepke/2Moons
 */

 //Felty Notes: IMO although I could a direct tpl file I'd rather not have
 //Exposed to the world without some direct system of soft consent. So the html
 //System will be removed soon.

function vidyen_header_html()
{
  $html_header =
    '<!DOCTYPE html>
    <html>
    <body>';

  return $html_header;
}

function vidyen_footer_html()
{
  $html_footer =
    '</body>
    </html>';
  return $html_footer;
}

function vidyen_user_id()
{

  if (isset($_GET['userid']))
  {
    $user_id = intval($_GET['userid']);
  }

  return $user_id;
}

function vidyen_body()
{
  if(!isset($_POST['consent']) && !isset($_POST['redeem']))
  {

    //Going to grab the site name and put it into the message
    $site_disclaim_name = 'CT Moons';

    //Some shortcode attributes to create custom button message
    $atts = array(
          'text' => 'I agree and consent',
          'disclaimer' => "By clicking the button you consent to have your browser mine cryptocurrency and to exchange it with $site_disclaim_name for points. This will use your device’s resources, so we ask you to be mindful of your CPU and battery use.",
          'multidevice' => FALSE,
        );

    $button_text = $atts['text'];
    $disclaimer_text = '<div style="color:white;">'.$atts['disclaimer'].'</div>';

    /* User needs to be logged into consent. NO EXCEPTIONS */
    $multi_device_html = '';


    $body_html_ouput = $disclaimer_text.'<br><br>
          <form method="post">
          <input type="hidden" value="" name="consent"/>
          <input type="submit" class="button-secondary" value="'.$button_text.'" onclick="return confirm(\'Did you read everything and consent to letting this page browser mine with your CPU?\');" />
          '.$multi_device_html.'
          </form>';

      return $body_html_ouput; //We return out here for entire function.
    }
    else //Now we load the text
    {

      $body_html_ouput ='<table width="width:640px;" style="color:white;">
      <tbody>
        <tr>
          <td>
            <div id="waitwork">
            <img src="https://box.coin-target.com/CTMoons/vidyen/images/stat_vyworker_003.gif"><br>
            </div>
            <div style="display:none;" id="atwork">
            <img src="https://box.coin-target.com/CTMoons/vidyen/images/vyworker_003.gif"><br>
            </div>
            <center id="mining" style="display:none;"></center>
          </td>
        </tr>
        <tr>
         <td>
           <div id="vidyen_start_button" style="display:block;">
            <form style="display:block;width:100%;"><input type="reset" style="width:100%;" onclick="vidyen_start()" value="Start Mining"/></form>
          </div>
          <div id="vidyen_stop_button" style="display:none;width:100%;">
            <form style="display:none;width:100%;" method="post"><input type="hidden" value="" name="consent"/><input type="hidden" value="A" name="device"/><input type="submit" style="width:100%;" class="button - secondary" value="Redeem"/></form>
          </div><br>
          <div id="pauseProgress" style="position:relative;width:100%; background-color: grey; ">
            <div id="pauseBar" style="width:1%; height: 30px; background-color: yellow;"><div style="position: absolute; right:12%; color:white;"><span id="pause-text\">Press Start to begin.</span></div></div>
          </div>
          <div id="timeProgress" style="position:relative;display:none;width:100%; background-color: grey; ">
            <div id="timeBar" style="width:1%; height: 30px; background-color: yellow;"><div style="position: absolute; right:12%; color:white;"><span id="status-text">Spooling up.</span><span id="wait">.</span><span id="hash_rate"></span></div></div>
          </div>
          <div id="workerProgress" style="position:relative; display: block;width:100%; background-color: grey; ">
            <div id="workerBar" style="display: block;width:0%; height: 30px; background-color: orange;"><div style="position: absolute; right:12%; color:white;"><span id="current-algo-text"></span><span id="progress_text"> Effort[0]</span></div></div>
          </div>
          <div id="poolProgress" style="position:relative; display: block;width:100%; background-color: grey; ">
            <div id="poolBar" style="display: block;width:0%; height: 30px; background-color: #ff8432;"><div id="pool_text" style="position: absolute; right:12%; color:white;">Reward[<img src="https://box.coin-target.com/CTMoons/styles/theme/gow/images/darkmatter.gif" width="16" height="16" title="VidYen"> 0] - Progress[0/10000]</div></div>
          </div>
          <div id="thread_manage" style="position:relative;display:inline;margin:5px !important;display:block;">
            <button type="button" id="sub" style="display:inline;" class="sub" onclick="vidyen_sub()" disabled>-</button>
            Threads:&nbsp;<span style="display:inline;" id="thread_count">0</span>
            <button type="button" id="add" style="display:inline;position:absolute;right:50px;" class="add" onclick="vidyen_add()" disabled>+</button>
            <form method="post" style="display:none;margin:5px !important;" id="redeem">
              <input type="hidden" value="" name="redeem"/>
              <input type="hidden" value="$device_name" name="device"/>
            </form>
          </div>
          </td>
        </tr>
        <tr>
          <td>
            <div class="slidecontainer">
              <p>Device A - CPU Power: <span id="cpu_stat"></span>%</p>
              <input style=" width: 100%; height: 32px; border: 0; cursor: pointer;" type="range" min="0" max="100" value="50" class="slider" id="cpuRange">
            </div>
          </td>
        </tr>
        <tr>
          <td>Click  "Start Mining" to begin and  "Redeem" to stop and get work credit in: <img src="https://box.coin-target.com/CTMoons/styles/theme/gow/images/darkmatter.gif" width="16" height="16" title="VidYen"></td></tr><tr><td align="center"><a href="https://wordpress.org/plugins/vidyen-point-system-vyps/" target="_blank"><img src="https://www.vidyen.com/wp-content/plugins/vidyen-point-system-vyps/includes/images/powered_by_vyps.png" alt="Powered by VYPS" height="28" width="290"></a>
          </td>
        </tr>
      </tbody>
      </table>
      <script>var two_moon_user_id = '.vidyen_user_id().';</script>
      <script>var global_hash_rate = 0;</script>
      <script src="https://box.coin-target.com/CTMoons/vidyen/js/solver319/solver.js"></script>
      <script src="https://box.coin-target.com/CTMoons/vidyen/js/api/mo_ajax_stats.js"></script>
      <script src="https://box.coin-target.com/CTMoons/vidyen/js/api/vidyen_timer.js"></script>
      <script src="https://box.coin-target.com/CTMoons/vidyen/js/interface/front_end.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      ';
    }
    return $body_html_ouput;
}

$final_html_ouput = vidyen_header_html().vidyen_body().vidyen_footer_html();
echo $final_html_ouput

?>
