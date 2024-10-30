<?php
/*
Plugin Name: JR Effects
Plugin URI: http://www.jakeruston.co.uk/2009/12/wordpress-plugin-jr-effects/
Description: Allows you to display cool effects on your website - Such as snow and leaves!
Version: 1.5.6
Author: Jake Ruston
Author URI: http://www.jakeruston.co.uk
*/

/*  Copyright 2010 Jake Ruston - the.escapist22@gmail.com

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

$pluginname="effects";

// Hook for adding admin menus
add_action('admin_menu', 'jr_effects_add_pages');

// action function for above hook
function jr_effects_add_pages() {
    add_options_page('JR Effects', 'JR Effects', 'administrator', 'jr_effects', 'jr_effects_options_page');
}

if (!function_exists("_iscurlinstalled")) {
function _iscurlinstalled() {
if (in_array ('curl', get_loaded_extensions())) {
return true;
} else {
return false;
}
}
}

if (!function_exists("jr_show_notices")) {
function jr_show_notices() {
echo "<div id='warning' class='updated fade'><b>Ouch! You currently do not have cURL enabled on your server. This will affect the operations of your plugins.</b></div>";
}
}

if (!_iscurlinstalled()) {
add_action("admin_notices", "jr_show_notices");

} else {
if (!defined("ch"))
{
function setupch()
{
$ch = curl_init();
$c = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
return($ch);
}
define("ch", setupch());
}

if (!function_exists("curl_get_contents")) {
function curl_get_contents($url)
{
$c = curl_setopt(ch, CURLOPT_URL, $url);
return(curl_exec(ch));
}
}
}

if (!function_exists("jr_effects_refresh")) {
function jr_effects_refresh() {
update_option("jr_submitted_effects", "0");
}
}

register_activation_hook(__FILE__,'effects_choice');

function effects_choice () {
if (get_option("jr_effects_links_choice")=="") {

if (_iscurlinstalled()) {
$pname="jr_effects";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_effects", "1");
wp_schedule_single_event(time()+172800, 'jr_effects_refresh'); 
} else {
$content = "Powered by <a href='http://arcade.xeromi.com'>Free Online Games</a> and <a href='http://directory.xeromi.com'>General Web Directory</a>.";
}

if ($content!="") {
$content=utf8_encode($content);
update_option("jr_effects_links_choice", $content);
}
}

if (get_option("jr_effects_link_personal")=="") {
$content = curl_get_contents("http://www.jakeruston.co.uk/p_pluginslink4.php");

update_option("jr_effects_link_personal", $content);
}
}

// jr_effects_options_page() displays the page content for the Test Options submenu
function jr_effects_options_page() {

    // variables for the field and option names
    $opt_name_1 = 'mt_effects_type';	
    $opt_name_5 = 'mt_effects_plugin_support';
	$opt_name_6 = 'mt_effects_amount';
    $hidden_field_name = 'mt_effects_submit_hidden';
	$data_field_name_1 = 'mt_effects_type';
    $data_field_name_5 = 'mt_effects_plugin_support';
	$data_field_name_6 = 'mt_effects_amount';

    // Read in existing option value from database
	$opt_val_1 = get_option($opt_name_1);
    $opt_val_5 = get_option($opt_name_5);
	$opt_val_6 = get_option($opt_name_6);
    
if (!$_POST['feedback']=='') {
$my_email1="the.escapist22@gmail.com";
$plugin_name="JR Effects";
$blog_url_feedback=get_bloginfo('url');
$user_email=$_POST['email'];
$user_email=stripslashes($user_email);
$subject=$_POST['subject'];
$subject=stripslashes($subject);
$name=$_POST['name'];
$name=stripslashes($name);
$response=$_POST['response'];
$response=stripslashes($response);
$category=$_POST['category'];
$category=stripslashes($category);
if ($response=="Yes") {
$response="REQUIRED: ";
}
$feedback_feedback=$_POST['feedback'];
$feedback_feedback=stripslashes($feedback_feedback);
if ($user_email=="") {
$headers1 = "From: feedback@jakeruston.co.uk";
} else {
$headers1 = "From: $user_email";
}
$emailsubject1=$response.$plugin_name." - ".$category." - ".$subject;
$emailmessage1="Blog: $blog_url_feedback\n\nUser Name: $name\n\nUser E-Mail: $user_email\n\nMessage: $feedback_feedback";
mail($my_email1,$emailsubject1,$emailmessage1,$headers1);
?>

<div class="updated"><p><strong><?php _e('Feedback Sent!', 'mt_trans_domain' ); ?></strong></p></div>

<?php
}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        // Read their posted value
		$opt_val_1 = $_POST[$data_field_name_1];
        $opt_val_5 = $_POST[$data_field_name_5];
		$opt_val_6 = $_POST[$data_field_name_6];
		$opt_val_7 = $_POST["customurl"];
		$opt_val_8 = $_POST["customurl2"];
		$opt_val_9 = $_POST["customurl3"];

        // Save the posted value in the database
		update_option( $opt_name_1, $opt_val_1 );
        update_option( $opt_name_5, $opt_val_5 );
		update_option( $opt_name_6, $opt_val_6 );
		update_option( "mt_effects_custom", $opt_val_7 );
		update_option( "mt_effects_custom2", $opt_val_8 );
		update_option( "mt_effects_custom3", $opt_val_9 );

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.', 'mt_trans_domain' ); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'JR Effects Plugin Options', 'mt_trans_domain' ) . "</h2>";
$blog_url_feedback=get_bloginfo('url');
	$donated=curl_get_contents("http://www.jakeruston.co.uk/p-donation/index.php?url=".$blog_url_feedback);
	if ($donated=="1") {
	?>
		<div class="updated"><p><strong><?php _e('Thank you for donating!', 'mt_trans_domain' ); ?></strong></p></div>
	<?php
	} else {
	if ($_POST['mtdonationjr']!="") {
	update_option("mtdonationjr", "444");
	}
	
	if (get_option("mtdonationjr")=="") {
	?>
	<div class="updated"><p><strong><?php _e('Please consider donating to help support the development of my plugins!', 'mt_trans_domain' ); ?></strong><br /><br /><form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="ULRRFEPGZ6PSJ">
<input type="image" src="https://www.paypal.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online.">
<img alt="" border="0" src="https://www.paypal.com/en_GB/i/scr/pixel.gif" width="1" height="1">
</form></p><br /><form action="" method="post"><input type="hidden" name="mtdonationjr" value="444" /><input type="submit" value="Don't Show This Again" /></form></div>
<?php
}
}

    // options form
    
    $change3 = get_option("mt_effects_plugin_support");
	$change4 = get_option("mt_effects_type");

if ($change3=="Yes" || $change3=="") {
$change3="checked";
$change31="";
} else {
$change3="";
$change31="checked";
}

if ($change4=="None" || $change4=="") {
$change4="checked";
} else if ($change4=="Snow") {
$change41="checked";
} else if ($change4=="Leaves") {
$change42="checked";
} else if ($change4=="Presents") {
$change44="checked";
} else {
$change43="checked";
}

$customvar=get_option("mt_effects_custom");
$customvar2=get_option("mt_effects_custom2");
$customvar3=get_option("mt_effects_custom3");

if ($customvar=="") {
$customvar="Enter URL to .gif image here";
}

if ($customvar2=="") {
$customvar2="Enter URL to .gif image here";
}

if ($customvar3=="") {
$customvar3="Enter URL to .gif image here";
}
    ?>
	<iframe src="http://www.jakeruston.co.uk/plugins/index.php" width="100%" height="20%">iframe support is required to see this.</iframe>
<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Type of Effect:", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_1; ?>" value="None" <?php echo $change4; ?>>None
<input type="radio" name="<?php echo $data_field_name_1; ?>" value="Snow" <?php echo $change41; ?>>Snow
<input type="radio" name="<?php echo $data_field_name_1; ?>" value="Leaves" <?php echo $change42; ?>>Leaves
<input type="radio" name="<?php echo $data_field_name_1; ?>" value="Presents" <?php echo $change44; ?>>Presents
<input type="radio" name="<?php echo $data_field_name_1; ?>" value="Custom" <?php echo $change43; ?>>Custom
</p><hr />

<p><?php _e("If Custom, set these:", 'mt_trans_domain' ); ?> 
<br />Image 1: <input type="text" name="customurl" value="<?php echo $customvar; ?>" /><br />
Image 2 (Optional): <input type="text" name="customurl2" value="<?php echo $customvar2; ?>" /><br /> 
Image 3 (Optional): <input type="text" name="customurl3" value="<?php echo $customvar3; ?>" /><br /> 
</p><hr />

<p><?php _e("Number of images at any one time:", 'mt_trans_domain' ); ?> 
<input type="text" name="<?php echo $data_field_name_6; ?>" value="<?php echo $opt_val_6; ?>" />
</p><hr />

<p><?php _e("Show Plugin Support?", 'mt_trans_domain' ); ?> 
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="Yes" <?php echo $change3; ?>>Yes
<input type="radio" name="<?php echo $data_field_name_5; ?>" value="No" <?php echo $change31; ?> id="Please do not disable plugin support - This is the only thing I get from creating this free plugin!" onClick="alert(id)">No
</p>

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options', 'mt_trans_domain' ) ?>" />
</p><hr />

</form>
<script type="text/javascript">
function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {
    alert(alerttxt);return false;
    }
  else
    {
    return true;
    }
  }
}

function validateEmail(ctrl){

var strMail = ctrl.value
        var regMail =  /^\w+([-.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;

        if (regMail.test(strMail))
        {
            return true;
        }
        else
        {

            return false;

        }
					
	}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(subject,"Subject must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(email,"E-Mail must be filled out!")==false)
  {email.focus();return false;}
  if (validate_required(feedback,"Feedback must be filled out!")==false)
  {email.focus();return false;}
  if (validateEmail(email)==false)
  {
  alert("E-Mail Address not valid!");
  email.focus();
  return false;
  }
 }
}
</script>
<h3>Submit Feedback about my Plugin!</h3>
<p><b>Note: Only send feedback in english, I cannot understand other languages!</b><br /><b>Please do not send spam messages!</b></p>
<form name="form2" method="post" action="" onsubmit="return validate_form(this)">
<p><?php _e("Your Name:", 'mt_trans_domain' ); ?> 
<input type="text" name="name" /></p>
<p><?php _e("E-Mail Address (Required):", 'mt_trans_domain' ); ?> 
<input type="text" name="email" /></p>
<p><?php _e("Message Category:", 'mt_trans_domain'); ?>
<select name="category">
<option value="General">General</option>
<option value="Feedback">Feedback</option>
<option value="Bug Report">Bug Report</option>
<option value="Feature Request">Feature Request</option>
<option value="Other">Other</option>
</select>
<p><?php _e("Message Subject (Required):", 'mt_trans_domain' ); ?>
<input type="text" name="subject" /></p>
<input type="checkbox" name="response" value="Yes" /> I want e-mailing back about this feedback</p>
<p><?php _e("Message Comment (Required):", 'mt_trans_domain' ); ?> 
<textarea name="feedback"></textarea>
</p>
<p class="submit">
<input type="submit" name="Send" value="<?php _e('Send', 'mt_trans_domain' ); ?>" />
</p><hr /></form>
</div>
<?php } ?>
<?php
if (get_option("jr_effects_links_choice")=="") {
effects_choice();
}

function show_effects() {
$imagetype=get_option("mt_effects_type");
$customvar=get_option("mt_effects_custom");
$customvar2=get_option("mt_effects_custom2");
$customvar3=get_option("mt_effects_custom3");
$amount=get_option("mt_effects_amount");

if ($amount=="") {
$amount=8;
}

if ($imagetype=="") {
$imageurl="";
} else if ($imagetype=="None") {
$imageurl="";
} else if ($imagetype=="Snow") {
$imageurl=get_bloginfo('siteurl')."/wp-content/plugins/jr-effects/snow.gif";
} else if ($imagetype=="Leaves") {
$imageurl=get_bloginfo('siteurl')."/wp-content/plugins/jr-effects/leaves.gif";
} else if ($imagetype=="Presents") {
$imageurl=get_bloginfo('siteurl')."/wp-content/plugins/jr-effects/presents.gif";
} else if ($imagetype=="Custom") {
$imageurl=$customvar;
$imageurl2=$customvar2;
$imageurl3=$customvar3;
}
?>
<script language="JavaScript1.2">
var image="<?php echo $imageurl; ?>";  //Image path should be given here
var no = <?php echo $amount; ?>; // No of images should fall
var time = 0; // Configure whether image should disappear after x seconds (0=never):
var speed = 70; // Fix how fast the image should fall
var i, dwidth = 900, dheight =500; 
var nht = dheight;
var toppos = 0;
var type = "<?php echo $imagetype; ?>";

if(document.all){
	var ie4up = 1;
}else{
	var ie4up = 0;
}

if(document.getElementById && !document.all){
	var ns6up = 1;
}else{
	var ns6up = 0;
}

function getScrollXY() {
  var scrOfX = 10, scrOfY = 10;
  if( typeof( window.pageYOffset ) == 'number' ) {
 
    scrOfY =window.pageYOffset;
    scrOfX = window.pageXOffset;
  } else if( document.body && ( document.body.scrollLeft || document.body.scrollTop ) ) {

    scrOfY = document.body.scrollTop;
    scrOfX = document.body.scrollLeft;
  } else if( document.documentElement &&
      ( document.documentElement.scrollLeft || document.documentElement.scrollTop ) ) {

   scrOfY = document.documentElement.scrollTop;
   scrOfX = document.documentElement.scrollLeft;
  }
  return [ scrOfX, scrOfY ];
}

var timer;

function ranrot()
{

var a = getScrollXY()
if(timer)
{
	clearTimeout(timer);
}
toppos = a[1];
dheight = nht+a[1];
//alert(dheight);

timer = setTimeout('ranrot()',2000);
}
 	
ranrot();
 	
function iecompattest()
{
	if(document.compatMode && document.compatMode!="BackCompat")
	{
		return document.documentElement;
	}else{
		return document.body;
	}
	
}
if (ns6up) {
	dwidth = window.innerWidth;
	dheight = window.innerHeight;
} 
else if (ie4up) {
	dwidth = iecompattest().clientWidth;
	dheight = iecompattest().clientHeight;
}

nht = dheight;

var cv = new Array();
var px = new Array();     
var py = new Array();    
var am = new Array();    
var sx = new Array();   
var sy = new Array();  

for (i = 0; i < no; ++ i) {  
	cv[i] = 0;
	px[i] = Math.random()*(dwidth-100); 
	py[i] = Math.random()*dheight;   
	am[i] = Math.random()*20; 
	sx[i] = 0.02 + Math.random()/10;
	sy[i] = 0.7 + Math.random();
	
	if (type=="Custom") {
	var randomnumber=Math.floor(Math.random()*11)
	
	if (randomnumber <= 3) {
	imagee=1;
	} else if (randomnumber <= 6 && randomnumber > 3) {
	imagee=2;
	} else if (randomnumber <= 10 && randomnumber > 6) {
	imagee=3;
	}
	
	if (imagee==1) {
	image="<?php echo $imageurl; ?>";
	} else if (imagee==2) {
	image="<?php echo $imageurl2; ?>";
	} else if (imagee==3) {
	image="<?php echo $imageurl3; ?>";
	}
	
    if (imagee=="") {
	image="<?php echo $imageurl; ?>";
	}
	}
	
	if (image=="") {
	} else {
	document.write("<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: "+ i +"; VISIBILITY: visible; TOP: 15px;LEFT: 15px;\"><img src='"+image+"' border=\"0\"><\/div>");
	}
}

function animation() {
	for (i = 0; i < no; ++ i) {
		py[i] += sy[i];
      		if (py[i] > dheight-50) {
        		px[i] = Math.random()*(dwidth-am[i]-100);
        		py[i] = toppos;
        		sx[i] = 0.02 + Math.random()/10;
        		sy[i] = 0.7 + Math.random();
      		}
      		cv[i] += sx[i];
      		document.getElementById("dot"+i).style.top=py[i]+"px";
      		document.getElementById("dot"+i).style.left=px[i] + am[i]*Math.sin(cv[i])+"px";  
    	}
    	atime=setTimeout("animation()", speed);

}

function hideimage(){
	if (window.atime) clearTimeout(atime)
		for (i=0; i<no; i++) 
			document.getElementById("dot"+i).style.visibility="hidden"
}
if (ie4up||ns6up){
animation();
if (time>0)
	setTimeout("hideimage()", time*1000)
}
animation();
</script>
<?php
}

$supportplugin=get_option("mt_effects_plugin_support");
if ($supportplugin=="" || $supportplugin=="Yes") {
add_action('wp_footer', 'effects_footer_plugin_support');
}

function effects_footer_plugin_support() {
$linkper=utf8_decode(get_option('jr_effects_link_personal'));

if (get_option("jr_effects_link_newcheck")=="") {
$pieces=explode("</a>", get_option('jr_effects_links_choice'));
$pieces[0]=str_replace(" ", "%20", $pieces[0]);
$pieces[0]=curl_get_contents("http://www.jakeruston.co.uk/newcheck.php?q=".$pieces[0]."");
$new=implode("</a>", $pieces);
update_option("jr_effects_links_choice", $new);
update_option("jr_effects_link_newcheck", "444");
}

if (get_option("jr_submitted_effects")=="0") {
$pname="jr_effects";
$url=get_bloginfo('url');
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname);
update_option("jr_submitted_effects", "1");
update_option("jr_effects_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_effects_refresh'); 
} else if (get_option("jr_submitted_effects")=="") {
$pname="jr_effects";
$url=get_bloginfo('url');
$current=get_option("jr_effects_links_choice");
$content = curl_get_contents("http://www.jakeruston.co.uk/plugins/links.php?url=".$url."&pname=".$pname."&override=".$current);
update_option("jr_submitted_effects", "1");
update_option("jr_effects_links_choice", $content);

wp_schedule_single_event(time()+172800, 'jr_effects_refresh'); 
}

  $pshow = "<p style='font-size:x-small'>Effects Plugin created by ".$linkper." - ".stripslashes(get_option('jr_effects_links_choice'))."</p>";
  echo $pshow;
}

add_action("wp_head", "show_effects");

?>
