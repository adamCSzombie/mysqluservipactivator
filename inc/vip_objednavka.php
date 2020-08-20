<?php

include("cfg/config.php");

include("cfg/functions.php");



$steamid = mysql_real_escape_string($_POST["steamid"]);

$email = mysql_real_escape_string($_POST["email"]);

$From = "podpora@bluezone.sk";

$Subject = "Send Email using SMTP authentication";

$Message = "This example demonstrates how you can send email with PHP using SMTP authentication"; 

$Host = "mail.gsp-europe.net";

$Username = "podpora@bluezone.sk";

$Password = "panenkomario9";

$Headers = array ('From' => $From, 'To' => $To, 'Subject' => $Subject);
$SMTP = Mail::factory('smtp', array ('host' => $Host, 'auth' => true, 
'username' => $Username, 'password' => $Password)); 


$reg_time = time();

$exp_time = time() + (30 * 24 * 60 * 60);

$steamid_used = mysql_result(mysql_query("SELECT count(*) FROM sql_2599 WHERE steam_id='".mysql_real_escape_string($steamid)."'"),0);

					 

if($steamid == "" || $email == "")

{

echo "Vyplnte STEAM_ID a E-Mail, prosím.";

}

	 else {

	 	if (!STEAM_($steamid)) {

		echo "Nezadali ste predložku \"STEAM_\".";

	 	} 

	 	else {

	 		if (!$steamid_used) 

	 		{ 

	 		mysql_query("INSERT INTO `sql_2599` (`steam_id` ,`email` ,`exp_time` ,`reg_time`) VALUES ('".$steamid."', '".$email."', '".$exp_time."', ' ".$reg_time."')") or die (mysql_error());

	 		$id = mysql_query("SELECT * FROM sql_2599 WHERE steam_id='".mysql_real_escape_string($steamid)."'");

	 		$vypis_id = mysql_fetch_array($id);

	 		$tvar = $vypis_id["id"];

 	 		$mail = $SMTP->send($email, $Headers, $Message);

			if (PEAR::isError($mail)){ 
			echo($mail->getMessage()); 
			} else { 
			echo("Email Message sent!"); 
			}

	 		} 

	 		else {

	 		echo "Vyplnené STEAM_ID už je zaregistrované.";

	 	    	}

	      	}

	 }

?>