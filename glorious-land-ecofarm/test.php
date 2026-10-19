<?php

require_once "config/database.php";

echo "Database connected successfully!<br><br>";

$result = $conn->query("SELECT * FROM trees");

if (!$result) {

    die(
        "Trees table query failed.<br>" .
        "Error: " . $conn->error
    );

}

echo "Trees table is working!<br>";
echo "Number of tree records: " . $result->num_rows;

?>