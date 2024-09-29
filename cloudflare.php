#!/usr/bin/php -d open_basedir=/usr/syno/bin/ddns
<?php

if ($argc !== 5) {
    echo 'badparam';
    exit();
}

// creation timestamp
$date = date('m-d-Y H:i:s', time());

$account = (string)$argv[1];
$pwd = (string)$argv[2];
$hostname = (string)$argv[3];
$ip = (string)$argv[4];

// check the hostname contains '.'
if (strpos($hostname, '.') === false) {
    echo 'notfqdn';
    exit();
}

// only for IPv4 format
if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
    echo "badparam";
    exit();
}

$zone_id = '__CLOUDFLARE_ZONE_ID__'; // Cloudflare zone ID
$ttl = 300; // 5 mins

$url = "https://api.cloudflare.com/client/v4/zones/$zone_id/dns_records/$account";

$headers = [
    'Content-Type: application/json',
    "Authorization: Bearer $pwd", // Cloudflaire API Token
];

$body = json_encode([
    'comment' => "Synology DDNS service on $date",
    'type' => 'A',
    'content' => $ip,
    'proxied' => false,
    'name' => $hostname,
    'ttl' => $ttl
]);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$res = curl_exec($ch);
$json = json_decode($res, true);
$http_status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if ($http_status_code !== 200) {
    if ($http_status_code === 400 && $json['errors'][0]['code'] === 1001)
        echo 'badauth';
    else
        echo '911';
    exit;
} else {
    echo 'good';
    exit;
}

curl_close($ch);
