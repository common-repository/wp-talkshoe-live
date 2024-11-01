<?php
/**
* Plugin Name: WP Talkshoe Live
* Plugin URI: http://www.nmpnetwork.com/wordpress-plugins/wp-talkshoe-live
* Description: This plugin will allow you to post current live calls from Talkshoe on your blog and allow visitors to tweet to their personal Twitter accounts and launch the Talkshoe Live Pro client.
* Version: 1.1
*
* Author: Dr. Robert White
* Author URI: http://www.nmpnetwork.com
*/
 
/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) 2010 GraiteSites Services                                  |
* +--------------------------------------------------------------------------+
* | This program is free software; you can redistribute it and/or modify     |
* | it under the terms of the GNU General Public License as published by     |
* | the Free Software Foundation; either version 2 of the License, or        |
* | (at your option) any later version.                                      |
* |                                                                          |
* | This program is distributed in the hope that it will be useful,          |
* | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
* | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
* | GNU General Public License for more details.                             |
* |                                                                          |
* | You should have received a copy of the GNU General Public License        |
* | along with this program; if not, write to the Free Software              |
* | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA |
* +--------------------------------------------------------------------------+
*/

/*********************************************************************
 * File: wptalkshoelive.php
 * Author: Dr. Robert White
 * Contact: graitesites@gmail.com
 * Company: The NMP Network [http://www.nmpnetwork.com]
 * Date Created: December 11th, 2010
 * Project Name: WP Talkshoe Live
 * Description:
 *        Add Twitter and Talkshoe Live Pro client abilities to your blog.
 * Copyright © 2010 - GraiteSites Services
 *********************************************************************/
 
// define(WP_Talkshoe_Live, "WP_Talkshoe_Live");
 
// WP_Talkshoe Live Version

$wptlversion = "1.1";
update_option('wptlversion',$wptlversion);
$wptl_title = "WP Talkshoe Live";
update_option('wptl_title',$wptl_title);

// Wordpress Version check 

global $wp_version;
$exit_msg='WP_Talkshoe requires Wordpress 3.0 or newer. Please update!';
if (version_compare($wp_version,"3.0","<"))
{
	exit ($exit_msg);
}

// Wordpress Plugins Path

$pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));

//WordPress Hooks 

    add_shortcode('wp_talkshoe_live', 'run_wptl'); 

//Wordpress Plugin Code

add_action("widgets_init", array('WP_Talkshoe_Live', 'registerwptl'));
register_activation_hook( __FILE__, array('WP_Talkshoe_Live', 'activatewptl'));
register_deactivation_hook( __FILE__, array('WP_Talkshoe_Live', 'deactivatewptl'));

class WP_Talkshoe_Live {
  function activatewptl(){
    $data1 = array( 'option1' => 'Default value' ,'option2' => 55);
    if ( ! get_option('WP_Talkshoe_Live')){
      add_option('WP_Talkshoe_Live' , $data1);
    } else {
      update_option('WP_Talkshoe_Live' , $data1);
    }
  }
  function deactivatewptl(){
    delete_option('WP_Talkshoe_Live');
  }

  function widget($args){
    echo $args['before_widget'];
    echo $args['before_title'] . 'WP Talkshoe Live' . $args['after_title'];
    run_wptl();
    echo $args['after_widget'];
  }
  function registerwptl(){
    register_sidebar_widget('WP Talkshoe Live', array('WP_Talkshoe_Live', 'widget'));
  }
}
	
// Talkshoe API Code

