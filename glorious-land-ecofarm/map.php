<?php

require_once "config/database.php";


$result = $conn->query("
    SELECT
        id,
        tree_code,
        common_name,
        latitude,
        longitude,
        status
    FROM trees
    WHERE latitude IS NOT NULL
    AND longitude IS NOT NULL
    ORDER BY common_name ASC
");


if (!$result) {

    die(
        "Map query failed: "
        . $conn->error
    );

}


$trees = [];


while ($row = $result->fetch_assoc()) {

    $trees[] = $row;

}


require_once "includes/header.php";

?>


<main class="container">


    <section class="section">


        <!-- PAGE HEADER -->

        <div class="section-head">


            <div>

                <h2>
                    Tree Location Map
                </h2>

                <p>
                    A simple location view of the
                    registered tree records.
                </p>

            </div>


        </div>



        <!-- MAP -->

        <div class="map-box">


            <?php

            /*
             * Temporary visual positions
             * for the demo map.
             */

            $positions = [

                [20, 25],
                [38, 48],
                [55, 28],
                [70, 55],
                [28, 72],
                [62, 78],
                [78, 25],
                [48, 68]

            ];

            ?>


            <?php foreach (
                $trees as $index => $tree
            ): ?>


                <?php

                $position =
                    $positions[
                        $index % count($positions)
                    ];

                ?>


                <a
                    href="tree-details.php?id=<?= (int)$tree['id'] ?>"
                    class="pin"
                    style="
                        left: <?= $position[0] ?>%;
                        top: <?= $position[1] ?>%;
                    "
                    title="<?= htmlspecialchars(
                        $tree['common_name']
                    ) ?>"
                >
                    📍
                </a>


            <?php endforeach; ?>


            <div
                class="map-label"
                style="
                    left: 20px;
                    top: 20px;
                "
            >
                Glorious Land Ecofarm
            </div>


        </div>



        <!-- TREE LIST -->

        <div
            class="detail-grid"
            style="margin-top: 25px;"
        >


            <?php if (count($trees) > 0): ?>


                <?php foreach ($trees as $tree): ?>


                    <div class="info-card">


                        <h2>

                            <?= htmlspecialchars(
                                $tree['common_name']
                            ) ?>

                        </h2>


                        <p>

                            <?= htmlspecialchars(
                                $tree['tree_code']
                            ) ?>

                        </p>


                        <p>

                            Status:

                            <strong>

                                <?= htmlspecialchars(
                                    $tree['status']
                                ) ?>

                            </strong>

                        </p>


                        <a
                            href="tree-details.php?id=<?= (int)$tree['id'] ?>"
                            class="btn btn-dark"
                        >
                            View Tree
                        </a>


                    </div>


                <?php endforeach; ?>


            <?php else: ?>


                <div class="info-card">

                    <h2>
                        No Tree Locations
                    </h2>

                    <p>
                        No tree records currently have
                        latitude and longitude coordinates.
                    </p>

                </div>


            <?php endif; ?>


        </div>


    </section>


</main>



<?php

$conn->close();

require_once "includes/footer.php";

?>