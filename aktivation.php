<?php
session_start();
mysql_connect("hostiteľ", "meno", "heslo");
mysql_select_db("databáza");
$access = "bit";
$flags = "ce";
$days = 9999;
?>
<!DOCTYPE HTML>
<html lang='cs'>
    <head>
        <title>VIP Aktivácia</title>
        <meta charset='UTF-8'>
        <meta name='keywords' content=''>
        <meta name='description' content=''>
        <meta name='author' content='Lukáš "iCrow" Jurygáček - iCrow.cz'>
        <style>
            .text{color:#FFFFFF;font-family:Verdana;font-size: 10px}
        </style>
	</head>
	<body bgcolor="#000000">
        <?php
        if (isset($_GET['step']) && $_GET['step'] == 2) {
            if (!isset($_SESSION['code']) || strlen($_SESSION['code']) != 10) {
                header("Location: vip_activation.php");
            }
            $access_step = false;
            $code = mysql_real_escape_string($_SESSION['code']);
            $select = mysql_query("SELECT * FROM sms WHERE code='".$code."' OR code2='".$code."' LIMIT 1");
            if (mysql_num_rows($select) == 1) {
                $data = mysql_fetch_assoc($select);
                if (!($data['country'] == "SK" && empty($data['code2']))) {
                    $access_step = true;
                }
            }
            if ($access_step == false) {
                header("Location: vip_activation.php");
            }
            if (isset($_POST['ok'])) {
                if (empty($_POST['id'])) {
                    $error = "<div class='text'>Nevyplnili ste ID.</div>";
                } elseif (empty($_POST['server'])) {
                    $error = "<div class='text'>Nevybrali ste server.</div>";
                } else {
                    if (preg_match('/\bSTEAM_([0-9]{1}):([0-9]{1}):([0-9]{1,10})$/', $_POST['id']) || preg_match('/\bVALVE_([0-9]{1}):([0-9]{1}):([0-9]+)$/', $_POST['id'])) {
                        $select = mysql_query("SELECT id FROM amx_amxadmins WHERE steamid='".mysql_real_escape_string($_POST['id'])."' LIMIT 1");
                        if (mysql_num_rows($select) == 1) {
                            $data = mysql_fetch_assoc($select);
                            $id = $data['id'];
                        } else {
                            $expire = time() + (60 * 60 * 24 * $days);
                            mysql_query("INSERT INTO amx_amxadmins (steamid, nickname, username, access, flags, created, expired, vip) VALUES ('".$_POST['id']."', '".$_POST['id']."', '".$_POST['id']."', '".$access."', '".$flags."', '".time()."', '".$expire."', '1')");
                            $id = mysql_insert_id();
                        }
                        mysql_query("INSERT INTO amx_admins_servers (admin_id, server_id) VALUES ('".$id."', '".intval($_POST['server'])."')");
                        mysql_query("DELETE FROM sms WHERE code='".$code."' OR code2='".$code."'");
                        unset($_SESSION['code']);
                        $error = "<div class='text'>VIP bolo aktivované, kód pre aktiváciu nie je už platný. VIP Vám pôjde po zmene mapy.</div>";
                    } else {
                        $error = "<div class='text'>Nepovolený formát ID.</div>";
                    }
                }
                echo "<div style='padding:10px;margin:6px;border:2px solid #f00;color:#000;'>".$error."</div>";
            }
            ?>
            <form action='<?php $_SERVER['PHP_SELF']; ?>' method='post'>
                <a class="text">Steam ID alebo Valve ID:</a>&nbsp;<input type='text' name='id'>
                <select name='server'>
                    <?php
                    $servers = mysql_query("SELECT id, hostname FROM amx_serverinfo ORDER BY id ASC");
                    while ($server = mysql_fetch_assoc($servers)) {
                        echo "<option value='".$server['id']."'>".$server['hostname']."</option>";
                    }
                    ?>
                </select>
                <input type='submit' name='ok' value='Aktivovať'>
                <br>
                <small>
                    <a class="text">Príklady:</a><br>
                    <a class="text">STEAM_0:0:123456789</a><br>
                    <a class="text">VALVE_0:4:1234567890123</a>
                </small>
            </form>
            <?php
        } else {
            if (isset($_POST['ok'])) {
                if (empty($_POST['code'])) {
                    $error = "<div class='text'>Nevyplnili ste kód.</div>";
                } else {
                    $code = mysql_real_escape_string($_POST['code']);
                    $select = mysql_query("SELECT * FROM sms WHERE code='".$code."' OR code2='".$code."' LIMIT 1");
                    if (mysql_num_rows($select) == 1) {
                        $data = mysql_fetch_assoc($select);
                        if ($data['country'] == "SK" && empty($data['code2'])) {
                            $error = "<div class=text'>Pre dokončenie platby musíte odoslať ešte 1 SMS z rovnakého telefónneho čísla.</div>";
                        } else {
                            $_SESSION['code'] = $_POST['code'];
                            header("Location: vip_activation.php?step=2");
                        }
                    } else {
                        $error = "<div class='text'>Neplatný kód!</div>";
                    }
                }
                echo "<div style='padding:10px;margin:6px;border:2px solid #f00;color:#000;'>".$error."</div>";
            }
            ?>
            <form action='<?php $_SERVER['PHP_SELF']; ?>' method='post'>
                <a class="text">Kód z SMS:</a>&nbsp;<input type='text' name='code'>
                <input type='submit' name='ok' value='Odoslať'>
            </form>
            <br>
            <a class="text">- platby zo Slovenska robte z rovnakého telefónneho čísla!</a>
        <?php
        }
        ?>
        <br>
        <br>
        <!-- VIP skript vytvořil iCrow (www.iCrow.cz) &copy; 2012 -->
    </body>
</html>