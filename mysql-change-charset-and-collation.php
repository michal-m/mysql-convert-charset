<?php
header('Content-Type: text/plain');

require 'config.php';

$dbl = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

if ($dbl->connect_errno) {
    echo "Failed to connect to MySQL: (" . $dbl->connect_errno . ") " . $dbl->connect_error;
    exit(1);
}

$CHARSET    = $dbl->real_escape_string($CHARSET);
$COLLATION  = $dbl->real_escape_string($COLLATION);
