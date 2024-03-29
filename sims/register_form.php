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

    <style>

.form-container form .google-button {
            width: 100%;
	        padding: 10px 15px;
	        font-size: 17px;
            margin: 8px 0;
            margin: 8px 0;
	        border-radius: 5px;
            border: 2px solid crimson;
            background: white;
	        color: crimson;
	        text-transform: capitalize;
	        font-size: 20px;
	        cursor: pointer;
        }

        .form-container form .google-button:hover {
	        background: crimson;
	        color: #fff;
        }


    </style>
</head>
<body>
	<div class="form-container">
		<form action="register_form.php" method="POST">
			<h3>Register With Email</h3>
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
            <button type="submit" name="button" class="google-button" id="buttongoogle"><img src="images/google.png" alt="button">&nbsp; Register With Google+</button>
			<p>Already have an Account? <a href="login_form.php">Login Now</a> </p>
		</form>
	</div>

    <script type="module">
    
    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
    import { getAuth, GoogleAuthProvider, signInWithPopup} from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries
    
    // Your web app's Firebase configuration
    const firebaseConfig = {
      apiKey: "AIzaSyAygwEzzDOPE3u0QgYpQJzd2RnI6KsbGEk",
      authDomain: "studentsafe-f84e3.firebaseapp.com",
      projectId: "studentsafe-f84e3",
      storageBucket: "studentsafe-f84e3.appspot.com",
      messagingSenderId: "669624594218",
      appId: "1:669624594218:web:56b71d2cf93fe9349f7853"
    };
    
    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const auth = getAuth(app);
    auth.languageCode = 'en'
    
    const provider = new GoogleAuthProvider();
    
    const googleLogin = document.getElementById("buttongoogle");
    googleLogin.addEventListener("click", function(){
      signInWithPopup(auth, provider)
    .then((result) => {
      
      const credential = GoogleAuthProvider.credentialFromResult(result);
      const user = result.user;
      console.log(user);
      window.location.href = "google_form.php";
    
    }).catch((error) => {
      
      const errorCode = error.code;
      const errorMessage = error.message;
    
     
    });
    })
    </script>
</body>
</html>