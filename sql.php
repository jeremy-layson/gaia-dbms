<?php
$myfile = fopen(__DIR__ . "../active-schema.txt", "r") or die("Unable to open file!");
$schema = fgets($myfile);
fclose($myfile);

$link = mysqli_connect("127.0.0.1", "root", "", $schema);

if (!$link) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}