function run_wptl() 

	{ 

	$conn = new LiveShowsConnection();
	$parser = new EpisodeListParser;
	$episodes = $parser->parse($conn->xml());
	$episode = $episodes[0];
	$parser1 = new TalkcastInformationParser;
	$talkcast = $parser1->parse($conn->xml());
	
      // FETCH EPISODE LIST IN DROPDOWN BOX
      $addr = 'http://api.talkshoe.com/TalkShoeServices/services/TalkcastService/getLiveShows';

      $ch = curl_init();
      curl_setopt ($ch, CURLOPT_URL, $addr);
      curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
      curl_setopt ($ch, CURLOPT_TIMEOUT, 1200);
      $contents = curl_exec($ch);
      curl_close($ch);

      preg_match_all ("'<talkcastId>(.*?)</talkcastId>'si", $contents, $tcid);
      preg_match_all ("'<talkcastName>(.*?)</talkcastName>'si", $contents, $tctitle);
	  preg_match_all ("'<episodeName>(.*?)</episodeName>'si", $contents, $epname);
	  preg_match_all ("'<streaming>(.*?)</streaming>'si", $contents, $sstream);
	  preg_match_all ("'<episodeId>(.*?)</episodeId>'si", $contents, $epid);
      $tcid = $tcid[1];
      $tctitle = $tctitle[1];
	  $epname = $epname[1];
	  $sstream = $sstream[1];
	  $epid = $epid[1];
      $count = count($tcid);
	  $tweet = "http://twitter.com/home?status=";
	  $wptlversion=get_option('wptlversion');
	
// Wordpress Plugins Path

$pluginpath = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));	
	
list($show1,$show2,$show3,$show4,$show5,$show6,$show7,$show8,$show9,$show10,$show11,$show12,$show13,$show14,$show15,$show16,$show17,$show18,$show19,$show20) = $tctitle;
list($showid1,$showid2,$showid3,$showid4,$showid5,$showid6,$showid7,$showid8,$showid9,$showid10,$showid11,$showid12,$showid13,$showid14,$showid15,$showid16,$showid17,$showid18,$showid19,$showid20) = $tcid;
list($showst1,$showst2,$showst3,$showst4,$showst5,$showst6,$showst7,$showst8,$showst9,$showst10,$showst11,$showst12,$showst13,$showst14,$showst15,$showst16,$showst17,$showst18,$showst19,$showst20) = $sstream;
list($showen1,$showen2,$showen3,$showen4,$showen5,$showen6,$showen7,$showen8,$showen9,$showen10,$showen11,$showen12,$showen13,$showen14,$showen15,$showen16,$showen17,$showen18,$showen19,$showen20) = $epname;
list($showeid1,$showeid2,$showeid3,$showeid4,$showeid5,$showeid6,$showeid7,$showeid8,$showeid9,$showeid10,$showeid11,$showeid12,$showeid13,$showeid14,$showeid15,$showeid16,$showeid17,$showeid18,$showeid19,$showeid20) = $epid;

