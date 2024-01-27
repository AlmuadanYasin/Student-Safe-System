<?php
@include 'config.php';

// Check if the user is authenticated
session_start();
if (!isset($_SESSION['student_id'])) {
    // Redirect to the login page if not authenticated
    header("Location: login_form.php");
    exit();
}

$studentId = $_SESSION['student_id'];

// Fetch enrollment details for the authenticated student
$enrollmentQuery = "SELECT s.sub_code, s.sub_name, s.sub_sched, s.sub_instructor, s.grades
                    FROM subjects s
                    WHERE s.student_id = $studentId";

$enrollmentResult = $conn->query($enrollmentQuery);

if ($enrollmentResult) {
    // Fetch student data (assuming you have a table named student_table)
    $studentQuery = "SELECT * FROM student_table WHERE student_id = $studentId";
    $studentResult = $conn->query($studentQuery);

    if ($studentResult) {
        $studentData = $studentResult->fetch_assoc();
        $tuitionAmount = $studentData['tuition'];
    }
}
?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <?php @include 'header.php'; ?>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
        <title>Student Page</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    </head>
    <body>
    <div class="container border-3 border rounded-3 my-5">
        <div class="row container position-absolute top-0">
            <div class="col-sm-2 mb-3 mb-sm-0">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Personal Information</h5>
                        <p class="card-text fw-semibold">Name:<br> <?php echo $studentData['f_name'] . ' ' . $studentData['m_name']. ' ' . $studentData['l_name']; ?></p>
                        <p class="card-text fw-semibold">Email:<br> <?php echo $studentData['email']; ?></p>
                        <p class="card-text fw-semibold">Tuition:<br> <?php echo isset($tuitionAmount) ? number_format($tuitionAmount) : ''; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-10">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title fw-bold">Enrollment Details</h5>
                        <?php if ($enrollmentResult->num_rows > 0) { ?>
                            <table class="mt-2 table table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Subject Code</th>
                                    <th scope="col">Subject Name</th>
                                    <th scope="col">Schedule</th>
                                    <th scope="col">Instructor</th>
                                    <th scope="col">Grades</th>
                                </tr>
                                </thead>
                                <tbody id="subjectTableBody" class="auto">
                                <?php
                                while ($subjectData = $enrollmentResult->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$subjectData['sub_code']}</td>";
                                    echo "<td>{$subjectData['sub_name']}</td>";
                                    echo "<td>{$subjectData['sub_sched']}</td>";
                                    echo "<td>{$subjectData['sub_instructor']}</td>";
                                    echo "<td>{$subjectData['grades']}</td>";
                                    echo "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        <?php } else { ?>
                            <p>No enrollment details available.</p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
<?php $conn->close(); ?>