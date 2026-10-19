<?php

require_once "config/database.php";


// ===============================
// TEST DATABASE
// ===============================

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
");

if (!$result) {

    die(
        "<h2>Database Query Error</h2>" .
        "<p>" . htmlspecialchars($conn->error) . "</p>"
    );

}

$row = $result->fetch_assoc();

$totalTrees = $row['total'];


// ===============================
// TOTAL OWNERS
// ===============================

$result = $conn->query("
    SELECT COUNT(DISTINCT owner_name) AS total
    FROM trees
    WHERE owner_name IS NOT NULL
    AND owner_name != ''
");

if (!$result) {

    die(
        "<h2>Owner Query Error</h2>" .
        "<p>" . htmlspecialchars($conn->error) . "</p>"
    );

}

$row = $result->fetch_assoc();

$totalOwners = $row['total'];


// ===============================
// HEALTHY TREES
// ===============================

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
    WHERE status = 'Healthy'
");

if (!$result) {

    die(
        "<h2>Healthy Tree Query Error</h2>" .
        "<p>" . htmlspecialchars($conn->error) . "</p>"
    );

}

$row = $result->fetch_assoc();

$healthyTrees = $row['total'];


// ===============================
// MONITORING TREES
// ===============================

$result = $conn->query("
    SELECT COUNT(*) AS total
    FROM trees
    WHERE status = 'Monitoring'
");

if (!$result) {

    die(
        "<h2>Monitoring Tree Query Error</h2>" .
        "<p>" . htmlspecialchars($conn->error) . "</p>"
    );

}

$row = $result->fetch_assoc();

$monitoringTrees = $row['total'];


// ===============================
// RECENT TREES
// ===============================

$recentTrees = $conn->query("
    SELECT
        id,
        tree_code,
        common_name,
        scientific_name,
        status,
        barangay,
        municipality
    FROM trees
    ORDER BY created_at DESC
    LIMIT 4
");

if (!$recentTrees) {

    die(
        "<h2>Recent Trees Query Error</h2>" .
        "<p>" . htmlspecialchars($conn->error) . "</p>"
    );

}


// ===============================
// HEADER
// ===============================

include "includes/header.php";

?>


<section class="hero">

    <div class="container hero-content">

        <div>

            <div class="eyebrow">
                NATIVE TREE DATABASE SYSTEM
            </div>

            <h1>
                Discover and Document
                Native Trees
            </h1>

            <p>
                A digital database for organizing,
                documenting, and monitoring native
                tree species found at Glorious Land Ecofarm.
            </p>

            <div class="hero-buttons">

                <a
                    href="trees.php"
                    class="btn btn-primary"
                >
                    Explore Tree Database
                </a>

                <a
                    href="about.php"
                    class="btn btn-light"
                >
                    Learn More
                </a>

            </div>

        </div>

    </div>

</section>


<section class="section">

    <div class="container">

        <div class="stats">

            <div class="stat">

                <div class="icon">
                    🌳
                </div>

                <div>

                    <strong>
                        <?= $totalTrees ?>
                    </strong>

                    <span>
                        Total Trees
                    </span>

                </div>

            </div>


            <div class="stat">

                <div class="icon">
                    👤
                </div>

                <div>

                    <strong>
                        <?= $totalOwners ?>
                    </strong>

                    <span>
                        Tree Owners
                    </span>

                </div>

            </div>


            <div class="stat">

                <div class="icon">
                    🌱
                </div>

                <div>

                    <strong>
                        <?= $healthyTrees ?>
                    </strong>

                    <span>
                        Healthy Trees
                    </span>

                </div>

            </div>


            <div class="stat">

                <div class="icon">
                    🔎
                </div>

                <div>

                    <strong>
                        <?= $monitoringTrees ?>
                    </strong>

                    <span>
                        Under Monitoring
                    </span>

                </div>

            </div>

        </div>

    </div>

</section>


<section class="section">

    <div class="container">

        <div class="section-head">

            <div>

                <div class="eyebrow">
                    TREE DATABASE
                </div>

                <h2>
                    Recently Added Trees
                </h2>

            </div>

            <a
                href="trees.php"
                class="btn btn-dark"
            >
                View All Trees
            </a>

        </div>


        <div class="tree-grid">

            <?php if ($recentTrees->num_rows > 0): ?>

                <?php while ($tree = $recentTrees->fetch_assoc()): ?>

                    <div class="card">

                        <div class="tree-img">
                            🌳
                        </div>

                        <div class="card-body">

                            <span
                                class="badge <?= strtolower(htmlspecialchars($tree['status'])) ?>"
                            >
                                <?= htmlspecialchars($tree['status']) ?>
                            </span>

                            <h3>
                                <?= htmlspecialchars($tree['common_name']) ?>
                            </h3>

                            <p class="scientific">
                                <?= htmlspecialchars($tree['scientific_name']) ?>
                            </p>

                            <p class="location">

                                📍

                                <?= htmlspecialchars($tree['barangay'] ?: 'No barangay') ?>,

                                <?= htmlspecialchars($tree['municipality'] ?: 'No municipality') ?>

                            </p>

                            <p>

                                Tree Code:

                                <strong>
                                    <?= htmlspecialchars($tree['tree_code']) ?>
                                </strong>

                            </p>

                            <a
                                href="tree-details.php?id=<?= (int) $tree['id'] ?>"
                                class="btn btn-primary"
                            >
                                View Details
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="card">

                    <div class="card-body">

                        <h3>
                            No tree records yet.
                        </h3>

                        <p>
                            Add a tree record through
                            the admin dashboard.
                        </p>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>


<section class="section">

    <div class="container">

        <div class="section-head">

            <div>

                <div class="eyebrow">
                    ABOUT THE SYSTEM
                </div>

                <h2>
                    Organized Tree Information
                </h2>

            </div>

        </div>


        <div class="detail-grid">

            <div class="info-card">

                <h3>
                    Tree Information
                </h3>

                <p>
                    Each tree record can contain its
                    common name, scientific name, family,
                    age, height, trunk diameter, and status.
                </p>

            </div>


            <div class="info-card">

                <h3>
                    Location Information
                </h3>

                <p>
                    Tree records can include the barangay,
                    municipality, specific location,
                    latitude, and longitude.
                </p>

            </div>


            <div class="info-card">

                <h3>
                    Monitoring
                </h3>

                <p>
                    Tree health and monitoring status can
                    be recorded to help organize information
                    about the trees in the ecofarm.
                </p>

            </div>

        </div>

    </div>

</section>


<?php

include "includes/footer.php";

$conn->close();

?>
