<?php

/* 
 * RCA ID in RapidSpike is supplied in the command arguments.
 *
 * How to run:
 *     php php-error-count.php 1111aaa1-8547-4ab9-8aa5-e33179937a0e
 */
$id = $argv[1];

// Assuming this script runs once a day, we're going to count the 
// number of occurrances of 'error' in the error log from 1 day ago
$date = new DateTime();
$date->modify('-1 day');
$prev_date = $date->format('D M d');

// Run the command
$error_count = exec("grep '{$prev_date}' /var/log/apache2/api-dev_error.log.1 | grep -o 'error' | wc -l");

$query = array(
    'id' => $id,
    'count' => trim($error_count)
);

$url = 'https://results.rapidspike.com/rca/';
$query = http_build_query($query, '', '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "{$url}?{$query}");
$resp = curl_exec($curl);
print_r($resp);
curl_close($curl);
