<?php 
session_start();

if(!isset($_SESSION['email'])) {
    header("Location:login.php");
}

include('../config/connection.php');

// Handle Add Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];
    if (!empty($category_name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        $stmt->bind_param("s", $category_name);
        if ($stmt->execute()) {
            $success_message = "Category added successfully!";
        } else {
            $error_message = "Error adding category: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle Edit Category
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT id, name FROM categories WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $stmt->bind_result($id, $name);
    $stmt->fetch();
    $stmt->close();
}

// Handle Update Category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_category_name'], $_POST['edit_category_id'])) {
    $category_name = $_POST['edit_category_name'];
    $category_id = $_POST['edit_category_id'];
    if (!empty($category_name)) {
        $stmt = $conn->prepare("UPDATE categories SET name = ? WHERE id = ?");
        $stmt->bind_param("si", $category_name, $category_id);
        if ($stmt->execute()) {
            $success_message = "Category updated successfully!";
        } else {
            $error_message = "Error updating category: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle Delete Category
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $success_message = "Category deleted successfully!";
    } else {
        $error_message = "Error deleting category: " . $stmt->error;
    }
    $stmt->close();
}

// Get all categories for display
$result = $conn->query("SELECT id, name FROM categories");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wars & Warriors - Categories Management</title>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lucide/0.263.1/umd/lucide.min.js"></script>
   <link rel="stylesheet" href="css/managecategories.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Categories Management</h1>
        </div>

        <?php if (isset($success_message)): ?>
            <div class="message message-success">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="message message-error">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Add Category Form -->
        <div class="card">
            <h2 class="card-title">Add New Category</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-input" id="category_name" name="category_name" required>
                </div>
                <button type="submit" class="btn">Add Category</button>
            </form>
        </div>

        <!-- Edit Category Form -->
        <?php if (isset($edit_id)): ?>
        <div class="card">
            <h2 class="card-title">Edit Category</h2>
            <form method="post" action="">
                <div class="form-group">
                    <label for="edit_category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-input" id="edit_category_name" name="edit_category_name" value="<?php echo htmlspecialchars($name); ?>" required>
                    <input type="hidden" name="edit_category_id" value="<?php echo $edit_id; ?>">
                </div>
                <button type="submit" class="btn">Update Category</button>
            </form>
        </div>
        <?php endif; ?>

        <!-- Categories List -->
        <div class="card">
            <h2 class="card-title">Existing Categories</h2>
            <table class="table">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <a href="?edit=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
                                <a href="?delete=<?php echo $row['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Initialize Lucide icons
        lucide.createIcons();
    </script>
</body>
</html>

<?php
$conn->close();
?>