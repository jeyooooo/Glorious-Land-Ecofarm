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


if ($id <= 0) {
    die("Invalid tree ID.");
}


/* GET EXISTING TREE */

$stmt = $conn->prepare("
    SELECT *
    FROM trees
    WHERE id = ?
    LIMIT 1
");

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$tree = $result->fetch_assoc();

$stmt->close();


if (!$tree) {
    die("Tree record not found.");
}


$error = "";


/* UPDATE TREE */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $tree_code = trim($_POST['tree_code'] ?? '');
    $common_name = trim($_POST['common_name'] ?? '');
    $scientific_name = trim($_POST['scientific_name'] ?? '');
    $family = trim($_POST['family'] ?? '');

    $age = trim($_POST['age'] ?? '');
    $height = trim($_POST['height'] ?? '');
    $trunk_diameter = trim($_POST['trunk_diameter'] ?? '');

    $status = trim($_POST['status'] ?? 'Healthy');

    $barangay = trim($_POST['barangay'] ?? '');
    $municipality = trim($_POST['municipality'] ?? '');
    $specific_location = trim($_POST['specific_location'] ?? '');

    $latitude = trim($_POST['latitude'] ?? '');
    $longitude = trim($_POST['longitude'] ?? '');

    $owner_name = trim($_POST['owner_name'] ?? '');
    $owner_contact = trim($_POST['owner_contact'] ?? '');
    $owner_address = trim($_POST['owner_address'] ?? '');
    $ownership_type = trim($_POST['ownership_type'] ?? '');

    $description = trim($_POST['description'] ?? '');
    $uses = trim($_POST['uses'] ?? '');
    $care_notes = trim($_POST['care_notes'] ?? '');

    $info_url = trim($_POST['info_url'] ?? '');
    $date_registered = trim($_POST['date_registered'] ?? '');


    if (
        $tree_code === '' ||
        $common_name === '' ||
        $scientific_name === ''
    ) {

        $error = "Please fill in the required fields.";

    } elseif (
        !in_array(
            $status,
            ['Healthy', 'Growing', 'Monitoring'],
            true
        )
    ) {

        $error = "Invalid tree status.";

    } else {

        $stmt = $conn->prepare("
            UPDATE trees
            SET
                tree_code = ?,
                common_name = ?,
                scientific_name = ?,
                family = ?,
                age = ?,
                height = ?,
                trunk_diameter = ?,
                status = ?,
                barangay = ?,
                municipality = ?,
                specific_location = ?,
                latitude = NULLIF(?, ''),
                longitude = NULLIF(?, ''),
                owner_name = ?,
                owner_contact = ?,
                owner_address = ?,
                ownership_type = ?,
                description = ?,
                uses = ?,
                care_notes = ?,
                info_url = NULLIF(?, ''),
                date_registered = NULLIF(?, '')
            WHERE id = ?
        ");


        if (!$stmt) {

            $error =
                "Database query failed: " .
                $conn->error;

        } else {

            $stmt->bind_param(
                "ssssssssssssssssssssssi",
                $tree_code,
                $common_name,
                $scientific_name,
                $family,
                $age,
                $height,
                $trunk_diameter,
                $status,
                $barangay,
                $municipality,
                $specific_location,
                $latitude,
                $longitude,
                $owner_name,
                $owner_contact,
                $owner_address,
                $ownership_type,
                $description,
                $uses,
                $care_notes,
                $info_url,
                $date_registered,
                $id
            );


            if ($stmt->execute()) {

                header("Location: admin.php");
                exit;

            } else {

                if ($stmt->errno === 1062) {

                    $error =
                        "Tree code already exists. " .
                        "Please use another code.";

                } else {

                    $error =
                        "Failed to update tree: " .
                        $stmt->error;

                }

            }


            $stmt->close();

        }

    }


    /*
     * Keep the user's submitted values
     * if there is an error.
     */

    $tree['tree_code'] = $tree_code;
    $tree['common_name'] = $common_name;
    $tree['scientific_name'] = $scientific_name;
    $tree['family'] = $family;
    $tree['age'] = $age;
    $tree['height'] = $height;
    $tree['trunk_diameter'] = $trunk_diameter;
    $tree['status'] = $status;
    $tree['barangay'] = $barangay;
    $tree['municipality'] = $municipality;
    $tree['specific_location'] = $specific_location;
    $tree['latitude'] = $latitude;
    $tree['longitude'] = $longitude;
    $tree['owner_name'] = $owner_name;
    $tree['owner_contact'] = $owner_contact;
    $tree['owner_address'] = $owner_address;
    $tree['ownership_type'] = $ownership_type;
    $tree['description'] = $description;
    $tree['uses'] = $uses;
    $tree['care_notes'] = $care_notes;
    $tree['info_url'] = $info_url;
    $tree['date_registered'] = $date_registered;
}


require_once "includes/header.php";

?>


<main class="container">

    <section class="section">


        <div class="section-head">

            <div>

                <p>ADMINISTRATION</p>

                <h2>
                    Edit Tree
                </h2>

                <p>
                    Update this tree record.
                </p>

            </div>


            <a
                href="admin.php"
                class="btn btn-light"
            >
                ← Back to Dashboard
            </a>

        </div>


        <?php if ($error !== ""): ?>

            <div class="alert">

                <?= htmlspecialchars($error) ?>

            </div>

        <?php endif; ?>


        <div class="form-card">


            <form
                method="POST"
                action="edit-tree.php?id=<?= $id ?>"
            >


                <div class="form-group">

                    <label for="tree_code">
                        Tree Code *
                    </label>

                    <input
                        type="text"
                        id="tree_code"
                        name="tree_code"
                        value="<?= htmlspecialchars($tree['tree_code']) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="common_name">
                        Common Name *
                    </label>

                    <input
                        type="text"
                        id="common_name"
                        name="common_name"
                        value="<?= htmlspecialchars($tree['common_name']) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="scientific_name">
                        Scientific Name *
                    </label>

                    <input
                        type="text"
                        id="scientific_name"
                        name="scientific_name"
                        value="<?= htmlspecialchars($tree['scientific_name']) ?>"
                        required
                    >

                </div>


                <div class="form-group">

                    <label for="family">
                        Family
                    </label>

                    <input
                        type="text"
                        id="family"
                        name="family"
                        value="<?= htmlspecialchars($tree['family'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="age">
                        Age
                    </label>

                    <input
                        type="text"
                        id="age"
                        name="age"
                        value="<?= htmlspecialchars($tree['age'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="height">
                        Height
                    </label>

                    <input
                        type="text"
                        id="height"
                        name="height"
                        value="<?= htmlspecialchars($tree['height'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="trunk_diameter">
                        Trunk Diameter
                    </label>

                    <input
                        type="text"
                        id="trunk_diameter"
                        name="trunk_diameter"
                        value="<?= htmlspecialchars($tree['trunk_diameter'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="status">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                    >

                        <option
                            value="Healthy"
                            <?= $tree['status'] === 'Healthy'
                                ? 'selected'
                                : '' ?>
                        >
                            Healthy
                        </option>

                        <option
                            value="Growing"
                            <?= $tree['status'] === 'Growing'
                                ? 'selected'
                                : '' ?>
                        >
                            Growing
                        </option>

                        <option
                            value="Monitoring"
                            <?= $tree['status'] === 'Monitoring'
                                ? 'selected'
                                : '' ?>
                        >
                            Monitoring
                        </option>

                    </select>

                </div>


                <div class="form-group">

                    <label for="barangay">
                        Barangay
                    </label>

                    <input
                        type="text"
                        id="barangay"
                        name="barangay"
                        value="<?= htmlspecialchars($tree['barangay'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="municipality">
                        Municipality
                    </label>

                    <input
                        type="text"
                        id="municipality"
                        name="municipality"
                        value="<?= htmlspecialchars($tree['municipality'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="specific_location">
                        Specific Location
                    </label>

                    <input
                        type="text"
                        id="specific_location"
                        name="specific_location"
                        value="<?= htmlspecialchars($tree['specific_location'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="latitude">
                        Latitude
                    </label>

                    <input
                        type="text"
                        id="latitude"
                        name="latitude"
                        value="<?= htmlspecialchars($tree['latitude'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="longitude">
                        Longitude
                    </label>

                    <input
                        type="text"
                        id="longitude"
                        name="longitude"
                        value="<?= htmlspecialchars($tree['longitude'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="owner_name">
                        Owner Name
                    </label>

                    <input
                        type="text"
                        id="owner_name"
                        name="owner_name"
                        value="<?= htmlspecialchars($tree['owner_name'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="owner_contact">
                        Owner Contact
                    </label>

                    <input
                        type="text"
                        id="owner_contact"
                        name="owner_contact"
                        value="<?= htmlspecialchars($tree['owner_contact'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="owner_address">
                        Owner Address
                    </label>

                    <input
                        type="text"
                        id="owner_address"
                        name="owner_address"
                        value="<?= htmlspecialchars($tree['owner_address'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="ownership_type">
                        Ownership Type
                    </label>

                    <input
                        type="text"
                        id="ownership_type"
                        name="ownership_type"
                        value="<?= htmlspecialchars($tree['ownership_type'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="description">
                        Description
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                    ><?= htmlspecialchars($tree['description'] ?? '') ?></textarea>

                </div>


                <div class="form-group">

                    <label for="uses">
                        Uses
                    </label>

                    <textarea
                        id="uses"
                        name="uses"
                        rows="4"
                    ><?= htmlspecialchars($tree['uses'] ?? '') ?></textarea>

                </div>


                <div class="form-group">

                    <label for="care_notes">
                        Care Notes
                    </label>

                    <textarea
                        id="care_notes"
                        name="care_notes"
                        rows="4"
                    ><?= htmlspecialchars($tree['care_notes'] ?? '') ?></textarea>

                </div>


                <div class="form-group">

                    <label for="info_url">
                        Information URL
                    </label>

                    <input
                        type="url"
                        id="info_url"
                        name="info_url"
                        value="<?= htmlspecialchars($tree['info_url'] ?? '') ?>"
                    >

                </div>


                <div class="form-group">

                    <label for="date_registered">
                        Date Registered
                    </label>

                    <input
                        type="date"
                        id="date_registered"
                        name="date_registered"
                        value="<?= htmlspecialchars($tree['date_registered'] ?? '') ?>"
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Changes
                </button>


                <a
                    href="admin.php"
                    class="btn btn-light"
                >
                    Cancel
                </a>


            </form>


        </div>


    </section>

</main>


<?php

$conn->close();

require_once "includes/footer.php";

?>