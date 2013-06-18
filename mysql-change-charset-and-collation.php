<?php
header('Content-Type: text/plain');

set_time_limit(0);
require 'config.php';

$dbl = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

if ($dbl->connect_errno) {
    echo 'Failed to connect to MySQL: (' . $dbl->connect_errno . ') ' . $dbl->connect_error;
    exit(1);
}

$CHARSET    = $dbl->real_escape_string($CHARSET);
$COLLATION  = $dbl->real_escape_string($COLLATION);
$DB_NAME    = $dbl->real_escape_string($DB_NAME);

// Updating Database
$query = 'ALTER DATABASE `' . $DB_NAME . '` CHARACTER SET = ' . $CHARSET . ' COLLATE = ' . $COLLATION;

if ($dbl->query($query) !== TRUE) {
    echo 'Failed to ALTER `' . $DB_NAME . '` database.';
    exit(1);
}

// Updating Tables
// Using CONVERT TO converts columns too
$result_tables = $dbl->query(
     "SELECT    `TABLE_NAME` "
    ."FROM      `information_schema`.`TABLES` "
    ."WHERE     `TABLE_SCHEMA` = '" . $DB_NAME . "' "
    ."AND       `TABLE_TYPE` = 'BASE TABLE'");

while ($row_tables = $result_tables->fetch_row()) {
    $TBL_NAME = $row_tables[0];
    $query = 'ALTER TABLE `' . $TBL_NAME . '` CONVERT TO CHARACTER SET ' . $CHARSET . ' COLLATE ' . $COLLATION;

    if ($dbl->query($query) !== TRUE) {
        echo 'Failed to ALTER `' . $TBL_NAME . '` table.';
        exit(1);
    }
}
