<?php
mysql_connect("hostite¾", "meno", "heslo");
mysql_select_db("databáza");
function randomCode($long) {
    $chars = "abcdefghijklmnopqrstuvwxyz123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $out = "";
    $num = strlen($chars);
    for ($i = 0; $i < $long; $i++) {
        $out .= $chars[mt_rand(0,$num - 1)];
    }
    return $out;
}
$pattern = "/^195\.47\.87\$/i";
$ip = $_SERVER['REMOTE_ADDR'];
$part = explode(".", $ip);
$subject = $part[0].".".$part[1].".".$part[2];
if (empty($_GET['hash']) || empty($_GET['price']) || !preg_match($pattern, $subject) || !($part[3] >= 160 && $part[3] <= 191) || $_GET['hash'] == "emulator") {
    die();
}
$prices = array("99.00", "1.6");
if (in_array($_GET['price'], $prices)) {
    $insert = true;
    $code = randomCode(10);
    if ($_GET['price'] == "99.00") {
        $country = "CZ";
        $msg = "Platba bola uspesne prijata. Kod: ".$code."";
    } elseif ($_GET['price'] == "1.6") {
        $country = "SK";
        $msg = "Pre dokoncenie platby musite odoslat este 1 SMS z rovnakeho telefonneho cisla.";
        $select = mysql_query("SELECT code FROM sms WHERE hash='".mysql_real_escape_string($_GET['hash'])."' AND country='SK' AND code2 IS NULL LIMIT 1");
        if (mysql_num_rows($select) == 1) {
            $result = mysql_fetch_assoc($select);
            $insert = false;
            $code = randomCode(10);
            mysql_query("UPDATE sms SET code2='".$code."' WHERE hash='".mysql_real_escape_string($_GET['hash'])."' AND code='".$result['code']."'");
            $msg = "Platba bola uspesne dokoncena. Kod: ".$code."";
        }
    }
    if ($insert == true) {
        mysql_query("INSERT INTO sms (code, hash, country) VALUES ('".$code."', '".mysql_real_escape_string($_GET['hash'])."', '".$country."')");
    }
    echo $msg;
}
?>