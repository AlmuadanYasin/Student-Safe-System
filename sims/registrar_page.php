<?php 

@include 'config.php';

// Delete or drop sa student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_sql = "DELETE FROM student_table WHERE student_id = $delete_id"; // Change "ID" to "student_id"
    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    exit; // Stop further execution
}

// Fetch data from the database
$sql = "SELECT * FROM student_table";
$result = $conn->query($sql);
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
	<title>Registrar Page</title>
	<!-- custom css file link --> 
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <!-- TABLE CONTENT -->
  <p class=" fs-1 ps-sm-5  font-monospace fw-bold ">LIST OF STUDENTS</p>
  <nav style="--bs-breaadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mx-5 font-monospace">
      <li class="breadcrumb-item">Home</li>
    </ol>
  </nav>
  <div class="container-xl border-3  border rounded-3  ">
  <table class="mt-2 table table-hover">
    <thead>
      <tr>
        <th scope="col">ID</th>
        <th scope="col">First name</th>
        <th scope="col">Last name</th>
        <th scope="col">Email</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <tbody class="auto" >
      <?php
      // Loop through the 'student' table results and generate table rows
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <th scope='row'>" . $row["student_id"] . "</th>
                      <td>" . $row["f_name"] . "</td>
                      <td>" . $row["l_name"] . "</td>
                      <td>" . $row["email"] . "</td>
                      <td>
                          <button type='button' class='btn btn-outline-primary rounded-pill' onclick='viewDetails(" . $row["student_id"] . ")'>Details</button>
                          <button type='button' class='btn btn-outline-danger rounded-pill' data-student-id='" . $row["student_id"] . "' onclick='confirmDelete(this)'>Delete</button>
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

<!-- Modal for delete confirmation -->
<div class="modal fade" id="deleteConfirmationModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this student?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
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
<script src="script.js"></script>
    <script>
        function confirmDelete(button) {
            var studentId = $(button).data('student-id');
            $('#deleteConfirmationModal').modal('show');

            // Unbind previous click event to avoid multiple bindings
            $('#confirmDeleteBtn').off('click').on('click', function() {
                deleteStudent(studentId);
                $('#deleteConfirmationModal').modal('hide');
            });
        }

        function deleteStudent(studentId) {
            $.ajax({
                type: "POST",
                url: window.location.href,
                data: { delete_id: studentId },
                success: function(response) {
                    console.log(response);
                    // Refresh the page or update the table using DataTables API
                    // For simplicity, you can reload the page after deletion
                    location.reload();
                },
                error: function(error) {
                    console.error(error);
                }
            });
        }
        function viewDetails(studentId) {
            // Redirect to registrarstudent_detail.php with the student ID as a parameter
            window.location.href = 'registrarstudent_detail.php?student_id=' + studentId;
        }

</script>
</body>
</html>
<?php
// Close the database connection
$conn->close();
?>