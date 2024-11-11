<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header('Location: login.php');
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";  // Your DB username
$password = "";      // Your DB password
$dbname = "task_tracker";  // Your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the current user's ID
$user_id = $_SESSION['user_id'];

// Fetch all reminders for the logged-in user
$sql = "SELECT * FROM reminders WHERE user_id = $user_id ORDER BY reminder_date ASC";
$result = $conn->query($sql);

// Handle new reminder submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['title'], $_POST['description'], $_POST['reminder_date'])) {
    // Get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $reminder_date = mysqli_real_escape_string($conn, $_POST['reminder_date']);

    // Insert new reminder into the database
    $insert_sql = "INSERT INTO reminders (user_id, title, description, reminder_date) 
                   VALUES ('$user_id', '$title', '$description', '$reminder_date')";

    if ($conn->query($insert_sql) === TRUE) {
        echo "<script>alert('Reminder added successfully!'); window.location.href = 'reminders.php';</script>";
    } else {
        echo "<script>alert('Error adding reminder: " . $conn->error . "');</script>";
    }
}

// Handle reminder deletion
if (isset($_GET['delete'])) {
    $delete_id = (int)$_GET['delete'];
    $delete_sql = "DELETE FROM reminders WHERE id = $delete_id AND user_id = $user_id";

    if ($conn->query($delete_sql) === TRUE) {
        echo "<script>alert('Reminder deleted successfully!'); window.location.href = 'reminders.php';</script>";
    } else {
        echo "<script>alert('Error deleting reminder: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Tracker - Reminders</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Your Reminders</h2>

        <!-- Add Reminder Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Add New Reminder</h5>
                <form action="reminders.php" method="POST">
                    <div class="form-group">
                        <label for="title">Reminder Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="reminder_date">Reminder Date</label>
                        <input type="datetime-local" class="form-control" id="reminder_date" name="reminder_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Reminder</button>
                </form>
            </div>
        </div>

        <!-- Display All Reminders -->
        <h3>Your Upcoming Reminders</h3>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Description</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['description']); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($row['reminder_date'])); ?></td>
                            <td>
                                <a href="reminders.php?delete=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="text-center">No reminders found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
