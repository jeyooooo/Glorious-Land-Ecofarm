<?php

//start the session
session_start();

//check if the admin is logged in 
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

//connect to the database
require_once "config/database.php";

//create variables for messages (error handling)
//example: tree code already exists.
$error = "";
$success = "";

//check if the the form was submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {

//get the tree information from the form
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

//check required fields
    if (
        $tree_code === '' ||
        $common_name === '' ||
        $scientific_name === ''
    ) {
//display
        $error = "Please fill in the required fields.";

//validate the tree status
//! this means not
    } elseif (
        !in_array(
            $status,
            ['Healthy', 'Growing', 'Monitoring'],
            true
        )
    ) {

        $error = "Invalid tree status.";

    } else {
//prepares the SQL to insert
//? are placeholders for those variables
//null if means if there is empty string in the input it was considered as null
//since the info was to be followed ? purpose serves as the placeholders of the variable
        $stmt = $conn->prepare("
            INSERT INTO trees (
                tree_code,
                common_name,
                scientific_name,
                family,
                age,
                height,
                trunk_diameter,
                status,
                barangay,
                municipality,
                specific_location,
                latitude,
                longitude,
                owner_name,
                owner_contact,
                owner_address,
                ownership_type,
                description,
                uses,
                care_notes,
                info_url,
                date_registered
            )
            VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, NULLIF(?, ''), NULLIF(?, ''),
                ?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?, '')
            )
        ");

//check if the SQL preparation work
        if (!$stmt) {

            $error = "Database query failed: " . $conn->error;

        } else {
// connect variables to the placeholders, result for display
            $stmt->bind_param(
                "ssssssssssssssssssssss",
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
                $date_registered
            );
//execute the insert/result
            if ($stmt->execute()) {
//if the input successful sent back to admin.php, prapare the sql, bind values, execute, successful added, yes, admin.php
                header("Location: admin.php");
                exit;

            } else {
//handle the duplicate tree code
                if ($stmt->errno === 1062) {
//display
                    $error =
                        "Tree code already exists. Please use another code.";

                } else {

                    $error =
                        "Failed to add tree: " .
                        $stmt->error;

                }

            }
//close prepared statement
            $stmt->close();
        }
    }
}
//load the header, start session, is admin log in?
//no > login.php = stop
//yes > connect data base
//was form submitted?
//no > show page/form
//yes > get form data > required feilds complete?
//no > error    
//yes > valid status?
//no > error
//yes > biind parameters, execute
//success > admin.php
//error > show error display

require_once "includes/header.php";

?>

<main class="container">

    <section class="section">

        <div class="section-head">

            <div>

                <p>ADMINISTRATION</p>

                <h2>
                    Add Tree
                </h2>

                <p>
                    Add a new native tree record.
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
                action="add-tree.php"
            >

                <div class="form-group">

                    <label for="tree_code">
                        Tree Code *
                    </label>

                    <input
                        type="text"
                        id="tree_code"
                        name="tree_code"
                        placeholder="GLE-NAR-001"
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
                        placeholder="Narra"
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
                        placeholder="Pterocarpus indicus"
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
                        placeholder="Fabaceae"
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
                        placeholder="10 years"
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
                        placeholder="12 meters"
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
                        placeholder="45 cm"
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

                        <option value="Healthy">
                            Healthy
                        </option>

                        <option value="Growing">
                            Growing
                        </option>

                        <option value="Monitoring">
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
                        placeholder="13.5000000"
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
                        placeholder="123.0000000"
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
                        placeholder="Private / Farm / Community"
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
                    ></textarea>

                </div>


                <div class="form-group">

                    <label for="uses">
                        Uses
                    </label>

                    <textarea
                        id="uses"
                        name="uses"
                        rows="4"
                    ></textarea>

                </div>


                <div class="form-group">

                    <label for="care_notes">
                        Care Notes
                    </label>

                    <textarea
                        id="care_notes"
                        name="care_notes"
                        rows="4"
                    ></textarea>

                </div>


                <div class="form-group">

                    <label for="info_url">
                        Information URL
                    </label>

                    <input
                        type="url"
                        id="info_url"
                        name="info_url"
                        placeholder="https://example.com"
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
                    >

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Add Tree
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