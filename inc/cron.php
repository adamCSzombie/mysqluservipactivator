<?php

include ("cfg/config.php");



// Zkontroluj všechny VIP PA

         $result_vip = mysql_query("SELECT * FROM `sql_2599` ORDER BY `sql_2599`.`steam_id` DESC ");



         while ($row_vip = mysql_fetch_array($result_vip))

                {

                $time_now = time();

                $time_back = strtotime('-1 month');

                $time_show = date('d.m.y - H:i:s', $time_now);

				

                     if ($row_vip[exp_time] <= $time_now)

                         {

                          // vypni PA + přenastav gmlevel

                          mysql_query("DELETE FROM `admins` WHERE auth = '".$row_vip[steam_id]."' LIMIT 1 ;");

						  mysql_query("DELETE FROM `sql_2599` WHERE steam_id = '".$row_vip[steam_id]."' LIMIT 1 ;");

                    } else {

                            if ($time_back >= $row_vip[reg_time])

                                {

                                 // pokud je žádost o PA starší 1mesíc tak ji smázni

						  		 mysql_query("DELETE FROM `sql_2599` WHERE steam_id = '".$row_vip[steam_id]."' LIMIT 1 ;");

                                }

                           }

				}

?>