<?php
if (isset($_GET['text']) && !empty($_GET['text']) && isset($_GET['price']) && ($_GET['price'] == "1.607" || $_GET['price'] == "10.00")) {
   
  function random_code() { 
    $moznosti= array("1","2","3","4","5","6","7","8","9","0"); 
    $pocet = "5"; 
    $heslo = ""; 
    for ($i=0 ;$i<=$pocet-1 ;$i++) { 
    $nahoda = rand(0, count($moznosti)-1); 
    $heslo .= $moznosti[$nahoda];
    }
    return $heslo; 
  } 

  $code = random_code();

$hostitel="94.23198";
$nazev="ban";
$jmeno="banl8";
$heslo_db="Hcvjf";

mysql_connect("$hostitel", "$jmeno", "$heslo_db") or die("Nepodarilo se pripojit k MySQL serveru.");
mysql_select_db("$nazev") or die("Nepodarilo se pripojit k MySQL databazi.");

mysql_query("INSERT INTO amx_amxadmins (username, password, access, flags, steamid, nickname, ashow, expired, days) VALUES ( '" . $_GET['text'] . "', '".$code."', 'bhit', 'b', '" . $_GET['text'] . "', 'VIP (Aktivovane ".date("j.n.Y").")', '0', '0', '0')");

$server_id = "7";
$newadmin = mysql_fetch_array(mysql_query("SELECT id FROM amx_amxadmins ORDER BY id DESC LIMIT 1"));

mysql_query("INSERT INTO amx_admins_servers (admin_id, server_id) VALUES ('".$newadmin['id']."', '".$server_id."')");

echo "VIP ti bylo aktivovano! Napis do konzole: setinfo _pw".$code." vypni a zapni hru a VIP bude aktivni do 30 minut.";

mysql_close();
}

?>