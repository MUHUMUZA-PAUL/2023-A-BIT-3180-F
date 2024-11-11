<?php
require 'db.php';

$stmt = $pdo->query("SELECT * FROM tasks");
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Tracker</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container">
    <h2>Task Tracker</h2>
    <a href="add_task.php" class="btn btn-success">Add Task</a>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>Title</th>
                <th>Description</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['description']) ?></td>
                    <td><?= htmlspecialchars($task['status']) ?></td>
                    <td>
                        <a href="update_task.php?id=<?= $task['id'] ?>" class="btn btn-warning">Edit</a>
                        <a href="delete_task.php?id=<?= $task['id'] ?>" class="btn btn-danger">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
