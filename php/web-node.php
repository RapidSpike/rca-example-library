<?php

/* 
 * RCA ID in RapidSpike is supplied in the command arguments.
 *
 * How to run:
 *     php web-node.php 1111aaa1-8547-4ab9-8aa5-e33179937a0e
 */
$id = $argv[1];

// Load Averages
list($load_avg_one, $load_avg_five, $load_avg_fifteen) = sys_getloadavg();

// RAM usage
$ram_free = (string) trim(shell_exec('free'));
$arrRamFree = explode("\n", $ram_free);
$mem = array_values(array_filter(explode(" ", $arrRamFree[1])));
$ram_usage_percent = round($mem[2] / $mem[1] * 100, 0, PHP_ROUND_HALF_UP);

// Disk usage
$total = disk_total_space('/');
$free = disk_free_space('/');
$disk_free_percent = round(($free / $total) * 100, 0, PHP_ROUND_HALF_UP);
$disk_used_percent = round((($total - $free) / $total) * 100, 0, PHP_ROUND_HALF_UP);

// Apache installed/running
$apache_installed = file_exists('/etc/init.d/apache2');
$apache_status = (string) trim(shell_exec('service apache2 status'));
$apache_running = $apache_status === '* apache2 is running';

$query = array(
    'id' => $id,
    'loadavg1' => round($load_avg_one, 4),
    'loadavg5' => round($load_avg_five, 4),
    'loadavg15' => round($load_avg_fifteen, 4),
    'ramused' => $ram_usage_percent,
    'freedisk' => $disk_free_percent,
    'useddisk' => $disk_used_percent,
    'apacheinst' => $apache_installed,
    'apacherun' => $apache_running,
);

$url = 'https://results.rapidspike.com/rca/';
$query = http_build_query($query, '', '&');

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, "{$url}?{$query}");
$resp = curl_exec($curl);
print_r($resp);
curl_close($curl);
