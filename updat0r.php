<?php

/*
This script is for use on Amazon Linux 2 instances to check whether they have the latest version of updates on their system.
It will call out to a slack channel in the environment variables using monolog
*/

require('vendor/autoload.php');
use Dotenv\Dotenv;
use Monolog\Logger;
use Monolog\Handler\SlackWebhookHandler;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable('.')->load();

$infolog = new Logger('info');
$infolog->pushHandler(new SlackWebhookHandler(
    $_ENV['SLACK_CHANNEL_HOOK_INFO'],
    null, null, true, null, false, false, Logger::INFO
));



$output = shell_exec("yum updateinfo");
$updatecount = intval(shell_exec("yum updateinfo list updates | wc -l"))-2; // -2 because of the header and footer

print($updatecount . "\n");

/*
Loaded plugins: extras_suggestions, langpacks, priorities, update-motd
Updates Information Summary: updates
    8 Security notice(s)
        3 important Security notice(s)
        1 low Security notice(s)
        4 medium Security notice(s)
updateinfo summary done
*/

$matches = [];
$pattern = "/[ ]+[0-9] [a-z]+ Security notice\(s\)/";
$ret = preg_match_all($pattern, $output, $matches);

$instance_id = shell_exec("curl -s http://169.254.169.254/latest/meta-data/instance-id");


if ($ret > 0 || $updatecount > 0) {
    $matches = implode("\n", $matches[0]);
    if ($matches == "") {
        $matches = "No security concerns";
    }
    $infolog->info("Instance {$instance_id} has:\n" . $matches . "\n" . $updatecount . " update(s) available to install" . "\n");
}
