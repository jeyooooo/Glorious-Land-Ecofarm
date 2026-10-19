<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";


$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($id > 0) {

    $stmt = $conn->prepare("
        DELETE FROM trees
        WHERE id = ?
    ");


    if ($stmt) {

        $stmt->bind_param("i", $id);

        $stmt->execute();

        $stmt->close();

    }

}


$conn->close();


header("Location: admin.php");

exit;

?>