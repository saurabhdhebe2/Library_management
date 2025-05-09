<?php
include '../includes/header.php';
include '../config/db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

// Get the current user ID
$user_id = $_SESSION['user']['id'];

// Fetch borrowed books for the logged-in user
$sql = "SELECT books.title, borrow_history.book_id, borrow_history.borrowed_at, borrow_history.returned_at, borrow_history.status 
        FROM borrow_history 
        JOIN books ON borrow_history.book_id = books.id 
        WHERE borrow_history.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle the return action
if (isset($_GET['return_id'])) {
    $book_id = $_GET['return_id'];
    $return_time = date('Y-m-d H:i:s');
    
    // Update the status to "returned"
    $update_sql = "UPDATE borrow_history SET status = 'returned', returned_at = ? WHERE book_id = ? AND user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sii", $return_time, $book_id, $user_id);
    
    if ($update_stmt->execute()) {
        // Update the book's quantity after it's returned
        $update_quantity_sql = "UPDATE books SET quantity = quantity + 1 WHERE id = ?";
        $update_quantity_stmt = $conn->prepare($update_quantity_sql);
        $update_quantity_stmt->bind_param("i", $book_id);
        $update_quantity_stmt->execute();

        header("Location: my_borrows.php");
        exit();
    }
}
?>

<h2>My Borrowed Books</h2>

<div class="dashboard-buttons">
    <a href="borrow_book.php" class="btn btn-primary">Borrow New Book</a>
    <a href="../logout.php" class="btn btn-danger">Logout</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Borrowed At</th>
            <th>Returned At</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= htmlspecialchars($row['borrowed_at']) ?></td>
                <td><?= $row['returned_at'] ? htmlspecialchars($row['returned_at']) : 'Not returned' ?></td>
                <td><?= $row['status'] == 'borrowed' ? 'Borrowed' : 'Returned' ?></td>
                <td>
                    <?php if ($row['status'] == 'borrowed'): ?>
                        <a href="?return_id=<?= $row['book_id'] ?>" class="btn btn-primary">Return</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
