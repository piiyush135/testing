<?php
require('connection.php');
session_start();

function showErrorAlert($message) {
    echo "<script>alert('$message');</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $email_username = $_POST['username'];
        $password = $_POST['password'];

        $query = "SELECT * FROM registered_user WHERE `E-mail`=? OR username=?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "ss", $email_username, $email_username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && mysqli_num_rows($result) == 1) {
            $result_fetch = mysqli_fetch_assoc($result);
            $hashed_password_from_db = $result_fetch['Password'];

            if (password_verify($password, $hashed_password_from_db)) {
                // Password is correct, redirect to main.html
                header("Location: main.html");
                exit();
            } else {
                // Incorrect password
                showErrorAlert("Incorrect password");
            }
        } else {
            // User not found
            showErrorAlert("User not found");
        }
    } elseif (isset($_POST['register'])) {
        $fullname = $_POST['fullname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
            showErrorAlert("Please fill in all fields");
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            showErrorAlert("Invalid email format");
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $user_exist_query = "SELECT * FROM registered_user WHERE `Username`=? OR `E-mail`=?";
            $stmt = mysqli_prepare($con, $user_exist_query);
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $result_fetch = mysqli_fetch_assoc($result);
                if ($result_fetch['Username'] == $username) {
                    showErrorAlert("$username - Username already taken");
                } elseif ($result_fetch['E-mail'] == $email) {
                    showErrorAlert("$email - E-mail already registered");
                }
            } else {
                $query = "INSERT INTO registered_user (`Full Name`, `Username`, `E-mail`, `Password`) VALUES (?, ?, ?, ?)";
                $stmt = mysqli_prepare($con, $query);
                mysqli_stmt_bind_param($stmt, "ssss", $fullname, $username, $email, $hashed_password);
                if (mysqli_stmt_execute($stmt)) {
                    // Registration successful
                    echo "<script>alert('Registration successful'); window.location.href='index.php';</script>";
                    exit();
                } else {
                    // Registration failed
                    showErrorAlert("Cannot run query");
                }
            }
        }
    }
}
?>
