#!/usr/local/bin/perl

###
## Version  Date      Author    Description
##----------------------------------------------
## 0.1      11/10/15  Shini31   0.1 dev release
##
####

use strict;
use warnings;

use HTTP::Request::Common qw(POST);
use LWP::UserAgent;
use JSON;
use Getopt::Std;

# Global Variables
my $slack_posturl = 'https://hooks.slack.com/services/T00000000/B00000000/XXXXXXXXXXXXXXXXXXXXXXXX';
my $default_channel = '#centreon';
my $emoji_post = ':vertical_traffic_light:';
my $slack_username = 'centreon';
my %payload;

my $ua = LWP::UserAgent->new;
$ua->timeout(5);

my $req = POST("${slack_posturl}", ['payload' => encode_json($payload)]);

my $response = $ua->request($req);

if ($response->is_success) {
    exit(0);
}
else {
  die $resp->status_line;
}
