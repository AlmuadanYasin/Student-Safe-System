<?php

@include 'config.php';
session_start();

if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
    $middlename = mysqli_real_escape_string($conn, $_POST['middlename']);
    $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = md5($_POST['password']);
    $cpass = md5($_POST['cpassword']);
    $user_type = $_POST['user_type'];

    $insert = ""; // Initialize $insert variable

    if ($pass != $cpass) {
        $error[] = 'Password don\'t Match!';
    } else {
        // Insert into the corresponding table based on user_type
        switch ($user_type) {
            case 'student':
                $insert = "INSERT INTO student_table(f_name, m_name, l_name, email, password, user_type) VALUES ('$firstname', '$middlename', '$lastname', '$email', '$pass', '$user_type')";
                break;
            case 'cashier':
                $insert = "INSERT INTO cashier_table(f_name, m_name, l_name, email, password, user_type) VALUES ('$firstname', '$middlename', '$lastname', '$email', '$pass', '$user_type')";
                break;
            case 'registrar':
                $insert = "INSERT INTO registrar_table(f_name, m_name, l_name, email, password, user_type) VALUES ('$firstname', '$middlename', '$lastname', '$email', '$pass', '$user_type')";
                break;
            default:
                // Handle unknown user_type or provide a default action
                echo "Debug: Unknown user_type - $user_type";
                break;
        }
    }

    // Check if $insert is set before executing the query
    if (!empty($insert)) {
        if (mysqli_query($conn, $insert)) {
			$_SESSION['user_type'] = $user_type; // Set user type in session
            header('location: login_form.php');
        } else {
            if (mysqli_errno($conn) == 1452) {
                // Foreign key violation
                echo "Error: Foreign key violation. Please check your data.";
            } else {
                // Other database error
                echo "Error: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Error: Insert query is empty!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register Form</title>
	<!-- custom css file link --> 
	<link rel="stylesheet" type="text/css" href="style.css">

</head>
<body>
	<div class="form-container">
		<form action="google_form.php" method="POST">
			<h3>Google Account Registration</h3>
			<?php
				if(isset($error)){
					foreach($error as $error){
						echo '<span class="error-msg">'.$error.'</span>';
					};
				};
			?>
			<select name="user_type">
				<option value="student">Student</option>
				<option value="cashier">Cashier</option>
				<option value="registrar">Registrar</option>
			</select>

			<input type="text" name="firstname" required placeholder="First Name *">
			<input type="text" name="middlename" required placeholder="Middle Name *">
			<input type="text" name="lastname" required placeholder="Last Name *">
			<input type="email" name="email" required placeholder="Enter your Email *">
			<input type="password" name="password" required placeholder="Enter your Password *">
			<input type="password" name="cpassword" required placeholder="Confirm your Password *">
			<input type="submit" name="submit" value="Register Now" class="form-btn">
			<p>Already have an Account? <a href="login_form.php">Login Now</a> </p>
		</form>
	</div>
</body>
</html>