echo "<table style='border: 5px solid black; width:230px'>";
echo "<tr><td style='background-color: white;border: 5px solid black'>";
echo "<b>Please Select A Show:</b>";
echo "</td></tr>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";
if ($showst1 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid1' target='_blank'>".$show1."</a><br>Show ID: ".$showid1."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show1 = urlencode(urlencode($show1));
			$message = $show1." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid1;
			$message .= " and http://www.talkshoe.com/tc/".$showid1;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
            echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid1/$showeid1' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			} else {
if ($show1 == "") {	
// No Display if No Show
}else{		
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid1' target='_blank'>".$show1."</a><br>Show ID: ".$showid1."<br></b></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show1 = urlencode(urlencode($show1));
			$message = $show1." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid1;
			$message .= " and http://www.talkshoe.com/tc/".$showid1;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
            echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid1/$showeid1' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>";
if ($showst2 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid2' target='_blank'>".$show2."</a><br>Show ID: ".$showid2."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show2 = urlencode(urlencode($show2));
			$message = $show2." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid2;
			$message .= " and http://www.talkshoe.com/tc/".$showid2;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid2/$showeid2' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show2 == "") {	
// No Display if No Show
}else{		
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid2' target='_blank'>".$show2."</a><br>Show ID: ".$showid2."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show2 = urlencode(urlencode($show2));
			$message = $show2." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid2;
			$message .= " and http://www.talkshoe.com/tc/".$showid2;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid2/$showeid2' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";		
if ($showst3 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid3' target='_blank'>".$show3."</a><br>Show ID: ".$showid3."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show3 = urlencode(urlencode($show3));
			$message = $show3." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid3;
			$message .= " and http://www.talkshoe.com/tc/".$showid3;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid3/$showeid3' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show3 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid3' target='_blank'>".$show3."</a><br>Show ID: ".$showid3."<br></font><font color='red'><h2>is Live NOW!</h2><br></b></font></center>";
			//create the message:
			$show3 = urlencode(urlencode($show3));
			$message = $show3." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid3;
			$message .= " and http://www.talkshoe.com/tc/".$showid3;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid3/$showeid3' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst4 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid4' target='_blank'>".$show4."</a><br>Show ID: ".$showid4."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show4 = urlencode(urlencode($show4));
			$message = $show4." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid4;
			$message .= " and http://www.talkshoe.com/tc/".$showid4;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid4/$showeid4' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show4 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid4' target='_blank'>".$show4."</a><br>Show ID: ".$showid4."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show4 = urlencode(urlencode($show4));
			$message = $show4." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid4;
			$message .= " and http://www.talkshoe.com/tc/".$showid4;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid4/$showeid4' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";					
if ($showst5 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid5' target='_blank'>".$show5."</a><br>Show ID: ".$showid5."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show5 = urlencode(urlencode($show5));
			$message = $show5." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid5;
			$message .= " and http://www.talkshoe.com/tc/".$showid5;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid5/$showeid5' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show5 == "") {	
// No Display if No Show
}else{							
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid5' target='_blank'>".$show5."</a><br>Show ID: ".$showid5."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show5 = urlencode(urlencode($show5));
			$message = $show5." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid5;
			$message .= " and http://www.talkshoe.com/tc/".$showid5;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid5/$showeid5' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";					
if ($showst6 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid6' target='_blank'>".$show6."</a><br>Show ID: ".$showid6."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show6 = urlencode(urlencode($show6));
			$message = $show6." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid6;
			$message .= " and http://www.talkshoe.com/tc/".$showid6;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid6/$showeid6' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show6 == "") {	
// No Display if No Show
}else{								
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid6' target='_blank'>".$show6."</a><br>Show ID: ".$showid6."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show6 = urlencode(urlencode($show6));
			$message = $show6." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid6;
			$message .= " and http://www.talkshoe.com/tc/".$showid6;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid6/$showeid6' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";				
if ($showst7 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid7' target='_blank'>".$show7."</a><br>Show ID: ".$showid7."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show7 = urlencode(urlencode($show7));
			$message = $show7." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid7;
			$message .= " and http://www.talkshoe.com/tc/".$showid7;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid7/$showeid7' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show7 == "") {	
// No Display if No Show
}else{							
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid7' target='_blank'>".$show7."</a><br>Show ID: ".$showid7."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show7 = urlencode(urlencode($show7));
			$message = $show7." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid7;
			$message .= " and http://www.talkshoe.com/tc/".$showid7;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid7/$showeid7' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst8 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid8' target='_blank'>".$show8."</a><br>Show ID: ".$showid8."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show8 = urlencode(urlencode($show8));
			$message = $show8." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid8;
			$message .= " and http://www.talkshoe.com/tc/".$showid8;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid8/$showeid8' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show8 == "") {	
// No Display if No Show
}else{								
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid8' target='_blank'>".$show8."</a><br>Show ID: ".$showid8."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show8 = urlencode(urlencode($show8));
			$message = $show8." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid8;
			$message .= " and http://www.talkshoe.com/tc/".$showid8;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid8/$showeid88' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";				
if ($showst9 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid9' target='_blank'>".$show9."</a><br>Show ID: ".$showid9."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show9 = urlencode(urlencode($show9));
			$message = $show9." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid9;
			$message .= " and http://www.talkshoe.com/tc/".$showid9;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid9/$showeid9' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show9 == "") {	
// No Display if No Show
}else{							
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid9' target='_blank'>".$show9."</a><br>Show ID: ".$showid9."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show9 = urlencode(urlencode($show9));
			$message = $show9." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid9;
			$message .= " and http://www.talkshoe.com/tc/".$showid9;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid9/$showeid9' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst10 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid10' target='_blank'>".$show10."</a><br>Show ID: ".$showid10."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show10 = urlencode(urlencode($show10));
			$message = $show10." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid10;
			$message .= " and http://www.talkshoe.com/tc/".$showid10;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid10/$showeid10' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show10 == "") {	
// No Display if No Show
}else{							
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid10' target='_blank'>".$show10."</a><br>Show ID: ".$showid10."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show10 = urlencode(urlencode($show10));
			$message = $show10." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid10;
			$message .= " and http://www.talkshoe.com/tc/".$showid10;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid10/$showeid10' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";				
if ($showst11 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid11' target='_blank'>".$show11."</a><br>Show ID: ".$showid11."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show11 = urlencode(urlencode($show11));
			$message = $show11." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid11;
			$message .= " and http://www.talkshoe.com/tc/".$showid11;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid11/$showeid11' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show11 == "") {	
// No Display if No Show
}else{						
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid11' target='_blank'>".$show11."</a><br>Show ID: ".$showid11."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show11 = urlencode(urlencode($show11));
			$message = $show11." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid11;
			$message .= " and http://www.talkshoe.com/tc/".$showid11;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid11/$showeid11' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst12 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid12' target='_blank'>".$show12."</a><br>Show ID: ".$showid12."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show12 = urlencode(urlencode($show12));
			$message = $show12." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid12;
			$message .= " and http://www.talkshoe.com/tc/".$showid12;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid12/$showeid12' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show12 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid12' target='_blank'>".$show12."</a><br>Show ID: ".$showid12."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show12 = urlencode(urlencode($show12));
			$message = $show12." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid12;
			$message .= " and http://www.talkshoe.com/tc/".$showid12;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid12/$showeid12' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";			
if ($showst13 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid13' target='_blank'>".$show13."</a><br>Show ID: ".$showid13."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show13 = urlencode(urlencode($show13));
			$message = $show13." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid13;
			$message .= " and http://www.talkshoe.com/tc/".$showid13;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid13/$showeid13' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show13 == "") {	
// No Display if No Show
}else{						
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid13' target='_blank'>".$show13."</a><br>Show ID: ".$showid13."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show13 = urlencode(urlencode($show13));
			$message = $show13." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid13;
			$message .= " and http://www.talkshoe.com/tc/".$showid13;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid13/$showeid13' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst14 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid14' target='_blank'>".$show14."</a><br>Show ID: ".$showid14."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show14 = urlencode(urlencode($show14));
			$message = $show14." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid14;
			$message .= " and http://www.talkshoe.com/tc/".$showid14;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid14/$showeid14' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show14 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid14' target='_blank'>".$show14."</a><br>Show ID: ".$showid14."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show14 = urlencode(urlencode($show14));
			$message = $show14." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid14;
			$message .= " and http://www.talkshoe.com/tc/".$showid14;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid14/$showeid14' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";				
if ($showst15 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid15' target='_blank'>".$show15."</a><br>Show ID: ".$showid15."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show15 = urlencode(urlencode($show15));
			$message = $show15." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid15;
			$message .= " and http://www.talkshoe.com/tc/".$showid15;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid15/$showeid15' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show15 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid15' target='_blank'>".$show15."</a><br>Show ID: ".$showid15."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show15 = urlencode(urlencode($show15));
			$message = $show15." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid15;
			$message .= " and http://www.talkshoe.com/tc/".$showid15;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid15/$showeid15' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst16 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid16' target='_blank'>".$show16."</a><br>Show ID: ".$showid16."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show16 = urlencode(urlencode($show16));
			$message = $show16." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid16;
			$message .= " and http://www.talkshoe.com/tc/".$showid16;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid16/$showeid16' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show16 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid16' target='_blank'>".$show16."</a><br>Show ID: ".$showid16."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show16 = urlencode(urlencode($show16));
			$message = $show16." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid16;
			$message .= " and http://www.talkshoe.com/tc/".$showid16;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid16/$showeid16' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";				
if ($showst17 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid17' target='_blank'>".$show17."</a><br>Show ID: ".$showid17."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show17 = urlencode(urlencode($show17));
			$message = $show17." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid17;
			$message .= " and http://www.talkshoe.com/tc/".$showid17;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid17/$showeid17' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {		
if ($show17 == "") {	
// No Display if No Show
}else{				
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid17' target='_blank'>".$show17."</a><br>Show ID: ".$showid17."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show17 = urlencode(urlencode($show17));
			$message = $show17." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid17;
			$message .= " and http://www.talkshoe.com/tc/".$showid17;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid17/$showeid17' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst18 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid18' target='_blank'>".$show18."</a><br>Show ID: ".$showid18."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show18 = urlencode(urlencode($show18));
			$message = $show18." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid18;
			$message .= " and http://www.talkshoe.com/tc/".$showid18;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid18/$showeid18' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {		
if ($show18 == "") {	
// No Display if No Show
}else{				
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid18' target='_blank'>".$show18."</a><br>Show ID: ".$showid18."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show18 = urlencode(urlencode($show18));
			$message = $show18." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid18;
			$message .= " and http://www.talkshoe.com/tc/".$showid18;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid18/$showeid18' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: yellow;border: 5px solid black'>";				
if ($showst19 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid19' target='_blank'>".$show19."</a><br>Show ID: ".$showid19."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show19 = urlencode(urlencode($show19));
			$message = $show19." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid19;
			$message .= " and http://www.talkshoe.com/tc/".$showid19;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid19/$showeid19' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {	
if ($show19 == "") {	
// No Display if No Show
}else{					
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid19' target='_blank'>".$show19."</a><br>Show ID: ".$showid19."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show19 = urlencode(urlencode($show19));
			$message = $show19." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid19;
			$message .= " and http://www.talkshoe.com/tc/".$showid19;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid19/$showeid19' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";				
if ($showst20 == "false") {
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid20' target='_blank'>".$show20."</a><br>Show ID: ".$showid20."</b><br><h2>Not Currently Streaming<br></h2></font></center>";
			//create the message:
			$show20 = urlencode(urlencode($show20));
			$message = $show20." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid20;
			$message .= " and http://www.talkshoe.com/tc/".$showid20;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big4.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid20/$showeid20' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
} else {
if ($show20 == "") {	
// No Display if No Show
}else{			
echo "<center><b><font color='black'><a href='http://www.talkshoe.com/tc/$showid20' target='_blank'>".$show20."</a><br>Show ID: ".$showid20."</b><br></font><font color='red'><h2>is Live NOW!</h2></font></center>";
			//create the message:
			$show20 = urlencode(urlencode($show20));
			$message = $show20." is Live NOW! ";
			$message .= "Dial 724-444-7444 Call ID ".$showid20;
			$message .= " and http://www.talkshoe.com/tc/".$showid20;
			$message = urldecode($message);
			echo "<a href='$tweet$message' target='_blank'><img src='$pluginpath/tt-twitter-big3.png' border='0' \></a>";
			echo "<a href='http://www.talkshoe.com/talkshoe/web/go2prot/tscmd/tsl/$showid20/$showeid20' target='_blank'><img src='$pluginpath/LaunchTSLiveClassicTR.gif' width='100' border='0'/></a>";
			
			}}
echo "</tr></td>";
echo "<tr><td style='background-color: white;border: 5px solid black'>	";
echo "<center><b><a href='http://www.nmpnetwork.com/wordpress-plugins/wp-talkshoe-live/' target='_blank'>WP Talkshoe Live $wptlversion</a></b></center>";
echo "</td></tr>";
echo "</table>";
}
?>
