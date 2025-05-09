<?php 
include '../includes/header.php'; 
include '../config/db.php'; 
?>

<h2>Add New Book</h2>

<form action="add_book.php" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Book Title:</label>
        <input type="text" name="title" id="title" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="author">Author:</label>
        <input type="text" name="author" id="author" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="quantity">Quantity:</label>
        <input type="number" name="quantity" id="quantity" class="form-control" required>
    </div>
    <div class="form-group">
        <label for="book_image">Book Image:</label>
        <input type="file" name="book_image" id="book_image" class="form-control" accept="image/*" required>
    </div>
    <button type="submit" class="btn btn-primary">Add Book</button>
    <a href="/admin/dashboard.php">back</a>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $author = mysqli_real_escape_string($conn, $_POST['author']);
    $quantity = (int) $_POST['quantity'];

    // Handle image upload
    if (isset($_FILES['book_image']) && $_FILES['book_image']['error'] === 0) {
        $image_name = $_FILES['book_image']['name'];
        $image_tmp_name = $_FILES['book_image']['tmp_name'];
        $image_size = $_FILES['book_image']['size'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);

        // Validate image type and size (optional)
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($image_extension, $allowed_extensions) && $image_size < 5000000) {
            // Generate a unique name for the image
            $new_image_name = uniqid('book_', true) . '.' . $image_extension;
            $image_path = '../uploads/' . $new_image_name;

            // Move the uploaded file to the desired folder
            if (move_uploaded_file($image_tmp_name, $image_path)) {
                // Insert book details into the database including image
                $sql = "INSERT INTO books (title, author, quantity, book_image_name, status) VALUES (?, ?, ?, ?, 1)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssis", $title, $author, $quantity, $image_path);

                if ($stmt->execute()) {
                    echo "<p class='alert alert-success'>Book added successfully.</p>";
                } else {
                    echo "<p class='alert alert-danger'>Error adding book: " . $stmt->error . "</p>";
                }
            } else {
                echo "<p class='alert alert-danger'>Failed to upload image.</p>";
            }
        } else {
            echo "<p class='alert alert-danger'>Invalid image file. Please upload a valid image (jpg, jpeg, png, gif).</p>";
        }
    } else {
        echo "<p class='alert alert-danger'>Please upload an image.</p>";
    }
}
?>

<?php include '../includes/footer.php'; ?>
