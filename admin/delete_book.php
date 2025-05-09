<?php
include '../includes/header.php';
include '../config/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Update the status of the book to 0 (inactive) instead of deleting
    $stmt = $conn->prepare("UPDATE books SET status = 0 WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: manage_books.php");
        exit();
    } else {
        echo "Error deleting book.";
    }
}

?>
