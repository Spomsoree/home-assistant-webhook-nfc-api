<?php

$url = 'localhost:8123/' . $_GET['route'];
$ch  = curl_init();

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_exec($ch);

?>

<script>window.close();</script>