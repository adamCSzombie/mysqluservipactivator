<?php



$log_file = FOpen('log/pladby.txt', 'a');



FPutS($log_file, Date("Y-m-d H:i:s") . " " . $_GET['text'] . " " . $_GET['price'] . " " . $_GET['debug'] . "\n");



FClose($log_file);



include("cfg/config.php");



function Key_Generator($delka_hesla) {

  $mozne_znaky = 'abcdefghijkmnpqrstuvwx23456789ABCDEFGHIJKMNPQRSTUVWX';

  $gen = '';

  $pocet_moznych_znaku = strlen($mozne_znaky);

  for ($i=0;$i<$delka_hesla;$i++) {

    $gen .= $mozne_znaky[mt_rand(0,$pocet_moznych_znaku - 1)];

  }

  return $gen;

 }



	$identifikacia = $_GET["text"];

	$activation = Key_Generator(10);

	$id_used = mysql_result(mysql_query("SELECT count(*) FROM sql_2599 WHERE id='".mysql_real_escape_string($identifikacia)."'"),0);

	

	if(!$id_used || $_GET["price"]<1.607){

	echo "@ERROR Vaša SMS nebola odoslaná správne. Kontaktujte nás na GetroXer@gmail.com";	

	} else {

	mysql_query("UPDATE `sql_2599` SET `activation` = '$activation' WHERE `id` =".$identifikacia." LIMIT 1") or die (mysql_error());

	echo "Dakujeme Vám za vašu SMS, váš aktivačný kód je \"".$activation."\".";

	}

?>