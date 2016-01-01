#!/bin/perl

###
## Version  Date      Author    Description
##----------------------------------------------
## 1.0      22/11/15  Shini31   1.0 stable release
## 1.1      31/12/15  Cjudith   1.1 minor release
##
####

use strict;
use warnings;

use HTTP::Request::Common qw(POST);
use LWP::UserAgent;
use JSON;
use Getopt::Long;

# Global Variables
## Version
my $version = "1.1";
my $change_date = "31/12/2015";

## Slack
my $slack_posturl = 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX';
my $slack_channel = '#notifications';
my $slack_emoji_post = ':vertical_traffic_light:';
my $slack_username = 'centreon';


## Centreon
my $centreon_url = "https://centreon.yourdomain.com:8081";

# Options
my %options;
GetOptions (\%options,'host:s','state:s', 'address:s', 'channel:s');

if (!defined($options{host})) {
    print "Need --state option\n";
    exit 1;
}

if (!defined($options{state})) {
    print "Need --state option\n";
    exit 1;
}

if (!defined($options{address})) {
    print "Need --address option\n";
    exit 1;
}

if (!defined($options{channel})) {
    print "Need --channel option\n";
    exit 1;
}

my $slack_payload = {
           channel => $options{channel},
           username => $slack_username,
           icon_emoji => $slack_emoji_post,
};

# Notification text
if ($options{host} eq 'UP') {
    $slack_payload->{attachments} = [
        {
            fallback => 'Host' . $options{host} . ' is ' . $options{state},
            color => 'good',
            fields => [
                {
                    title => 'Host',
                    value => $options{host},
                    short => 'true',
                },
            ]
        },
    ],
} else {
    $slack_payload->{attachments} = [
        {
            fallback => 'Host ' . $options{host} . ' is ' . $options{state} . ': ' . $centreon_url . '/centreon/main.php?p=20102&o=hd&host_name=' . $options{host},
            text => '<' . $centreon_url . '/centreon/main.php?p=20102&o=hd&host_name=' . $options{host} . '|Host ' . $options{host} . ' is ' . $options{state} . '>',
            color => 'danger',
            fields => [
                {
                    title => 'Host',
                    value => $options{host},
                    short => 'true',
                },
                {
                    title => 'Address',
                    value => $options{address},
                    short => 'true,'
                },
            ]
        },
    ],
}



# HTTP Request
my $ua = LWP::UserAgent->new;
$ua->timeout(15);

my $req = POST($slack_posturl, ['payload' => encode_json($slack_payload)]);

my $response = $ua->request($req);

if ($response->is_success) {
    exit(0);
} else {
  die $response->status_line;
}
