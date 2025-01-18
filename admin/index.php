<?php 
 session_start();

    if(!isset($_SESSION['email'])){
        header("Location:login.php");
    }

 
include('config/connection.php');

$sql = "SELECT * FROM posts ORDER BY created_at DESC";

$result = $conn->query($sql);

$categories = $conn->query("SELECT * FROM categories");
    
        ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wars & Warriors - Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=New+Rocker&family=Cinzel:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/sidebar.css">
    <link rel="stylesheet" href="css/dashboard.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>



</head>
<body>

    <div class="dashboard-container">
        
    <?php
    
    include('components/sidebar.php');
    include('components/dashboard.php');
 
    ?>
        
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script>

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


<?php
$conn->close();
?>

