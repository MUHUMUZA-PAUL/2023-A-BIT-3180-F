<?php
// Database connection
$servername = "localhost";
$username = "root";  // Your DB username
$password = "";      // Your DB password
$dbname = "task_tracker";  // Your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $user_name = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

    // Validate if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if the user already exists
        $check_user = "SELECT * FROM users WHERE email = '$email'";
        $result = $conn->query($check_user);

        if ($result->num_rows > 0) {
            echo "<script>alert('User already exists with that email.');</script>";
        } else {
            // Insert the user into the database
            $sql = "INSERT INTO users (username, email, password) VALUES ('$user_name', '$email', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Registration successful. You can now log in.'); window.location.href = 'login.php';</script>";
            } else {
                echo "<script>alert('Error: " . $sql . " " . $conn->error . "');</script>";
            }
        }
    }
}

$conn->close();
?>
