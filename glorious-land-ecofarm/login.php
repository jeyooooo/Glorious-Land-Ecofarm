<?php

session_start();


if (isset($_SESSION['admin_id'])) {

    header("Location: admin.php");

    exit;

}


require_once "config/database.php";


$error = "";


if ($_SERVER["REQUEST_METHOD"] === "POST") {


    $username = trim(
        $_POST['username'] ?? ''
    );


    $password = $_POST['password'] ?? '';


    if (
        $username === '' ||
        $password === ''
    ) {

        $error =
            "Please enter both username and password.";

    } else {


        $stmt = $conn->prepare("
            SELECT
                id,
                username,
                password
            FROM admins
            WHERE username = ?
            LIMIT 1
        ");


        if (!$stmt) {

            die(
                "Login query failed: " .
                $conn->error
            );

        }


        $stmt->bind_param(
            "s",
            $username
        );


        $stmt->execute();


        $result =
            $stmt->get_result();


        $admin =
            $result->fetch_assoc();


        if (
            $admin &&
            hash_equals(
                $admin['password'],
                hash(
                    'sha256',
                    $password
                )
            )
        ) {


            $_SESSION['admin_id'] =
                $admin['id'];


            $_SESSION['admin_username'] =
                $admin['username'];


            header(
                "Location: admin.php"
            );

            exit;


        } else {


            $error =
                "Invalid username or password.";

        }


        $stmt->close();

    }

}


require_once "includes/header.php";

?>


<main class="container">


    <section class="section">


        <div class="form-card">


            <h2>
                Admin Login
            </h2>


            <p>
                Sign in to manage tree records.
            </p>



            <?php if ($error !== ""): ?>


                <div class="alert">

                    <?= htmlspecialchars(
                        $error
                    ) ?>

                </div>


            <?php endif; ?>



            <form
                method="POST"
                action="login.php"
            >


                <div class="form-group">


                    <label for="username">
                        Username
                    </label>


                    <input
                        type="text"
                        id="username"
                        name="username"
                        autocomplete="username"
                        required
                    >


                </div>



                <div class="form-group">


                    <label for="password">
                        Password
                    </label>


                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >


                </div>



                <button
                    type="submit"
                    class="btn btn-dark"
                >
                    Login
                </button>


            </form>


        </div>


    </section>


</main>


<?php

$conn->close();

require_once "includes/footer.php";

?>