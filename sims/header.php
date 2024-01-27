<?php session_start(); @include 'config.php' ?>
<style>
	.header {
		background-color: lightgray;
		color: #fff; /* Set the text color */
		padding: 10px; /* Adjust padding as needed */
		text-align: start;
        display: flex;
        justify-content: space-between;
        align-items: center;
	}

	.header h1 {
		margin: 0; /* Remove default margin for h1 */
	}

	.logo{
		/* width: 10px; */
		height: 65px;
	}

    .user-profile {
        display: flex;
        align-items: center; /* Align items vertically in the center */
    }

    .user-profile .dp {
        background-color: orange;
        width: 40px;
        height: 40px;
        border-radius: 50%; 
        margin-right: 10px; /* Adjust margin between image and username */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
    }

    .user-profile .username {
        display: block; /* Show the username */
    }

    .user-profile .usertype {
        font-size: 14px; /* Adjust font size for user type */
        color: #888; /* Adjust color for user type */
    }
</style>

<header>

    <div class="header">
        <img src="images/logo.png" alt="logo" class="logo">
        <div class="user-profile">
            <div class="dp">
                <?php
                    if (isset($_SESSION['user_initial'])) {
                        echo $_SESSION['user_initial'];
                    } else {
                        // Redirect or handle the case where the user is not logged in
                    }
                ?>
            </div>
            <div class="user-details">
                <div class="username">
                    <h1 class=" fw-bold text-black  " >Hi, <?php
                    if (isset($_SESSION['user_name'])) {
                        echo $_SESSION['user_name'];
                    } else {
                        // Redirect or handle the case where the user is not logged in
                    }
                    ?>!</h1>
                </div>
                <div class="usertype">
				<h3 class=" text-black-50 " ><?php
                    if (isset($_SESSION['user_type'])) {
                        echo $_SESSION['user_type'];
                    } else {
                        // Redirect or handle the case where the user is not logged in
                    }
                    ?></h3>
                </div>
				
            </div><form class=" m-3 " action="logout.php" method="post">
            <button type="submit" class="btn btn-outline-danger">Logout</button>
        </form>
        </div>
    </div>
</header>