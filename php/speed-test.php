<?php

/* 
 * RCA ID in RapidSpike is supplied in the command arguments.
 *
 * How to run:
 *     php speed-test.php 1111aaa1-8547-4ab9-8aa5-e33179937a0e
 */
$id = $argv[1];

// Run the speed test cli. the '--json' arg sets output to JSON
$result = json_decode(exec('./speedtest-cli --json'));

// Put together the query parts
$query = array(
    'id' => $id,
    'download' => number_format($result->download / 1048576, 2), 
    'upload' => number_format($result->upload / 1048576, 2)
);

$url = 'https://results.rapidspike.com/rca/';
$query = http_build_query($query, '', '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "{$url}?{$query}");
$resp = curl_exec($curl);
print_r($resp);
curl_close($curl);
