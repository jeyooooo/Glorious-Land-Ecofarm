<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Glorious Land Ecofarm | Native Tree Database
    </title>

    <link
        rel="stylesheet"
        href="css/style.css"
    >

</head>

<body>

<header class="site-header">

    <div class="container nav-wrap">

        <a href="index.php" class="brand">

            <div class="brand-icon">
                🌳
            </div>

            <div>

                <strong>
                    Glorious Land Ecofarm
                </strong>

                <small>
                    Native Tree Database
                </small>

            </div>

        </a>

        <nav>

            <a href="index.php">
                Home
            </a>

            <a href="trees.php">
                Trees
            </a>

            <a href="map.php">
                Map
            </a>

            <a href="about.php">
                About
            </a>

            <?php if (isset($_SESSION['admin_id'])): ?>

                <a
                    href="admin.php"
                    class="nav-button"
                >
                    Admin
                </a>

            <?php else: ?>

                <a
                    href="login.php"
                    class="nav-button"
                >
                    Admin Login
                </a>

            <?php endif; ?>

        </nav>

    </div>

</header>