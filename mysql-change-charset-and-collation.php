<?php
header('Content-Type: text/plain');
require 'config.php';

$dbl = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

if ($dbl->connect_errno) {
    echo 'Failed to connect to MySQL: (' . $dbl->connect_errno . ') ' . $dbl->connect_error;
    exit(1);
}

$CHARSET    = $dbl->real_escape_string($CHARSET);
$COLLATION  = $dbl->real_escape_string($COLLATION);

// Updating Database
$query = 'ALTER DATABASE `' . $DB_NAME . '` CHARACTER SET = ' . $CHARSET . ' COLLATE = ' . $COLLATION;

if ($dbl->query($query) !== TRUE) {
    echo 'Failed to ALTER `' . $DB_NAME . '` database.';
    exit(1);
}

// Updating Tables
// Using CONVERT TO converts columns too
$result_tables = $dbl->query('SHOW TABLES');

while ($row_tables = $result_tables->fetch_row()) {
    $TBL_NAME = $row_tables[0];
    $query = 'ALTER TABLE `' . $TBL_NAME . '` CONVERT TO CHARACTER SET ' . $CHARSET . ' COLLATE ' . $COLLATION;

    if ($dbl->query($query) !== TRUE) {
        echo 'Failed to ALTER `' . $TBL_NAME . '` table.';
        exit(1);
    }
}
