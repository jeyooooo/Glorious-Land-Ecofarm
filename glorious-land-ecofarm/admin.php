<?php

session_start();

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

require_once "config/database.php";


/* TOTAL TREES */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
");

if (!$result) {
    die("Database query failed: " . $conn->error);
}

$totalTrees = $result->fetch_assoc()['total'];


/* HEALTHY TREES */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
    WHERE status = 'Healthy'
");

$healthyTrees = $result->fetch_assoc()['total'];


/* GROWING TREES */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
    WHERE status = 'Growing'
");

$growingTrees = $result->fetch_assoc()['total'];


/* MONITORING TREES */

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
    WHERE status = 'Monitoring'
");

$monitoringTrees = $result->fetch_assoc()['total'];


/* TREE RECORDS */

$trees = $conn->query("
    SELECT
        id,
        tree_code,
        common_name,
        scientific_name,
        status,
        barangay,
        municipality
    FROM trees
    ORDER BY common_name ASC
");

if (!$trees) {
    die("Tree records query failed: " . $conn->error);
}


require_once "includes/header.php";

?>


<main class="container">


    <section class="section">


        <!-- DASHBOARD HEADER -->

        <div class="section-head">


            <div>

                <p>
                    ADMINISTRATION
                </p>

                <h2>
                    Admin Dashboard
                </h2>

                <p>

                    Welcome,
                    <?= htmlspecialchars(
                        $_SESSION['admin_username']
                    ) ?>.

                </p>

            </div>


            <div>

                <a
                    href="add-tree.php"
                    class="btn btn-primary"
                >
                    + Add Tree
                </a>


                <a
                    href="logout.php"
                    class="btn btn-light"
                >
                    Logout
                </a>

            </div>


        </div>



        <!-- STATISTICS -->

        <div class="admin-grid">


            <div class="action-card">

                <h3>
                    Total Trees
                </h3>

                <strong>
                    <?= $totalTrees ?>
                </strong>

            </div>



            <div class="action-card">

                <h3>
                    Healthy
                </h3>

                <strong>
                    <?= $healthyTrees ?>
                </strong>

            </div>



            <div class="action-card">

                <h3>
                    Growing
                </h3>

                <strong>
                    <?= $growingTrees ?>
                </strong>

            </div>



            <div class="action-card">

                <h3>
                    Monitoring
                </h3>

                <strong>
                    <?= $monitoringTrees ?>
                </strong>

            </div>


        </div>



        <!-- TREE RECORDS -->

        <div class="section">


            <div class="section-head">


                <div>

                    <h2>
                        Tree Records
                    </h2>

                    <p>
                        Manage existing database records.
                    </p>

                </div>


            </div>



            <div class="table-wrap">


                <table>


                    <thead>

                        <tr>

                            <th>
                                Code
                            </th>

                            <th>
                                Tree
                            </th>

                            <th>
                                Scientific Name
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Location
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        <?php if ($trees->num_rows > 0): ?>


                            <?php while (
                                $tree =
                                $trees->fetch_assoc()
                            ): ?>


                                <tr>


                                    <td>

                                        <?= htmlspecialchars(
                                            $tree['tree_code']
                                        ) ?>

                                    </td>



                                    <td>

                                        <?= htmlspecialchars(
                                            $tree['common_name']
                                        ) ?>

                                    </td>



                                    <td>

                                        <em>

                                            <?= htmlspecialchars(
                                                $tree['scientific_name']
                                            ) ?>

                                        </em>

                                    </td>



                                    <td>


                                        <span
                                            class="badge <?= htmlspecialchars(
                                                strtolower(
                                                    $tree['status']
                                                )
                                            ) ?>"
                                        >

                                            <?= htmlspecialchars(
                                                $tree['status']
                                            ) ?>

                                        </span>


                                    </td>



                                    <td>

                                        <?= htmlspecialchars(
                                            $tree['barangay']
                                        ) ?>,

                                        <?= htmlspecialchars(
                                            $tree['municipality']
                                        ) ?>

                                    </td>



                                    <td>


                                        <a
                                            href="edit-tree.php?id=<?= (int)$tree['id'] ?>"
                                            class="btn btn-dark"
                                        >
                                            Edit
                                        </a>


                                        <a
                                            href="delete-tree.php?id=<?= (int)$tree['id'] ?>"
                                            class="btn btn-light"
                                            onclick="return confirm('Delete this tree record?');"
                                        >
                                            Delete
                                        </a>


                                    </td>


                                </tr>


                            <?php endwhile; ?>


                        <?php else: ?>


                            <tr>

                                <td colspan="6">

                                    No tree records found.

                                </td>

                            </tr>


                        <?php endif; ?>


                    </tbody>


                </table>


            </div>


        </div>


    </section>


</main>


<?php

$conn->close();

require_once "includes/footer.php";

?>