<?php
define('INCLUDE_CHECK',true);

$PRE_URL = "../";

// These files can be included only if INCLUDE_CHECK is defined
require_once("$PRE_URL"."includes/config_twilio.php");
require_once("$PRE_URL"."includes/config_login.php");
require_once("$PRE_URL"."includes/config_mongo.php"); // connect to database or set $error
require_once("$PRE_URL"."includes/functions_validateforms.php");
require_once("$PRE_URL"."includes/functions_login.php");

// Include files 
require_once('../twilio-php/Services/Twilio.php'); // Load the PHP helper library 

// Copy database issues onto $db_error. $error will be cleared soon after
if(isset($error)) 
	$db_error = $error;

unset($error, $output, $num_found, $have_updates );


// Check if user is logged in.
sec_session_start();
if (login_check($collection_user) != true) {
	header("Location: ".$PRE_URL."login.php");
	exit;
} 

// Check if user is admin; Only an admin has access to this page
if ($_SESSION['user_type'] != "admin" ) {
	$_SESSION['message'] = "ERROR: You are not authorized to perform this action!";
	header("Location: ".$PRE_URL."login.php");
	exit;
}

// Get the company sid and token from the SESSION parameters. 
if (isset($_SESSION['sid']) && isset($_SESSION['token']) ) {
	$company_sid = $_SESSION['sid'];
	$company_token = $_SESSION['token'];
} else {
	$error = "Session Error. Please log in again!";
}

// Get company_id from the SESSION parameters. Without which, several modules will not work. 
if (isset($_SESSION['company'])) {
	// get the company ID (MongoID) 
	$company_id = new MongoId($_SESSION['company']);
} else {
	$error = "Session Error. Please log in again!";
}


if($_POST['return']){
	unset( $_SESSION['edit_greeting'] );
}
elseif(isset($_SESSION['edit_greeting'])&&( $_POST['save1']||$_POST['save2']|| $_POST['save3']|| $_POST['save4']|| $_POST['save5']|| $_POST['save6']|| $_POST['save7'])){
	if($_POST['save1']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('welcome_greeting'=>array('name'=>$_POST['welcome_name'],'content'=>$_POST['welcome_content'])))),array('upsert'=>false));
	}elseif($_POST['save2']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('available_agents_greeting'=>array('name'=>$_POST['available_agents_name'],'content'=>$_POST['available_agents_content'])))),array('upsert'=>false));
	}elseif($_POST['save3']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('waiting_message'=>array('name'=>$_POST['waiting_msg_name'],'content'=>$_POST['waiting_msg_content'])))),array('upsert'=>false));
	}elseif($_POST['save4']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('voice_message'=>array('name'=>$_POST['voicemail_msg_name'],'content'=>$_POST['voicemail_msg_content'])))),array('upsert'=>false));
	}elseif($_POST['save5']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('outside_business_hours_message'=>array('name'=>$_POST['outside_business_name'],'content'=>$_POST['outside_business_content'])))),array('upsert'=>false));
	}elseif($_POST['save6']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('delay_announcement_message'=>array('name'=>$_POST['delay_announce_name'],'content'=>$_POST['delay_announce_content'])))),array('upsert'=>false));
	}elseif($_POST['save7']){
		$collection_phonenum->update(array('phone_number'=>$_SESSION['phone_selected']),array('$set'=>(array('full_waiting_queue_message'=>array('name'=>$_POST['full_waiting_queue_name'],'content'=>$_POST['full_waiting_queue_content'])))),array('upsert'=>false));
	}
}
elseif(!isset($_SESSION['edit_greeting'])&&$_POST['phone']){
	$_SESSION['phone_selected'] = $_POST['submit'];
	$cursor1 = $collection_phonenum->findOne(array('company'=>$company_id));
	$_SESSION['edit_greeting']=1;
	unset($cursor,$cursor2);
}





?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="Voice BroadCast service" />
<meta name="keywords" content="Voice, Notifications, broadcast, callbroadcast, group" />
<meta name="author" content="SolidBase Consulting LLC" />
<title>CallBroadCast - CallCenter Software</title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/stylesheet1.css" rel="stylesheet" type="text/css" media="all" />
<script src="js/button.js" type="text/javascript"></script>

