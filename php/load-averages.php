<?php

/* 
 * RCA ID in RapidSpike is supplied in the command arguments.
 *
 * How to run:
 *     php load-averages.php 1111aaa1-8547-4ab9-8aa5-e33179937a0e
 */
$id = $argv[1];

// Get the three load averages (1 minute, 5 minute, 15 minute])
list($one, $five, $fifteen) = sys_getloadavg();

$query = array(
    'id' => $id,
    'loadavg1' => round($one, 3),
    'loadavg5' => round($five, 3),
    'loadavg15' => round($fifteen, 3)
);

$url = 'https://results.rapidspike.com/rca/';
$query = http_build_query($query, '', '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "{$url}?{$query}");
$resp = curl_exec($curl);
print_r($resp);
curl_close($curl);
