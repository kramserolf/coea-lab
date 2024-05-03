<?php

$hostname = '10-6-11-90.proxysql-cluster-passive.proxysql.svc.cluster.local:53220';
$username = 'b06b070ab5bf5f';
$password = '7a4c7399';
$database = 'heroku_ad0603b04410e2e';

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
  die("Could not connect to MySQL: " . $conn->connect_error);
}
