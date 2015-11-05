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
    $f_notify_type =array_shift($argv);  /*1*/
    $f_host_name =array_shift($argv);    /*2*/
    $f_host_alias =array_shift($argv);   /*3*/
    $f_host_state =array_shift($argv);    /*4*/
    $f_host_address =array_shift($argv);   /*5*/
    $f_serv_output =array_shift($argv);   /*6*/
    $f_long_date =array_shift($argv);     /*7*/
    $f_serv_desc  =array_shift($argv);    /*8*/
    $f_serv_state  =array_shift($argv);   /*9*/
    $f_to  =array_shift($argv);           /*10*/
    $f_duration = round((array_shift($argv))/60,2);   /*11*/
    $f_exectime =array_shift($argv);       /*12*/
    $f_totwarnings =array_shift($argv);     /*13*/
    $f_totcritical =array_shift($argv);      /*14*/
    $f_totunknowns =array_shift($argv);     /*15*/
    $f_lastserviceok = array_shift($argv);    /*16*/
    $f_lastwarning = array_shift($argv);     /*17*/
    $f_attempts= array_shift($argv);     /*18*/
    $f_ackauthor= array_shift($argv);     /*19*/
    $f_ackcomment= array_shift($argv);     /*20*/

    $f_downwarn = $f_duration;
    $f_color="#dddddd";
    if($f_serv_state=="WARNING") {$f_color="#f48400";}
    if($f_serv_state=="CRITICAL") {$f_color="#f40000";}
    if($f_serv_state=="OK") {$f_color="#00b71a";}
    if($f_serv_state=="UNKNOWN") {$f_color="#cc00de";}

    // Check If File Exists ###########
    if($f_notify_type=="PROBLEM")
    {
        $currenttime = time();
        $file_name = "/tmp/$f_host_name.$f_serv_desc.txt";
        if ($f_attempts==1)
        {
            if(file_exists($file_name)==true) {unlink($file_name);}
            $currenttime = $currenttime+round(($f_duration * 60),0);
            file_put_contents($file_name, "$currenttime");
        }
    }

    if($f_notify_type=="RECOVERY")
    {
        $currenttime = time();
        $oldtime = time();
        $file_name = "/tmp/$f_host_name.$f_serv_desc.txt";
        if (file_exists($file_name)==true)
        {
            $oldtime = intval(file_get_contents($file_name));
        }
        $f_downwarn = round(($currenttime - $oldtime)/60,2);
    }

    $f_serv_output = str_replace("(","/",$f_serv_output);
    $f_serv_output = str_replace(")","/",$f_serv_output);
    $f_serv_output = str_replace("[","/",$f_serv_output);
    $f_serv_output = str_replace("]","/",$f_serv_output);

    $subject = "[CENTREON] $f_notify_type $f_host_name/$f_serv_desc [$f_serv_state]";
    $url = "https://centreon.yourdomain.com:8081";
    $token = "PpaktymnW";
    $user = "guest";

    $from = "centreon@yourdomain.com";
    $body = "<html><body><table border=0 width='98%' cellpadding=0 cellspacing=0><tr><td valign='top'>\r\n";
    $body .= "<table border=0 cellpadding=0 cellspacing=0 width='98%'>";
    $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>Notification:</font></b></td><td><font ";
    $body .= "color=#ffffff><b>$f_notify_type [$f_serv_state]</b></font></td></tr>\r\n";
    if($f_ackauthor!="" && $f_ackcomment!=""){
        $body .= "<tr bgcolor=$f_color><td width='140'><b><font color=#ffffff>$f_ackauthor:</font></b></td><td><font color=#ffffff><b>$f_ackcomment</b></font></td></tr>\r\n";
    }
    $body .= "<tr bgcolor=#eeeeee><td><b>Service:</b></td><td><font color=#0000CC><b><a href='$url/centreon/main.php?p=20201&o=svcd&host_name=$f_host_name&service_description=$f_serv_desc&autologin=1&useralias=$user&token=$token'>$f_serv_desc</a></b></font></td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Host:</b></td><td><font color=#0000CC><b><a href='$url/centreon/main.php&autologin=1&useralias=$user&token=$token'>$url</a></b></font></td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Server:</b></td><td><b><a href='$url/centreon/main.php?p=20102&o=hd&host_name=$f_host_name&autologin=1&useralias=$user&token=$token'>$f_host_alias</a></b></td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Address:</b></td><td><b>$f_host_address</b></font></td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Date/Time:</b></td><td>$f_long_date UTC</td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td><b>Additional Info:</b></td><td><font color=$f_color>$f_serv_output</font></td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td><b>Commands:</b></td><td><a href='$url/centreon/main.php?p=20201&o=svcd&host_name=$f_host_name&service_description=$f_serv_desc&autologin=1&useralias=$user&token=$token'><b>Acknowledge</b></a></td></tr>\r\n";
    $body .= "</td><td valign='top'></tr></table><table border=0 cellpadding=0 cellspacing=0 width='96%'><tr bgcolor=#000055><td width='140'><b> \r\n";
    $body .= "<font color=#FFFFFF>Service Summary</font></b></td><td>.</td></tr> \r\n";
    $body .= "<tr bgcolor=#fefefe><td>Service <i><b>DOWN</b></i> For: </td><td> $f_downwarn<i>m</i></td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td>Total Warnings: </td><td> $f_totwarnings</td></tr>\r\n";
    $body .= "<tr bgcolor=#fefefe><td>Total Critical: </td><td> $f_totcritical</td></tr>\r\n";
    $body .= "<tr bgcolor=#eeeeee><td>Total Unknowns: </td><td> $f_totunknowns</td></tr>\r\n";
    $body .= "</body></html> \r\n";

    $headers  = "From: $from\r\n";
    $headers .= "Content-type: text/html\r\n";

    /* Send eMail Now... */
    $m_true = mail($f_to, $subject, $body, $headers);
    echo $m_true;


?>
