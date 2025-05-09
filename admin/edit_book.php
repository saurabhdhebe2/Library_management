<?php
include '../includes/header.php';
include '../config/db.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $quantity = $_POST['quantity'];
    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE books SET title = ?, author = ?, quantity = ? WHERE id = ?");
    $stmt->bind_param("ssii", $title, $author, $quantity, $id);
    if ($stmt->execute()) {
        header("Location: manage_books.php");
        exit();
    }
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
}
?>
<h2>Edit Book</h2>
<form method="post">
    <input type="hidden" name="id" value="<?= $book['id'] ?>">
    <input type="text" name="title" value="<?= $book['title'] ?>" required>
    <input type="text" name="author" value="<?= $book['author'] ?>" required>
    <input type="number" name="quantity" value="<?= $book['quantity'] ?>" required>
    <button type="submit">Update Book</button>
</form>
<?php include '../includes/footer.php'; ?>
