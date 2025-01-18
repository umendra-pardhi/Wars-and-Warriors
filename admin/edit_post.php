<?php

session_start();

if(!isset($_SESSION['email'])){
    header("Location:login.php");
}

include('config/connection.php');

// Check if post ID is provided in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];

    // Fetch the post data based on the provided ID
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();

    // Fetch all categories
    $categoryResult = $conn->query("SELECT id, name FROM categories");

  

    if (!$post) {
        // If no post found, redirect to post list
        header('Location: manage_post.php');
        exit;
    }
} else {
    // If no ID provided, redirect to post list
    header('Location: manage_post.php');
    exit;
}

// Handle form submission to update the post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'];
    $category = $_POST['category'];
    $content = $_POST['content'];
    $status = $_POST['status'];

    // Handle image upload and convert to base64 string
$image = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
       // Get the image file and encode it in base64
       $imageData = file_get_contents($_FILES['image']['tmp_name']);
       $imageBase64 = base64_encode($imageData);
       // Add the data:image MIME type prefix
       $imageBase64 = 'data:image/jpeg;base64,' . $imageBase64;
       $image =  $imageBase64;
   } else {
       // If no new image is uploaded, retain the old image
       // $imageBase64 = $post['image'];
       $image= $post['image'];
   }


    
// Handle image upload
// $image = null;
// if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
//     $uploadDir = "../uploads/";  // Directory to store images
//     $uploadFile = $uploadDir . basename($_FILES['image']['name']);
//     $imgpath="uploads/". basename($_FILES['image']['name']);
    
//     // Check if file is an image
//     $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
//     $allowedTypes = ['jpg', 'jpeg', 'png', 'gif','webp','svg'];
    
//     if (in_array($imageFileType, $allowedTypes)) {
//         if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
//             $image = $imgpath; // Store the image path in the database
//         } else {
//             echo "Error uploading the image.";
//         }
//     } else {
//         echo "Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.";
//     }
// }else {
     
//     $image = $post['image'];
// }


    // Update the post in the database
    $sql = "UPDATE posts SET title = ?, category = ?, content = ?, status = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $title, $category, $content, $status, $image, $postId);

    if ($stmt->execute()) {
        // Redirect to the post list after successful update
        header('Location: manage_post.php');
        exit;
    } else {
        $error = "Error updating post.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Blaka&family=Bokor&family=Ceviche+One&family=Jaro:opsz@6..72&family=New+Rocker&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">

    <script type="text/javascript" src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.css" integrity="sha512-rDHV59PgRefDUbMm2lSjvf0ZhXZy3wgROFyao0JxZPGho3oOuWejq/ELx0FOZJpgaE5QovVtRN65Y3rrb7JhdQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs5.min.js" integrity="sha512-qTQLA91yGDLA06GBOdbT7nsrQY8tN6pJqjT16iTuk08RWbfYmUz/pQD3Gly1syoINyCFNsJh7A91LtrLIwODnw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <link rel="stylesheet" href="css/editpost.css">

</head>
<body>
<main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Create New Post</h1>
            </div>

        

            <form class="create-post-form" method="POST" action="edit_post.php?id=<?php echo $post['id']; ?>"  enctype="multipart/form-data">
    <!-- Post Title -->
    <div class="form-group">
        <label class="form-label" for="title">Post Title</label>
        <input type="text" id="title" name="title" class="form-input" placeholder="Enter post title..." value="<?php echo htmlspecialchars($post['title']); ?>" required>
    </div>
  
    <!-- Thumbnail Image -->
    <div class="form-group">
        <label for="image" class="form-label">Thumbnail Image
        <?php if ($post['image']): ?>
                <img src="<?php echo $post['image']; ?>" alt="Post Image" style="max-width: 200px; max-height: 200px;"><br>
                <small>Current Image</small><br><br>
            <?php endif; ?>
        <div class="image-upload" id="imageUpload">
            <i class="image-upload-icon" data-lucide="image-plus"></i><br>
            <input type="file" id="image-input" name="image" accept="image/*" >
            <small>Leave blank to keep the current image.</small>
        </div>
        </label>
    </div>
 
    <!-- Category -->
    <div class="form-group">
        <label class="form-label" for="category">Category</label>
        <select id="category" name="category" class="form-input category-select" required>
        <?php while ($row = $categoryResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['name']; ?>" <?php echo $post['category'] == $row['name'] ? 'selected' : ''; ?>>
                        <?php echo $row['name']; ?>
                    </option>
                <?php endwhile; ?>
        </select>

    </div>

    <!-- Post Content (Summernote) -->
    <div class="form-group">
        <label class="form-label" for="content">Post Content</label>
        <textarea id="content" name="content" required><?php echo htmlspecialchars($post['content']); ?></textarea>
    </div>

    <div class="form-group">
    <label for="status" class="form-label">Status</label>
            <select class="form-select" id="status" name="status" required>
                <option value="draft" <?php echo $post['status'] == 'draft' ? 'selected' : ''; ?>>Draft</option>
                <option value="published" <?php echo $post['status'] == 'published' ? 'selected' : ''; ?>>Published</option>
            </select>
    </div>

 
    <!-- Form Actions -->
    <div class="form-actions">
    <button type="submit" class="_btn _btn-primary">Update Post</button>
    </div>
</form>

<?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3"><?php echo $error; ?></div>
    <?php endif; ?>
        </main>


        <script>
       $(document).ready(function() {
    // Initialize Summernote editor
    $('#content').summernote({
        placeholder: 'Enter Post Content here...',
        tabsize: 2,
        height: 200
    });
});
</script>

          
    <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();

    
  </script>
</body>
</html>