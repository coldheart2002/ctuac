<!DOCTYPE html>
<html>

<head>
    <title>Result</title>
    <link rel="stylesheet" type="text/css" href="../css/styles.css">
    <style>
        @keyframes blink {

            10%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .message-blink {
            animation: blink 3s infinite;
        }
    </style>
    <script src="../js/script.js" defer></script>
</head>

<body>

    <div class="process">
        <div class="logo_container">
            <img class="logo" src="../images/ctulogo.png" alt="CTU Logo">
        </div>
        <?php
        session_start(); // Start the session

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $firstName = $_POST["first_name"];
            $middleInitial = $_POST["middle_initial"];
            $lastName = $_POST["last_name"];
            $id = $_POST["id"];
            $contact = $_POST["contact"];
            $gfirstName = $_POST["gfirst_name"];
            $gmiddleInitial = $_POST["gmiddle_initial"];
            $glastName = $_POST["glast_name"];
            $course = $_POST["course"];
            $blood = $_POST["blood"];
            $bday = $_POST["bday"];

            // Combine first name, middle initial, and last name into one name field
            $name = $firstName . " " . $middleInitial . " " . $lastName;
            $gname = $gfirstName . " " . $gmiddleInitial . " " . $glastName;

            // Rest of your code remains the same



            $servername = "localhost";
            $username = "root";
            $password = "NINOmharlito2002";
            $dbname = "testdb";

            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }


            
            // Check if the ID already exists in the database
            $check_query = "SELECT * FROM userdata WHERE id = '$id'";
            $check_result = $conn->query($check_query);

            // Count the number of entries for today
            $today_entries_query = "SELECT COUNT(*) as entry_count FROM userdata WHERE DATE(datetime) = CURDATE()";
            $today_entries_result = $conn->query($today_entries_query);
            $today_entries = $today_entries_result->fetch_assoc()["entry_count"];

// Calculate the priority number for the current day
$priority_number = $today_entries + 1;

// Check if it's a new day and reset the priority number to 1
$last_entry_query = "SELECT MAX(datetime) AS last_entry_datetime FROM userdata";
$last_entry_result = $conn->query($last_entry_query);
$last_entry_row = $last_entry_result->fetch_assoc();
$last_entry_datetime = $last_entry_row["last_entry_datetime"];

if (date("Y-m-d", strtotime($last_entry_datetime)) !== date("Y-m-d")) {
    $priority_number = 1; // It's a new day, reset priority number to 1
}

            if ($check_result->num_rows > 0) {
                echo '<div class="message">ID Number <br> Already Exists</div>';
            } elseif ($today_entries >= 50) {
                echo '<div class="message">Today\'s limit has been reached</div>';
            } else {
                // Calculate the page and slot
                $total_entries_today = $today_entries + 1;
                $page_number = ord('a') + (int)(($total_entries_today - 1) / 50);
                $slot_number = ($total_entries_today - 1) % 50 + 1;

                // Calculate the remaining slots
                $remaining_slots = 50 - $total_entries_today;


                // Insert data into the database
                $insert_query = "INSERT INTO userdata (name, id, contact, gname, course, blood, bday, datetime, priority_number) VALUES ('$name', '$id', '$contact', '$gname', '$course', '$blood', '$bday', NOW(), '$priority_number')";

                if ($conn->query($insert_query) === TRUE) {
                    // Get the current page based on the last inserted ID
                    $lastInsertedId = $conn->insert_id;
                    $currentPage = chr(ord('A') + intval(($lastInsertedId - 1) / 50));

                    // Calculate the entry count on the current page
                    $entryCount = ($lastInsertedId - 1) % 50 + 1;

                    echo '<div class="message">Data Inserted Successfully</div>';
                    echo '<div class="priority">Your Priority Number is: <br>';
                    echo '<span class="batch-number">' . $entryCount . '</span>';
                    if ($remaining_slots > 0) {
                        echo '<br>' . $remaining_slots . ' slots available';
                    } else {
                        echo '<br>No slots available today';
                    }
                    echo '</div>';

                    echo '<div class="message message-blink">Please Take Note or Screenshot <br> your Priority Number!</div>';
                } else {
                    echo "Error: " . $insert_query . "<br>" . $conn->error;
                }
            }

            $conn->close();
        }
        ?>

        <div class="return-container">
            <a href="../index.php" class="return">Return</a>
        </div>
    </div>

</body>

</html>