<style type="text/css">
body {
	font-family:"Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
	font-size:12px;
}
.rb {padding: 0;
border: none;
background: none; color:red;}
input {padding: 0;border: none;background: #D8D8D8;} 
div1 {
    display: inline-block;
    background-color:yellow;
    background-size: 100% 100%;
    background-repeat:no-repeat;
    min-height: 75px;
    min-width: 350px;
    outline: 0;
    padding: 30px 30px 40px 20px;
}textarea {
   resize: none;
}
.bigbutton {width: 6em;  height: 2em;}.bigbutton2 {height: 2em;}
 .success, .warning, .errormsgbox, .validation {
	border: 1px solid;
	margin: 0 auto;
	padding:10px 5px 10px 50px;
	background-repeat: no-repeat;
	background-position: 10px center;
     font-weight:bold;
     width:450px;  
}
.errormsgbox {
	color: #D8000C;
	background-color: #FFBABA;
	background-image: url('images/error.png');
}
.success {
	color: #4F8A10;
	background-color: #DFF2BF;
	background-image:url('images/success.png');
}.label-style{
background-color:#66CCFF;
width:auto;
}
</style>


</head>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script type="text/javascript" src="animatedcollapse.js">
</script>
<script type="text/javascript">
animatedcollapse.addDiv('alan1', 'fade=1,height=160px')
animatedcollapse.addDiv('alan2', 'fade=1,height=160px')
animatedcollapse.addDiv('alan3', 'fade=1,height=160px')
animatedcollapse.addDiv('alan4', 'fade=1,height=160px')
animatedcollapse.addDiv('alan5', 'fade=1,height=160px')
animatedcollapse.addDiv('alan6', 'fade=1,height=160px')
animatedcollapse.addDiv('alan7', 'fade=1,height=160px')
animatedcollapse.ontoggle=function($, divobj, state){ //fires each time a DIV is expanded/contracted
	//$: Access to jQuery
	//divobj: DOM reference to DIV being expanded/ collapsed. Use "divobj.id" to get its ID
	//state: "block" or "none", depending on state
}
animatedcollapse.init()
</script>
<body>
<div id="container">
  <div id="header">

<?php include ("includes/header.html"); ?>

  </div>
  <div id="inner-body">
    <div class="body-container">
      <div class="inner-welcome-box">
       <h1>View/Update Number</h1>
      </div>
      <div class="about-box">

<!--==============Show list of phone numbers=================-->

<?php if(!isset($error)&&isset($company_id)&&!isset($_SESSION['edit_greeting'])){ ?>
<h3>Greeting Setting </h3>
<form  action="<?php echo basename(__FILE__);?>" method="post">
<?php $cursor = $collection_phonenum->find(array('company'=>$company_id));
	if (!isset($cursor)){
		$error = "Database Error (14368). Please try again!";
	}else{ ?> 
<input type="hidden" name="phone" value="add">
		<?php while($cursor->hasNext()){
			$result = $cursor->getNext();?>	
<br/><input class="bigbutton2" type=submit value= 
<?php echo $result['phone_number'];  ?> 
name="submit">  
<br/>
<?php	}}} ?>
</form>
<!--==================show error==================-->

<?php

if(isset($error)) 
	echo '<div class="errormsgbox"> '.$error.'</div>';

if (isset($_SESSION['message'])) {
	echo '<div class="success">'. $_SESSION['message'].'</div>';
	unset($_SESSION['message']);
}

?>

<!--==============Greeting Settings===============-->

<?php if(isset($_SESSION['edit_greeting'])): ?>
<h2><?php echo $_SESSION['phone_selected'] ; ?></h2>
<h3>Greetings </h3><br/>

<?php 
$result = $collection_phonenum->findOne(array('phone_number'=>$_SESSION['phone_selected']));
?>

<label>Welcome Greeting </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="javascript:animatedcollapse.toggle('alan1')"> <label>Expand/Collapse</label> </a>
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $welcome_array = $result['welcome_greeting']; ?>
<label class="label-style"><?php echo $welcome_array['name']; ?></label> 
<div id="alan1" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save1" value="add">
<p></p>
<label>Name*</label> <input type="text" name="welcome_name" value="<?php echo $welcome_array['name']; ?>" > <p></p>
<label>TTS Message*</label> <textarea style="width: 200px; height: 90px;" name="welcome_content"><?php echo $welcome_array['content']; ?></textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>


<label>Available Agents Greeting </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animatedcollapse.toggle('alan2')"><label>Expand/Collapse</label></a> 
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $available_agents_array = $result['available_agents_greeting']; ?>
<label class="label-style"><?php echo $available_agents_array['name']; ?></label> 
<div id="alan2" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save2" value="add">
<p></p>
<label>Name*</label> <input type="text" name="available_agents_name" value="<?php echo $available_agents_array['name'];  ?>" > <p></p>
<label>TTS Message*</label> <textarea style="width: 200px; height: 90px;"  name="available_agents_content"><?php echo $available_agents_array['content'];  ?></textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>


<label>Waiting Message </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animatedcollapse.toggle('alan3')"><label>Expand/Collapse</label></a> 
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $waiting_message_array = $result['waiting_message']; ?>
<label class="label-style"><?php echo $waiting_message_array['name']; ?></label> 
<div id="alan3" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save3" value="add">
<p></p>
<label>Name*</label> <input type="text" name="waiting_msg_name" value="<?php echo $waiting_message_array['name'];  ?>" > <p></p>
<label>TTS Message*</label> <textarea style="width: 200px; height: 90px;"  name="waiting_msg_content"><?php echo $waiting_message_array['content']; ?> </textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>


<label>Voicemail Message </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animatedcollapse.toggle('alan4')"><label>Expand/Collapse</label></a> 
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $voice_message_array = $result['voice_message']; ?>
<label class="label-style"><?php echo $voice_message_array['name']; ?></label> 
<div id="alan4" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save4" value="add">
<p></p>
<label>Name*</label> <input type="text" name="voicemail_msg_name" value="<?php echo $voice_message_array['name'];  ?>" > <p></p>
<label>TTS Message*</label> <textarea style="width: 200px; height: 90px;"  name="voicemail_msg_content"><?php echo $voice_message_array['content'];   ?></textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>


<label>Outside Business Hours Message </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animatedcollapse.toggle('alan5')"><label>Expand/Collapse</label></a> 
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $outside_business_hours_message_array = $result['outside_business_hours_message']; ?>
<label class="label-style"><?php echo $outside_business_hours_message_array['name']; ?></label> 
<div id="alan5" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save5" value="add">
<p></p>
<label>Name*</label> <input type="text" name="outside_business_name" value= "<?php echo $outside_business_hours_message_array['name'];  ?>" > <p></p>
<label>Message*</label> <textarea style="width: 200px; height: 90px;"  name="outside_business_content"><?php echo $outside_business_hours_message_array['content'];  ?> </textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>


<label>Delay Announcement Message </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animatedcollapse.toggle('alan6')"><label>Expand/Collapse</label></a> 
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $delay_announcement_message_array = $result['delay_announcement_message']; ?>
<label class="label-style"><?php echo $delay_announcement_message_array['name']; ?></label> 
<div id="alan6" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save6" value="add">
<p></p>
<label>Name*</label> <input type="text" name="delay_announce_name" value= "<?php echo $delay_announcement_message_array['name']; ?>" > <p></p>
<label>TTS Message*</label> <textarea style="width: 200px; height: 90px;"  name="delay_announce_content"><?php echo $delay_announcement_message_array['content']; ?> </textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>


<label>Full Waiting Queue Message </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:animatedcollapse.toggle('alan7')"><label>Expand/Collapse</label></a> 
<br/><br/>&nbsp;&nbsp;&nbsp; 
<?php $full_waiting_queue_message_array = $result['full_waiting_queue_message']; ?>
<label class="label-style"><?php echo $full_waiting_queue_message_array['name']; ?></label> 
<div id="alan7" style="width: 500px; display:none">
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="save7" value="add">
<p></p>
<label>Name*</label> <input type="text" name="full_waiting_queue_name" value= "<?php echo $full_waiting_queue_message_array['name']; ?>" > <p></p>
<label>TTS Message*</label> <textarea style="width: 200px; height: 90px;"  name="full_waiting_queue_content"><?php echo $full_waiting_queue_message_array['content']; ?> </textarea><br/><br/>
<input class="bigbutton2" type=submit value= 
"Save"
name="submit">  
</form>
</div><br/><br/>

<?php unset($result);endif; ?>


<!--=============Return==================-->
<?php if(isset( $_SESSION['edit_greeting'] )): ?>
<div><br/>
<form  action="<?php echo basename(__FILE__);?>" method="post">
<input type="hidden" name="return" value="add">
<input class="bigbutton2" type=submit value="Return" >
</form>
</div>
<?php endif; ?>
<!--========================================-->
        </div>
      <div class="body-bottom-box">
	<h2>Voice BroadCast - <span style="color: #009dea;">Click.Set.Go</span></h2>
      </div>
    </div>
  </div>
  <div id="footer">
<?php include ("includes/footer.html"); ?>
  </div>
</div>
</body>
</html>

