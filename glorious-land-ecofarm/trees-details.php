<?php

require_once "config/database.php";


$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;


if ($id <= 0) {

    die("Invalid tree ID.");

}


$stmt = $conn->prepare("
    SELECT *
    FROM trees
    WHERE id = ?
    LIMIT 1
");


if (!$stmt) {

    die(
        "Query preparation failed: "
        . $conn->error
    );

}


$stmt->bind_param("i", $id);

$stmt->execute();


$result = $stmt->get_result();

$tree = $result->fetch_assoc();


if (!$tree) {

    die("Tree record not found.");

}


$statusClass = strtolower(
    $tree['status']
);


require_once "includes/header.php";

?>


<main class="container">


    <section class="section">


        <!-- PAGE HEADER -->

        <div class="section-head">


            <div>

                <p>
                    <?= htmlspecialchars(
                        $tree['tree_code']
                    ) ?>
                </p>


                <h2>

                    <?= htmlspecialchars(
                        $tree['common_name']
                    ) ?>

                </h2>


                <p class="scientific">

                    <?= htmlspecialchars(
                        $tree['scientific_name']
                    ) ?>

                </p>


                <span
                    class="badge <?= htmlspecialchars(
                        $statusClass
                    ) ?>"
                >

                    <?= htmlspecialchars(
                        $tree['status']
                    ) ?>

                </span>

            </div>



            <a
                href="trees.php"
                class="btn btn-light"
            >
                ← Back to Trees
            </a>


        </div>



        <!-- TREE INFORMATION -->

        <div class="detail-grid">


            <div class="info-card">


                <h2>
                    Tree Information
                </h2>


                <div class="info-row">

                    <strong>
                        Tree Code
                    </strong>

                    <span>
                        <?= htmlspecialchars(
                            $tree['tree_code']
                        ) ?>
                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Common Name
                    </strong>

                    <span>
                        <?= htmlspecialchars(
                            $tree['common_name']
                        ) ?>
                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Scientific Name
                    </strong>

                    <span>
                        <?= htmlspecialchars(
                            $tree['scientific_name']
                        ) ?>
                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Family
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['family']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Age
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['age']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Height
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['height']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Trunk Diameter
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['trunk_diameter']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Status
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['status']
                        ) ?>

                    </span>

                </div>


            </div>



            <!-- LOCATION -->

            <div class="info-card">


                <h2>
                    Location
                </h2>


                <div class="info-row">

                    <strong>
                        Barangay
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['barangay']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Municipality
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['municipality']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Specific Location
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['specific_location']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Latitude
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['latitude']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Longitude
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['longitude']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


            </div>



            <!-- OWNER -->

            <div class="info-card">


                <h2>
                    Owner Information
                </h2>


                <div class="info-row">

                    <strong>
                        Name
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['owner_name']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Contact
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['owner_contact']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Address
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['owner_address']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


                <div class="info-row">

                    <strong>
                        Ownership
                    </strong>

                    <span>

                        <?= htmlspecialchars(
                            $tree['ownership_type']
                                ?: 'N/A'
                        ) ?>

                    </span>

                </div>


            </div>



            <!-- DESCRIPTION -->

            <div class="info-card">


                <h2>
                    Description
                </h2>


                <p>

                    <?= nl2br(
                        htmlspecialchars(
                            $tree['description']
                                ?: 'No description available.'
                        )
                    ) ?>

                </p>



                <h3>
                    Uses
                </h3>


                <p>

                    <?= nl2br(
                        htmlspecialchars(
                            $tree['uses']
                                ?: 'No information available.'
                        )
                    ) ?>

                </p>



                <h3>
                    Care Notes
                </h3>


                <p>

                    <?= nl2br(
                        htmlspecialchars(
                            $tree['care_notes']
                                ?: 'No care notes available.'
                        )
                    ) ?>

                </p>



                <?php if (!empty($tree['info_url'])): ?>


                    <a
                        href="<?= htmlspecialchars(
                            $tree['info_url']
                        ) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-dark"
                    >
                        Learn More About This Species
                    </a>


                <?php endif; ?>


            </div>


        </div>


    </section>


</main>



<?php

$stmt->close();

$conn->close();

require_once "includes/footer.php";

?>