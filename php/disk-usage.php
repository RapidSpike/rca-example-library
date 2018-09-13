<?php

/* 
 * RCA ID in RapidSpike is supplied in the command arguments.
 *
 * How to run:
 *     php disk-usage.php 1111aaa1-8547-4ab9-8aa5-e33179937a0e
 */
$id = $argv[1];

// Figure out disk usage stats
$total = disk_total_space('/');
$free = disk_free_space('/');
$used = $total - $free;

$free_percent = round(($free / $total) * 100, 0, PHP_ROUND_HALF_UP);
$used_percent = round(($used / $total) * 100, 0, PHP_ROUND_HALF_UP);

$query = array(
    'id' => $id,
    'freedisk' => $free_percent,
    'useddisk' => $used_percent
);

$url = 'https://results.rapidspike.com/rca/';
$query = http_build_query($query, '', '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "{$url}?{$query}");
$resp = curl_exec($curl);
print_r($resp);
curl_close($curl);
