<?php


session_start();

if(!isset($_SESSION['email'])){
    header("Location:login.php");
}


include('includes/connection.php');

// Fetch all posts from the database
$sql = "SELECT * FROM posts";
$result = $conn->query($sql);

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wars & Warriors - Post Management</title>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <link rel="stylesheet" href="css/managepost.css">
</head>
<body>
    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Post Management</h1>
            <a href="create_post.php" class="btn create-btn">Create New Post</a>
        </div>

        <div class="content-card">
            <table class="posts-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['title']); ?></td>
                            <td><?php echo htmlspecialchars($row['category']); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($row['status']); ?>">
                                    <?php echo ucfirst($row['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                        <i data-lucide="edit" class="icon"></i> Edit
                                    </a>
                                    <button class="btn btn-danger" onclick="deletePost(<?php echo $row['id']; ?>)">
                                        <i data-lucide="trash-2" class="icon"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

   <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        // Initialize Lucide icons
        lucide.createIcons();


    function deletePost(postId) {
        if (confirm('Are you sure you want to delete this post?')) {
        // Delete post using Fetch API
        fetch('delete_post.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: postId }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Post deleted successfully');
                location.reload();  // Reload the page after deletion
            } else {
                alert('Failed to delete post');
            }
        })
        .catch(error => {
            alert('Error: ' + error);
        });
    }
}
</script>

</body>
</html>