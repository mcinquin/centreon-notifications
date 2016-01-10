centreon-notifications
=================


## Mail
# Setup

1. Download the scripts in the plugins directory and modify these following variables :
    * $url : URL of the Centreon Web UI ( eg. https://centreon.foo.bar:8081 )
    * $from : from email address desired
2. Make the files executable.
3. On distributed platform, don't miss to install php on your pollers
4. Change the command line for host-notify-by-email and service-notify-by-email notification's command :
    * `$USER1$/host-email.php "$NOTIFICATIONTYPE$" "$HOSTNAME$" "$HOSTALIAS$" "$HOSTSTATE$" "$HOSTADDRESS$" "$HOSTOUTPUT$" "$LONGDATETIME$" "$SERVICEDESC$" "$SERVICESTATE$" "$CONTACTEMAIL$" "$TOTALHOSTSUP$" "$TOTALHOSTSDOWN$" "$HOSTACKAUTHOR$" "$HOSTACKCOMMENT$"`
    * `$USER1$/service-email.php "$NOTIFICATIONTYPE$" "$HOSTNAME$" "$HOSTALIAS$" "$HOSTSTATE$" "$HOSTADDRESS$" "$SERVICEOUTPUT$" "$LONGDATETIME$" "$SERVICEDESC$" "$SERVICESTATE$" "$CONTACTEMAIL$" "$SERVICEDURATIONSEC$" "$SERVICEEXECUTIONTIME$" "$TOTALSERVICESWARNING$" "$TOTALSERVICESCRITICAL$" "$TOTALSERVICESUNKNOWN$" "$LASTSERVICEOK$" "$LASTSERVICEWARNING$" "$SERVICENOTIFICATIONNUMBER$" "$SERVICEACKAUTHOR$" "$SERVICEACKCOMMENT$"`
5. Generate, move and export the new configuration on your all pollers

# Screenshots
![host.png](https://raw.githubusercontent.com/Shini31/centreon-notifications/master/mail/host_email.png)
![service.png](https://raw.githubusercontent.com/Shini31/centreon-notifications/master/mail/service_email.png)

# Credits
* centreon-notifications is a fork of nagios-notifications. nagios-notifications is originally written by seancdugan at [nagios-notifications](https://github.com/seancdugan/nagios-notifications).


## Slack
# Setup

1. Set up an incoming webhook integration in your Slack team : [Slack incoming webhook integration](https://api.slack.com/incoming-webhooks)
2. Download the scripts in the plugins directory
3. Take config.ini.example as a template, create a file config.ini in same directory and correct configuration according to your preferences:
    * centreon_url : URL of the Centreon Web UI ( eg. https://centreon.foo.bar:8081 )
    * slack_posturl : URL of incoming webhook ( eg. https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX )
    * slack_username : Integration's username (eg. Centreon)
    * slack_emoji_post : Emoji for each post
4. Don't miss install the following Perl modules : HTTP::Request::Common, LWP::UserAgent, JSON, Getopt::Long.
5. Create two new notification's command, host-notify-by-slack and service-notify-by-slack :
    * `perl /directoryofplugins/centreon-notifications/slack/host-slack.pl --host="$HOSTNAME$" --state="$HOSTSTATE$" --address="$HOSTADDRESS$" --channel="#channelofyourchoice"`
    * `perl /directoryofplugins/centreon-notifications/slack/service-slack --host="$HOSTNAME$" --address="$HOSTADDRESS$" --output="$SERVICEOUTPUT$" --service="$SERVICEDESC$" --state="$SERVICESTATE$" --channel="#channelofyourchoice"`
6. Adapt your notification's configuration for using theses new commands
7. Generate, move and export the new configuration on your all pollers

# Screenshots
![host.png](https://raw.githubusercontent.com/Shini31/centreon-notifications/master/slack/host_slack.png)
![service.png](https://raw.githubusercontent.com/Shini31/centreon-notifications/master/slack/service_slack.png)
