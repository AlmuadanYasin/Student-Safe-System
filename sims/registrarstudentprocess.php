<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sims";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sub_code"])) {
    // Validate and sanitize the form data
    $sub_code = isset($_POST['sub_code']) ? $conn->real_escape_string($_POST['sub_code']) : '';
    $sub_name = isset($_POST['sub_name']) ? $conn->real_escape_string($_POST['sub_name']) : '';
    $sub_sched = isset($_POST['sub_sched']) ? $conn->real_escape_string($_POST['sub_sched']) : '';
    $sub_instructor = isset($_POST['sub_instructor']) ? $conn->real_escape_string($_POST['sub_instructor']) : '';
    $grades = isset($_POST['grades']) ? $conn->real_escape_string($_POST['grades']) : '';


    // Insert data into subjectsched table
    $insertSubjectSchedQuery = "INSERT INTO subjects (sub_code, sub_name, sub_sched, sub_instructor) 
                                VALUES ('$sub_code', '$sub_name', '$sub_sched', '$sub_instructor')";

    if ($conn->query($insertSubjectSchedQuery) === TRUE) {
        echo "Subject added successfully to subjectsched table";
    } else {
        echo "Error adding subject to subjectsched table: " . $conn->error;
    }

    // Insert data into studentgrades table
   // Insert data into the 'studentgrades' table, including the 'student_id'
   $insert_sql = "INSERT INTO grades (student_id, sub_code, sub_name, sub_sched, sub_instructor, grades) 
   VALUES ($user_id, '$sub_code', '$sub_name', '$sub_sched', '$sub_instructor', '$grades')";


    if ($conn->query($insertStudentGradesQuery) === TRUE) {
        echo "Subject added successfully to studentgrades table";
    } else {
        echo "Error adding subject to studentgrades table: " . $conn->error;
    }
} else {
    // If the request method is not POST, redirect or handle accordingly
    echo "Invalid request method";
}

// Close the database connection
$conn->close();
?>
