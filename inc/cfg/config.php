<?php

$ip = "db.mysql-01.gsp-europe.net"; // MySQL IP

$user = "sql_2599"; // MySQL User

$pass = "5lMsI3bmf7Gf9nA57BSva9V5CietIoF"; // MySQL Password

$db = "sql_2599"; // MySQL Database



$default_password = ""; //Musí ostať prázdne

$default_activation = ""; //Musí ostať prázdne

$access = "ts"; //Práva VIP

$flags = "ce"; //Flags - Nastavene na STEAM_ID



$cislo = "6674";



$pripojenie = mysql_connect("$ip", "$user", "$pass") or die ("Nepodarilo sa pripojiť na MySQL");

$vyber = mysql_select_db($db, $pripojenie) or die ("Nepodarilo sa pripojiť do databázy");



?>