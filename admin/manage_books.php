<?php 
include '../includes/header.php'; 
include '../config/db.php'; 
?>

<h2>Manage Books</h2>

<div class="dashboard-buttons">
    <a href="add_book.php" class="btn btn-primary">Add New Book</a>
    <a href="borrow_history.php" class="btn btn-info">View Borrow History</a>
    <a href="../logout.php" class="btn btn-danger">Logout</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Book Title</th>
            <th>Author</th>
            <th>Quantity</th>
            <th>Image</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Fetch books
        $sql = "SELECT * FROM books WHERE status = 1";
        $result = $conn->query($sql);

        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['title']) . "</td>
                    <td>" . htmlspecialchars($row['author']) . "</td>
                    <td>" . $row['quantity'] . "</td>
                    <td><img src='" . $row['book_image_name'] . "' alt='" . htmlspecialchars($row['title']) . "' width='50'></td>
                    <td>" . ($row['status'] == 1 ? 'Available' : 'Unavailable') . "</td>
                    <td>
                        <a href='edit_book.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                    </td>
                </tr>";
        }
        ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>
