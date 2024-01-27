<?php
// Include para mauban sa code
@include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["studentId"]) && isset($_POST["tuitionAmount"])) {
    // Kuhaon ang student ID ug tuition
    $studentId = $_POST["studentId"];
    $tuitionAmount = $_POST["tuitionAmount"];

    // update or edit sa table
    $updateSql = "UPDATE student_table SET tuition = ? WHERE student_id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("di", $tuitionAmount, $studentId);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error_message = 'Error updating tuition: ' . $stmt->error;
    }

    // Close the statement
    $stmt->close();

    // magsend JSON response or javascript
    header('Content-Type: application/json');
    echo json_encode(['success' => $success, 'message' => $error_message ?? '']);
    exit(); // Ensure that no further HTML is output
}

// Kuha data sa database
$sql = "SELECT * FROM student_table";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cashier Page</title>
    <!-- Stylesheet links moved to the head -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <!-- Custom CSS file link -->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <?php include "header.php"; ?>
    <!-- TABLE CONTENT -->
    <p class="fs-1 ps-sm-5 font-monospace fw-bold">LIST OF STUDENTS</p>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb mx-5 font-monospace">
            <li class="breadcrumb-item">Home</li>
        </ol>
    </nav>
    <div class="container-xl border-3 border rounded-3">
        <table id="studentsTable" class="mt-2 table table-hover">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Tuition</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody class="auto">
                <?php
                // Loop through the 'student' table results and generate table rows
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                            <th scope='row'>" . $row["student_id"] . "</th>
                            <td>" . $row["f_name"] . '  ' . $row["m_name"] . '  ' . $row["l_name"] . "</td>
                            <td>" . $row["email"] . "</td>
                            <td>" . number_format($row["tuition"]) . "</td>
                            <td><button type='button' class='btn btn-warning' onclick='editTuition(\"{$row["student_id"]}\", \"{$row["tuition"]}\")'>Edit</button>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for tuition edit -->
    <div class="modal fade" id="tuitionModal" tabindex="-1" aria-labelledby="tuitionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tuitionModalLabel">Edit Tuition</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Add your form elements for editing tuition here -->
                    <input type="hidden" id="tuitionStudentId" name="tuitionStudentId">
                    <!-- Add this line -->
                    <label for="tuitionAmount">Tuition Amount:</label>
                    <input type="text" class="form-control" id="tuitionAmount" name="tuitionAmount">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="saveTuition()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
        function editTuition(studentId, tuition) {
            // Set the values in the edit modal
            document.getElementById('tuitionStudentId').value = studentId;
            document.getElementById('tuitionAmount').value = tuition;

            // Show the edit modal
            $('#tuitionModal').modal('show');
        }

        function saveTuition() {
            const student_Id = document.getElementById('tuitionStudentId').value;
            const tuitionAmount = document.getElementById('tuitionAmount').value;

            // Use AJAX to send form data to the server
            $.ajax({
                type: 'POST',
                url: 'cashier_page.php',
                data: {
                    studentId: student_Id,
                    tuitionAmount: tuitionAmount
                },
                dataType: 'json', // Specify that you expect a JSON response
                success: function(response) {
                    console.log(response);

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Tuition edited successfully',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            $('#tuitionModal').modal('hide');
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message || 'An error occurred while editing tuition',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        });
                    }
                },
                error: function(xhr, status, error) {
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
</body>

</html>
<?php
// Close the database connection
$conn->close();
?>