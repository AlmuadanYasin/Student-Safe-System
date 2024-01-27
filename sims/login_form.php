<?php
session_start();
@include 'config.php';

$error = array();

if (isset($_POST['submit'])){
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
    } else {
        $error[] = 'Email is missing.';
    }

    if (isset($_POST['password'])) {
        $password = md5($_POST['password']);
    } else {
        $error[] = 'Password is missing.';
    }

    // Check the corresponding table based on email
    $select = "SELECT * FROM student_table WHERE email = '$email' AND password = '$password'";
    $result_student = mysqli_query($conn, $select);

    $select = "SELECT * FROM cashier_table WHERE email = '$email' AND password = '$password'";
    $result_cashier = mysqli_query($conn, $select);

    $select = "SELECT * FROM registrar_table WHERE email = '$email' AND password = '$password'";
    $result_registrar = mysqli_query($conn, $select);

    if (mysqli_num_rows($result_student) > 0) {
        $row = mysqli_fetch_array($result_student);
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['user_name'] = $row['f_name'];
        $_SESSION['user_type'] = $row['user_type'];

        // Store the first letter of the name in a session variable
        $_SESSION['user_initial'] = substr($row['f_name'], 0, 1);
        header('location: user_page.php');
    } elseif (mysqli_num_rows($result_cashier) > 0) {
        $row = mysqli_fetch_array($result_cashier);
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['user_name'] = $row['f_name'];
        $_SESSION['user_type'] = $row['user_type'];

        // Store the first letter of the name in a session variable
        $_SESSION['user_initial'] = substr($row['f_name'], 0, 1);
        header('location: cashier_page.php');
    } elseif (mysqli_num_rows($result_registrar) > 0) {
        $row = mysqli_fetch_array($result_registrar);
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['user_name'] = $row['f_name'];
        $_SESSION['user_type'] = $row['user_type'];

        // Store the first letter of the name in a session variable
        $_SESSION['user_initial'] = substr($row['f_name'], 0, 1);
        header('location: registrar_page.php');
    } else {
        $error[] = 'Incorrect Email or Password!';
    }
};
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>LogIn Form</title>
    <!-- custom css file link -->
    <link rel="stylesheet" type="text/css" href="style.css">
    <style>
        /* Additional CSS for the image and form layout */
        .main-container {
            display: flex;
            align-items: center;
            justify-content: space-around; /* Adjust as needed */
            height: 100vh;
            background: #eee;
        }

        .img-container {
            flex: 1;
            text-align: center;
        }

        .img-container img {
            max-width: 100%;
            height: auto;
        }

        .form-container {
            flex: 1;
            padding: 20px;
            /* background: #eee; */
        }

        .form-container form {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="main-container">
    <div class="img-container">
        <img src="images/logo.png" alt="logo">
    </div>
    <div class="form-container">
        <form action="login_form.php" method="post">
            <h3>LogIn Now</h3>
            <?php
            if (!empty($error)) {
                foreach ($error as $error) {
                    echo '<span class="error-msg">' . $error . '</span>';
                }
            }
            ?>
            <input type="email" name="email" required placeholder="Enter your Email">
            <input type="password" name="password" required placeholder="Enter your Password">
            <input type="submit" name="submit" value="LogIn Now" class="form-btn">
            <p>Don't have an Account? <a href="register_form.php">Register Now</a> </p>
        </form>
    </div>
</div>
</body>
</html>