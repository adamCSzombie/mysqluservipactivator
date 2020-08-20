<?php
function PostMail($to, $subject = '(No subject)', $message = '', $from) 
 { 
  $header = 'MIME-Version: 1.0' . "\n" . 'Content-type: text/plain; charset=UTF-8' 
  . "\n" . 'From: <' . $from . ">\n"; 
  mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $message, $header); 
 }
 
function STEAM_($steamid)
 {
  return ereg("^STEAM_",$steamid);
 }
?>