<?php

require_once "config/database.php";

$pageTitle = "Native Tree Database";

$search = trim($_GET['search'] ?? '');

$status = $_GET['status'] ?? '';

$sql = "
    SELECT *
    FROM trees
    WHERE 1=1
";

$params = [];
$types = "";

if ($search !== '') {

    $sql .= "
        AND (
            tree_code LIKE ?
            OR common_name LIKE ?
            OR scientific_name LIKE ?
            OR family LIKE ?
            OR barangay LIKE ?
            OR municipality LIKE ?
            OR specific_location LIKE ?
            OR owner_name LIKE ?
        )
    ";

    $searchValue = "%" . $search . "%";

    for ($i = 0; $i < 8; $i++) {
        $params[] = $searchValue;
        $types .= "s";
    }
}

if (
    in_array(
        $status,
        ['Healthy', 'Growing', 'Monitoring'],
        true
    )
) {

    $sql .= "
        AND status = ?
    ";

    $params[] = $status;
    $types .= "s";
}

$sql .= "
    ORDER BY common_name ASC
";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die(
        "Tree query failed: " .
        $conn->error
    );
}

if (!empty($params)) {

    $stmt->bind_param(
        $types,
        ...$params
    );
}

$stmt->execute();

$trees = $stmt->get_result();

require "includes/header.php";

?>

<section class="section">

    <div class="container">

        <div class="section-head">

            <div>

                <div
                    class="eyebrow"
                    style="color:#58a86c"
                >
                    Database
                </div>

                <h2>
                    All Native Trees
                </h2>

                <p>
                    Search and explore registered trees.
                </p>

            </div>

            <?php if (!empty($_SESSION['admin_id'])): ?>

                <a
                    href="add-tree.php"
                    class="btn btn-dark"
                >
                    + Add Tree
                </a>

            <?php endif; ?>

        </div>


        <form
            method="GET"
            class="filters"
        >

            <input
                type="text"
                name="search"
                placeholder="Search tree, species, barangay..."
                value="<?= htmlspecialchars($search) ?>"
            >

            <select name="status">

                <option value="">
                    All Statuses
                </option>

                <option
                    value="Healthy"
                    <?= $status === 'Healthy'
                        ? 'selected'
                        : '' ?>
                >
                    Healthy
                </option>

                <option
                    value="Growing"
                    <?= $status === 'Growing'
                        ? 'selected'
                        : '' ?>
                >
                    Growing
                </option>

                <option
                    value="Monitoring"
                    <?= $status === 'Monitoring'
                        ? 'selected'
                        : '' ?>
                >
                    Monitoring
                </option>

            </select>

            <button
                type="submit"
                class="btn btn-dark"
            >
                Search
            </button>

        </form>


        <div class="tree-grid">

            <?php if ($trees->num_rows > 0): ?>

                <?php while ($tree = $trees->fetch_assoc()): ?>

                    <article class="card">

                        <div class="tree-img">
                            🌱
                        </div>

                        <div class="card-body">

                            <small>
                                <?= htmlspecialchars(
                                    $tree['tree_code']
                                ) ?>
                            </small>

                            <h3>
                                <?= htmlspecialchars(
                                    $tree['common_name']
                                ) ?>
                            </h3>

                            <div class="scientific">

                                <?= htmlspecialchars(
                                    $tree['scientific_name']
                                ) ?>

                            </div>

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

                            <div class="location">

                                📍

                                <?= htmlspecialchars(
                                    $tree['barangay']
                                    ?: 'N/A'
                                ) ?>,

                                <?= htmlspecialchars(
                                    $tree['municipality']
                                    ?: 'N/A'
                                ) ?>

                            </div>

                            <a
                                href="tree-details.php?id=<?= (int)$tree['id'] ?>"
                                class="btn btn-dark"
                            >
                                View Details
                            </a>

                        </div>

                    </article>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="card">

                    <div class="card-body">

                        <h3>
                            No tree records found.
                        </h3>

                    </div>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<?php

$stmt->close();

$conn->close();

require "includes/footer.php";

?>