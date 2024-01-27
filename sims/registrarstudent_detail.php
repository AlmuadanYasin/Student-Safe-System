<?php

@include 'config.php';

if (isset($_GET['student_id'])) {
    $studentId = $_GET['student_id'];

    $studentQuery = "SELECT * FROM student_table WHERE student_id = $studentId";
    $studentResult = $conn->query($studentQuery);

    $subjectsQuery = "SELECT sub_code, sub_name, sub_sched, sub_instructor, grades
                    FROM subjects
                    WHERE student_id = $studentId";

    $subjectsResult = $conn->query($subjectsQuery);

    if ($studentResult) {
        $studentData = $studentResult->fetch_assoc();

    }
}



if (isset($_POST['saveEditedPersonalInfo'])) {
    $editFirstName = $_POST['editFirstName'];
    $editMiddleName = $_POST['editMiddleName'];
    $editLastName = $_POST['editLastName'];


    // Update the personal information in the database
    $updatePersonalInfoQuery = "UPDATE student_table
                            SET f_name = '$editFirstName',
                                m_name = '$editMiddleName',
                                l_name = '$editLastName'
                            WHERE student_id = $studentId";


    $conn->query($updatePersonalInfoQuery);

    // Respond with a JSON object indicating success
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}


if (isset($_POST['deleteSubject'])) {
    $subjectCodeToDelete = $_POST['subjectCode'];

    // Perform the deletion in the database
    $deleteSubjectQuery = "DELETE FROM subjects WHERE student_id = $studentId AND sub_code = '$subjectCodeToDelete'";
    $conn->query($deleteSubjectQuery);

    // Respond with a JSON object indicating success
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}
if (isset($_POST['saveEditedSubject'])) {
    $editSubjectCode = $_POST['editSubjectCode'];
    $editSubjectName = $_POST['editSubjectName'];
    $editSchedule = $_POST['editSchedule'];
    $editInstructor = $_POST['editInstructor'];
    $editGrades = $_POST['editGrades'];

    // Update the subject information in the database
    $updateSubjectQuery = "UPDATE subjects
                           SET sub_name = '$editSubjectName',
                               sub_sched = '$editSchedule',
                               sub_instructor = '$editInstructor',
                               grades = '$editGrades'
                           WHERE student_id = $studentId AND sub_code = '$editSubjectCode'";

    $conn->query($updateSubjectQuery);

    // Respond with a JSON object indicating success
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addSubject'])) {
        $subjectCode = $_POST['subjectCode'];
        $subjectName = $_POST['subjectName'];
        $schedule = $_POST['schedule'];
        $instructor = $_POST['instructor'];
        $grades = $_POST['grades'];

        // Check if the subject with the same code already exists
        $checkDuplicateQuery = "SELECT COUNT(*) as count FROM subjects WHERE student_id = $studentId AND sub_code = '$subjectCode'";
        $duplicateResult = $conn->query($checkDuplicateQuery);
        $duplicateData = $duplicateResult->fetch_assoc();

        if ($duplicateData['count'] > 0) {
            // Subject with the same code already exists, return an error response
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Subject with the same code already exists']);
            exit();
        }

        // Add the subject to the database
        $insertSubjectQuery = "INSERT INTO subjects (student_id, sub_code, sub_name, sub_sched, sub_instructor, grades) 
                              VALUES ($studentId, '$subjectCode', '$subjectName', '$schedule', '$instructor', '$grades')";
        $conn->query($insertSubjectQuery);

        // Respond with a JSON object indicating success
        header('Content-Type: application/json');
        echo json_encode(['success' => true]);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <title>Registrar Page</title>
    <link rel="stylesheet" type="text/css" href="header.php">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
<?php
@include 'header.php';
?>
<p class="fs-1 ps-sm-5 font-monospace fw-bold">LIST OF STUDENTS</p>
<nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mx-5 font-monospace">
        <li class="breadcrumb-item"><a href="registrar_page.php">Home</a></li>
        <li class="breadcrumb-item">Details</li>
    </ol>
</nav>

<div class="container border-3 border rounded-3">
    <div class="row container-xxl my-1 position-absolute top-50">
        <div class="col-sm-2 mb-3 mb-sm-0">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Personal Information</h5>
                    <p class="card-text fw-semibold">Name:<br> <?php echo $studentData['f_name'] . ' ' . $studentData['m_name']. ' ' . $studentData['l_name']; ?></p>
                    <p class="card-text fw-semibold">Email:<br> <?php echo $studentData['email']; ?></p>
                    <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#editPersonalInfoModal">Edit</button>
                </div>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Subject Schedule and Grade</h5>
                    <button type="button" class="btn btn-success position-absolute top-0 end-0 m-3" data-bs-toggle="modal" data-bs-target="#addModal">Add</button>
                    <table class="mt-2 table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Subject Code</th>
                                <th scope="col">Subject Name</th>
                                <th scope="col">Schedule</th>
                                <th scope="col">Instructor</th>
                                <th scope="col">Grades</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="subjectTableBody" class="auto">
                            <?php 
                                while ($subjectData = $subjectsResult->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$subjectData['sub_code']}</td>";
                                    echo "<td>{$subjectData['sub_name']}</td>";
                                    echo "<td>{$subjectData['sub_sched']}</td>";
                                    echo "<td>{$subjectData['sub_instructor']}</td>";
                                    echo "<td>{$subjectData['grades']}</td>";
                                    echo "<td>  <button type='button' class='btn btn-warning' onclick=\"editSubject('{$subjectData['sub_code']}', '{$subjectData['sub_name']}', '{$subjectData['sub_sched']}', '{$subjectData['sub_instructor']}', '{$subjectData['grades']}')\">Edit</button>
                                                <button type='button' class='btn btn-danger' onclick=\"deleteSubject('{$subjectData['sub_code']}')\">Delete</button></td>";
                                    echo "</tr>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- EDIT PERSONAL INFORMATION MODAL -->
<div class="modal fade" id="editPersonalInfoModal" tabindex="-1" aria-labelledby="editPersonalInfoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPersonalInfoModalLabel">Edit Personal Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editPersonalInfoForm">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="editFirstName" value="<?php echo $studentData['f_name']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="editMiddleName" class="form-label">Middle Name</label>
                        <input type="text" class="form-control" id="editMiddleName" name="editMiddleName" value="<?php echo $studentData['m_name']; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="editLastName" value="<?php echo $studentData['l_name']; ?>" required>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveEditedPersonalInfo()">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!--ADD SUBJECT MODAL-->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Subject, Grade, and Schedule</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="subjectForm">
                    <div class="mb-3">
                        <label for="subjectCode" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="subjectCode" name="subjectCode" required>
                    </div>
                    <div class="mb-3">
                        <label for="subjectName" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="subjectName" name="subjectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="schedule" class="form-label">Schedule</label>
                        <input type="text" class="form-control" id="schedule" name="schedule" required>
                    </div>
                    <div class="mb-3">
                        <label for="instructor" class="form-label">Instructor</label>
                        <input type="text" class="form-control" id="instructor" name="instructor" required>
                    </div>
                    <div class="mb-3">
                        <label for="grades" class="form-label">Grades (optional)</label>
                        <input type="text" class="form-control" id="grades" name="grades">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="addSubject()">Add Subject</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--EDIT SUBJECT MODAL-->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Subject Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editSubjectForm">
                    <div class="mb-3">
                        <label for="editSubjectCode" class="form-label">Subject Code</label>
                        <input type="text" class="form-control" id="editSubjectCode" name="editSubjectCode" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="editSubjectName" class="form-label">Subject Name</label>
                        <input type="text" class="form-control" id="editSubjectName" name="editSubjectName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editSchedule" class="form-label">Schedule</label>
                        <input type="text" class="form-control" id="editSchedule" name="editSchedule" required>
                    </div>
                    <div class="mb-3">
                        <label for="editInstructor" class="form-label">Instructor</label>
                        <input type="text" class="form-control" id="editInstructor" name="editInstructor" required>
                    </div>
                    <div class="mb-3">
                        <label for="editGrades" class="form-label">Grades</label>
                        <input type="text" class="form-control" id="editGrades" name="editGrades">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="saveEditedSubject()">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
    function editPersonalInfo(firstName, middleName, lastName, email) {
        // Set the values in the edit modal
        document.getElementById('editFirstName').value = firstName;
        document.getElementById('editMiddleName').value = middleName;
        document.getElementById('editLastName').value = lastName;



        // Show the edit modal
        $('#editPersonalInfoModal').modal('show');
    }

    function saveEditedPersonalInfo() {
        const editFirstName = document.getElementById('editFirstName').value;
        const editMiddleName = document.getElementById('editMiddleName').value;
        const editLastName = document.getElementById('editLastName').value;



        // Use AJAX to send form data to the server
        $.ajax({
            type: 'POST',
            url: 'registrarstudent_detail.php?student_id=<?php echo $studentId; ?>',
            data: {
                saveEditedPersonalInfo: true,
                editFirstName: editFirstName,
                editMiddleName: editMiddleName,
                editLastName: editLastName,


            },
            success: function(response) {
                console.log(response); // Log the response for debugging

                if (response.success) {
                    // Use SweetAlert for success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Personal information edited successfully',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#editPersonalInfoModal').modal('hide');  // Close the edit modal
                        location.reload();  // Reload the page or update the personal information
                    });
                } else {
                    // Use SweetAlert for error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'An error occurred while editing personal information',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Use SweetAlert for generic error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                console.error(error);
            }
        });
    }
