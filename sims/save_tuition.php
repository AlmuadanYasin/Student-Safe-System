<?php

// Include your database connection configuration
@include 'config.php';

// Check if the form data is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the student ID and tuition amount from the POST data
    $studentId = $_POST["studentId"];
    $tuitionAmount = $_POST["tuitionAmount"];

    // Validate the data if needed

    // Update the 'student_table' with the new tuition amount
    $updateSql = "UPDATE student_table SET tuition = '$tuitionAmount' WHERE student_id = '$studentId'";

    if ($conn->query($updateSql) === TRUE) {
        echo "Tuition updated successfully";
    } else {
        echo "Error updating tuition: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Handle the case where the request method is not POST
    echo "Invalid request method";
}
?>
