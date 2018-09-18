#!/usr/bin/php -c /etc/php.ini

/*
###
### Version  Date      Author    Description
###----------------------------------------------
### 1.0      26/07/15  Shini31   1.0 stable release
###
#####
*/

<?php

    array_shift($argv);
    $f_notify_type =array_shift($argv);
    $f_host_name =array_shift($argv);
    $f_host_alias =array_shift($argv);
    $f_host_state =array_shift($argv);
    $f_host_address =array_shift($argv);
    $f_host_output =array_shift($argv);
    $f_long_date =array_shift($argv);
    $f_serv_desc  =array_shift($argv);
    $f_serv_state  =array_shift($argv);
    $f_to  =array_shift($argv);
    $f_totalup  =array_shift($argv);
    $f_totaldown=array_shift($argv);
    $f_ackauthor= array_shift($argv);
    $f_ackcomment= array_shift($argv);

    if($f_host_state=="RECOVERY") {$f_color="#f48400";}
    if($f_host_state=="DOWN") {$f_color="#f40000";}
    if($f_host_state=="UP") {$f_color="#00b71a";}

    $subject = "[CENTREON] $f_notify_type Host:$f_host_name";
    $url = "https://centreon.yourdomain.com:8081";

    $from  ="centreon@yourdomain.com";
    $body = "<html><body><table border=0 width='98%' cellpadding=0 cellspacing=0><tr><td valign='top'>\r\n";
    $body .= "<table border=0 cellpadding=0 cellspacing=0 width='98%'>";
    $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>Host: </font></b></td><td><font color=#ffffff><b> $f_notify_type [$f_host_state]</b></font></td></tr> \r\n";
    if($f_ackauthor!="" && $f_ackcomment!=""){
        $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>$f_ackauthor:</font></b></td><td><font color=#ffffff><b>$f_ackcomment</b></font></td></tr>\r\n";
    }
    $body .= "<tr bgcolor=#eeeeee><td><b>Hostname: </b></td><td><b><a href='$url/centreon/main.php?p=20202&o=hd&host_name=$f_host_name'>$f_host_alias</a></b></td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Address: </b></td><td><b>$f_host_address</b></td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Date/Time: </b></td><td>$f_long_date</td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Info: </b></td><td><font color=$f_color>$f_host_output</font></td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Total Hosts Up: </b></td><td>$f_totalup</td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Total Hosts Down: </b></td><td>$f_totaldown</td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Actions: </b></td><td><a href='$url/centreon/main.php?p=20201&o=hd&host_name=$f_host_name'><b>Acknowledge</b></a></td></tr>\r\n";
    $body .= "</table></table></body></html> \r\n";

    $headers = "From: $from\r\n";
    $headers = $headers."Content-type: text/html\r\n";

    /* Send eMail Now... */
    mail($f_to, $subject, $body, $headers);



?>
