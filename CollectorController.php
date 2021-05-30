<?php

require __DIR__.'/vendor/autoload.php';

use InfluxDB2\Client;
use InfluxDB2\Model\WritePrecision;
use InfluxDB2\Point;

echo $_POST["HR"];

$token = '3AxbMHo453QJcgTljPFNFTNCqUDS4crmP8VcIbG-f0RrnJ8mr2ZGNznh3Jjjxnr616CC2Fwg5t2TCE9Ukxa90A==';
$org = 'Dizayka Org';
$bucket = 'SensorsData';

$client = new Client([
    "url" => "http://192.168.0.106:8086",
    "token" => $token,
]);

$writeApi = $client->createWriteApi();

$_SERVER['REMOTE_ADDR'];

if($_POST["HRValid"]) {
    $data = "sensors,host=".$_SERVER['REMOTE_ADDR']." HR=".$_POST["HR"];
    $writeApi->write($data, WritePrecision::S, $bucket, $org);
}
if($_POST["SPO2Valid"]) {
    $data = "sensors,host=".$_SERVER['REMOTE_ADDR']." SPO2=".$_POST["SPO2"];
    $writeApi->write($data, WritePrecision::S, $bucket, $org);
}
?>