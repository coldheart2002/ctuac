<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "NINOmharlito2002";
    $dbname = "testdb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $id = $_POST['id'];
    $field = $_POST['field'];
    $value = $_POST['value'];
    
    // Debugging
    error_log("Received data: id=$id, field=$field, value=$value");
      
    // Update the ID Status value in the database
    $update_query = "UPDATE userdata SET $field = '$value' WHERE id = $id";
    if ($conn->query($update_query) === TRUE) {
        echo "Success";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
}
?>
