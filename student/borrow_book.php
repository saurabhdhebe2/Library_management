<?php
include '../includes/header.php';
include '../config/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$query = "SELECT * FROM books WHERE quantity > 0";
$result = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $user_id = $_SESSION['user']['id'];

    $stmt = $conn->prepare("INSERT INTO borrow_history (user_id, book_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $book_id);
    if ($stmt->execute()) {
        // Update book quantity
        $conn->query("UPDATE books SET quantity = quantity - 1 WHERE id = $book_id");
        echo "<div class='alert alert-success'>Book borrowed successfully.</div>";
    }
}
?>
<h2>Borrow Book</h2>
<form method="post">
<a href="/student/dashboard.php">back</a>

    <select name="book_id" required>
        <?php while ($book = $result->fetch_assoc()) { ?>
            <option value="<?= $book['id'] ?>"><?= $book['title'] ?> by <?= $book['author'] ?></option>
        <?php } ?>
    </select>
    <button type="submit">Borrow</button>
</form>
<?php include '../includes/footer.php'; ?>