</script>

<script>
    function addSubject() {
        const subjectCode = document.getElementById('subjectCode').value;
        const subjectName = document.getElementById('subjectName').value;
        const schedule = document.getElementById('schedule').value;
        const instructor = document.getElementById('instructor').value;
        const grades = document.getElementById('grades').value;

        // Use AJAX to send form data to the server
        $.ajax({
            type: 'POST',
            url: 'registrarstudent_detail.php?student_id=<?php echo $studentId; ?>',
            data: {
                addSubject: true,
                subjectCode: subjectCode,
                subjectName: subjectName,
                schedule: schedule,
                instructor: instructor,
                grades: grades
            },
            success: function(response) {
                console.log(response); // Log the response for debugging

                if (response.success) {

                    // Use SweetAlert for success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Subject added successfully',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    // Use SweetAlert for error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'An error occurred 2',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Use SweetAlert for generic error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                console.error(error);
            }
        });
    }
</script>
<script>
    function editSubject(code, name, schedule, instructor, grades) {
        // Set the values in the edit modal
        document.getElementById('editSubjectCode').value = code;
        document.getElementById('editSubjectName').value = name;
        document.getElementById('editSchedule').value = schedule;
        document.getElementById('editInstructor').value = instructor;
        document.getElementById('editGrades').value = grades;

        // Show the edit modal
        $('#editModal').modal('show');
    }
    function saveEditedSubject() {
        const editSubjectCode = document.getElementById('editSubjectCode').value;
        const editSubjectName = document.getElementById('editSubjectName').value;
        const editSchedule = document.getElementById('editSchedule').value;
        const editInstructor = document.getElementById('editInstructor').value;
        const editGrades = document.getElementById('editGrades').value;


        // Use AJAX to send form data to the server
        $.ajax({
            type: 'POST',
            url: 'registrarstudent_detail.php?student_id=<?php echo $studentId; ?>',
            data: {
                saveEditedSubject: true,
                editSubjectCode: editSubjectCode,
                editSubjectName: editSubjectName,
                editSchedule: editSchedule,
                editInstructor: editInstructor,
                editGrades: editGrades
            },
            success: function(response) {
                console.log(response); // Log the response for debugging

                if (response.success) {
                    // Use SweetAlert for success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Subject edited successfully',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#editModal').modal('hide');  // Close the edit modal
                        location.reload();  // Reload the page or update the table
                    });
                } else {
                    // Use SweetAlert for error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'An error occurred while editing',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                // Use SweetAlert for generic error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An error occurred',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
                console.error(error);
            }
        });
    }
</script>
                                    <script>
                                        function deleteSubject(subjectCode) {
                                            // Use AJAX to send the subject code to the server for deletion
                                            $.ajax({
                                                type: 'POST',
                                                url: 'registrarstudent_detail.php?student_id=<?php echo $studentId; ?>',
                                                data: {
                                                    deleteSubject: true,
                                                    subjectCode: subjectCode
                                                },
                                                success: function(response) {
                                                    console.log(response); // Log the response for debugging

                                                    if (response.success) {
                                                        // Use SweetAlert for success message
                                                        Swal.fire({
                                                            icon: 'success',
                                                            title: 'Success',
                                                            text: 'Subject deleted successfully',

                                                        }).then(() => {
                                                            location.reload();
                                                        });
                                                    } else {
                                                        // Use SweetAlert for error message
                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: response.message || 'An error occurred while deleting',

                                                        });
                                                    }
                                                },
                                                error: function(xhr, status, error) {
                                                    // Use SweetAlert for generic error message
                                                    Swal.fire({
                                                        icon: 'error',
                                                        title: 'Error',
                                                        text: 'An error occurred',

                                                    });
                                                    console.error(error);
                                                }
                                            });
                                        }

                                    </script>
</body>
</html>
<?php
$conn->close();
?>