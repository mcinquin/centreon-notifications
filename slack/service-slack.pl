#!/bin/perl

###
## Version  Date      Author    Description
##----------------------------------------------
## 1.0      22/11/15  Shini31   1.0 stable release
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
my $version = "1.0";
my $change_date = "22/11/2015";

## Slack
my $slack_posturl = 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX';
my $slack_channel = '#notifications';
my $slack_emoji_post = ':vertical_traffic_light:';
my $slack_username = 'centreon';

my $slack_payload = {
           channel => $slack_channel,
           username => $slack_username,
           icon_emoji => $slack_emoji_post,
};

## Centreon
my $centreon_url = "https://centreon.yourdomain.com:8081";



# Options
my %options;
GetOptions (\%options,'host:s', 'output:s', 'service:s', 'state:s', 'address:s');

if (!defined($options{host})) {
    print "Need --host option\n";
    exit 1;
}

if (!defined($options{output})) {
    print "Need --output option\n";
    exit 1;
}

if (!defined($options{service})) {
    print "Need --service option\n";
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



# Notification text
if ($options{state} eq 'RECOVERY') {
    $slack_payload->{attachments} = [
        {
            fallback => 'Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state},
            color => 'good',
            fields => [
                {
                    title => 'Service',
                    value => $options{service},
                    short => 'true',
                },
                {
                    title => 'Host',
                    value => $options{host},
                    short => 'true',
                },
            ]
        },
    ],
} elsif ($options{state} eq 'WARNING') {
    $slack_payload->{attachments} = [
        {
            fallback => 'Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state} . ': ' . $centreon_url . '/centreon/main.php?p=20201&o=svcd&host_name=' . $options{host} . '&service_description=' . $options{service},
            text => '<' . $centreon_url . '/centreon/main.php?p=20201&o=svcd&host_name=' . $options{host} . '&service_description=' . $options{service} . '|Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state} . '>',
            color => 'warning',
            fields => [
                {
                    title => 'Service',
                    value => $options{service},
                    short => 'true',
                },
                {
                    title => 'Host',
                    value => $options{host},
                    short => 'true',
                },
                {
                    title => 'Output',
                    value => $options{output},
                    short => 'false',
                },
            ]
        },
    ],
} elsif ($options{state} eq 'CRITICAL') {
    $slack_payload->{attachments} = [
        {
            fallback => 'Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state} . ': ' . $centreon_url . '/centreon/main.php?p=20201&o=svcd&host_name=' . $options{host} . '&service_description=' . $options{service},
            text => '<' . $centreon_url . '/centreon/main.php?p=20201&o=svcd&host_name=' . $options{host} . '&service_description=' . $options{service} . '|Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state} . '>',
            color => 'danger',
            fields => [
                {
                    title => 'Service',
                    value => $options{service},
                    short => 'true',
                },
                {
                    title => 'Host',
                    value => $options{host},
                    short => 'true',
                },
                {
                    title => 'Output',
                    value => $options{output},
                    short => 'false',
                },
            ]
        },
    ],
} elsif ($options{state} eq 'UNKNOWN') {
    $slack_payload->{attachments} = [
        {
            fallback => 'Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state} . ': ' . $centreon_url . '/centreon/main.php?p=20201&o=svcd&host_name=' . $options{host} . '&service_description=' . $options{service},
            text => '<' . $centreon_url . '/centreon/main.php?p=20201&o=svcd&host_name=' . $options{host} . '&service_description=' . $options{service} . '|Service ' . $options{service} . ' (Host: ' . $options{host} . ') is ' . $options{state} . '>',
            color => '#DCDADA',
            fields => [
                {
                    title => 'Service',
                    value => $options{service},
                    short => 'true',
                },
                {
                    title => 'Host',
                    value => $options{host},
                    short => 'true',
                },
                {
                    title => 'Output',
                    value => $options{output},
                    short => 'false',
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
