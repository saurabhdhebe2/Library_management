<?php
// Start the session and include necessary files
include '../includes/header.php';
include '../config/db.php';

// Ensure the user is an admin
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Fetch borrow history for all users
$sql = "SELECT bh.id, u.name AS user_name, b.title AS book_title, bh.borrowed_at, bh.returned_at, bh.status 
        FROM borrow_history bh
        JOIN users u ON bh.user_id = u.id
        JOIN books b ON bh.book_id = b.id
        ORDER BY bh.borrowed_at DESC";
$result = $conn->query($sql);
?>
        <a href="/admin/dashboard.php">back</a>

<h2>Borrow History</h2>
<table class="table">
    <thead>
        <tr>
            <th>User Name</th>
            <th>Book Title</th>
            <th>Borrowed At</th>
            <th>Returned At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['user_name']) ?></td>
                <td><?= htmlspecialchars($row['book_title']) ?></td>
                <td><?= htmlspecialchars($row['borrowed_at']) ?></td>
                <td><?= $row['returned_at'] ? htmlspecialchars($row['returned_at']) : 'Not returned' ?></td>
                <td><?= $row['status'] == 'borrowed' ? 'Borrowed' : 'Returned' ?></td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
