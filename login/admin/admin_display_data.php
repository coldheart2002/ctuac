<?php
session_start(); // Start the session

// Check if the user is authenticated
if (!isset($_SESSION["username"])) {
    header("Location: login.php"); // Redirect to login page
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Display Data</title>
    <link rel="stylesheet" type="text/css" href="css/display_data.css">
    <script src="js/display_data.js" defer></script>
</head>

<body>
    
    <div class="header">
        <h2>Displaying Data from Database</h2>
        <button id="back">Back</button>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('back');
            button.addEventListener('click', () => {
                window.location.href = '../login.html';
            });
        });
    </script>
    <!-- Add search input and button -->
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search for Name or ID Number...">
        <button class="searchbutton" onclick="searchTable()">Search</button>
    </div>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "NINOmharlito2002";
    $dbname = "testdb";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Initialize offset
    $offset = 0;

    // Pagination Logic
    $page = isset($_GET['page']) ? $_GET['page'] : '1'; // Use '1' as default
    $limitPerPage = 50; // Number of pages to display per set

    // Calculate the offset
    $offset = ($page === 'all') ? 0 : ($page - 1) * $limitPerPage;

    // Calculate the total number of pages
    $totalPagesQuery = "SELECT COUNT(*) as total FROM userdata";
    $totalPagesResult = $conn->query($totalPagesQuery);
    $totalPagesRow = $totalPagesResult->fetch_assoc();
    $totalPages = ceil($totalPagesRow['total'] / $limitPerPage);

    // Modify the SELECT query to include LIMIT and OFFSET
    if ($page === 'all') {
        $select_query = "SELECT * FROM userdata";
    } else {
        $select_query = "SELECT * FROM userdata LIMIT $limitPerPage OFFSET $offset";
    }

    // Add a placeholder for the search query
    $searchQuery = '';

    // Check if a search query is provided in the URL
    if (isset($_GET['search'])) {
        $searchQuery = $conn->real_escape_string($_GET['search']);
        $select_query = "SELECT * FROM userdata WHERE name LIKE '%$searchQuery%' OR id LIKE '%$searchQuery%' LIMIT $limitPerPage OFFSET $offset";
    }

    $result = $conn->query($select_query);
    
    // Pagination Links
    echo '<div class="pagination">';

    // Display the "Show All" link with a confirmation pop-up
    echo '<a href="?page=all" onclick="return confirm(\'Are you sure you want to show all data?\nToo much data may cause an error!\')">Show All</a>';

    // Display the page numbers
    $startPage = max(1, intval($page) - 5); // Cast $page to integer
    $endPage = min($totalPages, $startPage + 9);

    for ($i = $startPage; $i <= $endPage; $i++) {
        // Highlight the current page
        $isActive = ($i == intval($page)) ? ' active' : ''; // Cast $page to integer
        echo '<a href="?page=' . $i . '" class="' . $isActive . '">' . $i . '</a>';
    }

    // Add the "Last Page" button
    echo '<a href="?page=' . $totalPages . '">Last Page</a>';

    echo '</div>';

    if ($result->num_rows > 0) {
        
        echo '<table>';
        echo '<tr>
<th>Total</th>
<th>Priority #</th>
<th>Name</th>
<th>ID</th>
<th>Contact</th>
<th>Guardian</th>
<th>Course</th>
<th>Blood Type</th>
<th>Birthday</th>
<th>Fill Up Date</th>
<th>ID Status</th>
</tr>';

        $counter = 1; // Start the counter from 1 on each page

        while ($row = $result->fetch_assoc()) {
            $statusClass = '';
            switch ($row['id_status']) {
                case 'done':
                    $statusClass = 'status-done';
                    break;
                case 'released':
                    $statusClass = 'status-released';
                    break;
                case 'unclaimed':
                    $statusClass = 'status-unclaimed';
                    break;
                default:
                    $statusClass = 'status-pending';
            }

            echo '<tr class="' . $statusClass . '">';
            echo '<td>' . $row['number'] . '</td>';
            echo '<td>' . $counter . '</td>';
            echo '<td>' . strtoupper($row['name']) . '</td>';
            echo '<td>' . $row['id'] . '</td>';
            echo '<td>' . $row['contact'] . '</td>';
            echo '<td>' . strtoupper($row['gname']) . '</td>';
            echo '<td>' . $row['course'] . '</td>';
            echo '<td>' . $row['blood'] . '</td>';
            echo '<td>' . $row['bday'] . '</td>';
            echo '<td>' . $row['datetime'] . '</td>';
            echo '<td class="editable" data-id="' . $row['id'] . '" data-field="id_status">';
            echo '<select onchange="updateStatus(this)">';
            echo '<option value="pending" ' . ($row['id_status'] === 'pending' ? 'selected' : '') . '>Pending</option>';
            echo '<option value="done" ' . ($row['id_status'] === 'done' ? 'selected' : '') . '>Done</option>';
            echo '<option value="released" ' . ($row['id_status'] === 'released' ? 'selected' : '') . '>Released</option>';
            echo '<option value="unclaimed" ' . ($row['id_status'] === 'unclaimed' ? 'selected' : '') . '>Unclaimed</option>';
            echo '</select>';
            echo '</td>';
            echo '</tr>';

            $counter++; // Increment counter for the next row
        }

        echo '</table>';
    } else {
        echo 'No data available.';
    }

    // Pagination Links
    echo '<div class="pagination">';

    // Display the "Show All" link with a confirmation pop-up
    echo '<a href="?page=all" onclick="return confirm(\'Are you sure you want to show all data?\nToo much data may cause an error!\')">Show All</a>';

    // Display the page numbers
    $startPage = max(1, intval($page) - 5); // Cast $page to integer
    $endPage = min($totalPages, $startPage + 9);

    for ($i = $startPage; $i <= $endPage; $i++) {
        // Highlight the current page
        $isActive = ($i == intval($page)) ? ' active' : ''; // Cast $page to integer
        echo '<a href="?page=' . $i . '" class="' . $isActive . '">' . $i . '</a>';
    }

    // Add the "Last Page" button
    echo '<a href="?page=' . $totalPages . '">Last Page</a>';

    echo '</div>';


    // Close connection
    $conn->close();
    ?>

    <script>
        function searchTable() {
            var searchInput = document.getElementById("searchInput").value;
            window.location.href = "?search=" + encodeURIComponent(searchInput);
        }

        function updateStatus(select) {
            var id = select.parentElement.getAttribute("data-id");
            var field = select.parentElement.getAttribute("data-field");
            var status = select.value;
        }
    </script>
</body>

</html>