
<?php 

 session_start();

 if(!isset($_SESSION['email'])){
     header("Location:login.php");
 }


include('config/connection.php');

$categoryResult = $conn->query("SELECT id, name FROM categories");


// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Retrieve form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $category = $_POST['category'];
    $status = $_POST['status'] ?? 'draft';  

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
    
//     // Check if file is an image
//     $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
//     $allowedTypes = ['jpg', 'jpeg', 'png', 'gif','webp','svg'];
    
//     if (in_array($imageFileType, $allowedTypes)) {
//         if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
//             $image = $uploadFile; // Store the image path in the database
//         } else {
//             echo "Error uploading the image.";
//         }
//     } else {
//         echo "Invalid image type. Only JPG, JPEG, PNG, and GIF are allowed.";
//     }
// }


    // Prepare SQL query to insert post data
    $sql = "INSERT INTO posts (title, content, category, image, created_at, status) 
            VALUES (?, ?, ?, ?, NOW(), ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters and execute
        $stmt->bind_param("sssss", $title, $content, $category, $image, $status);

        if ($stmt->execute()) {
            // Success: Redirect to another page or show a success message
            header('Location: index.php');  // You can change this to a success page
            exit();
        } else {
            // Error: Output error message
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        // If the statement preparation failed
        echo "Error: Could not prepare statement.";
    }
    
    // Close the database connection
    $conn->close();
}

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


  <link rel="stylesheet" href="css/createpost.css">

</head>
<body>
<main class="main-content">
            <div class="page-header">
                <h1 class="page-title">Create New Post</h1>
            </div>


            <form class="create-post-form" method="POST"  enctype="multipart/form-data">
    <!-- Post Title -->
    <div class="form-group">
        <label class="form-label" for="title">Post Title</label>
        <input type="text" id="title" name="title" class="form-input" placeholder="Enter post title..." required>
    </div>
  
    <!-- Thumbnail Image -->
    <div class="form-group">
        <label for="image" class="form-label">Thumbnail Image
        <div class="image-upload" id="imageUpload">
            <i class="image-upload-icon" data-lucide="image-plus"></i><br>
            <input type="file" id="image-input" name="image" accept="image/*" required>
        </div>
        </label>
    </div>
 
    <!-- Category -->
    <div class="form-group">
        <label class="form-label" for="category">Category</label>
        <select id="category" name="category" class="form-input category-select" required>
            <?php while ($row = $categoryResult->fetch_assoc()): ?>
                <option value="<?php echo $row['name']; ?>"><?php echo $row['name']; ?></option>
            <?php endwhile; ?>
        </select>

    </div>

    <!-- Post Content (Summernote) -->
    <div class="form-group">
        <label class="form-label" for="content">Post Content</label>
        <textarea id="content" name="content" required></textarea>
    </div>

    <!-- Hidden Status Input -->
    <input type="hidden" id="status" name="status">

    <!-- Form Actions -->
    <div class="form-actions">
        <button type="button" class="_btn _btn-secondary" id="saveDraftButton">Save Draft</button>
        <button type="button" class="_btn _btn-primary" id="publishPostButton">Publish Post</button>
    </div>
</form>

        </main>


        <script>
       $(document).ready(function() {
    // Initialize Summernote editor
    $('#content').summernote({
        placeholder: 'Enter Post Content here...',
        tabsize: 2,
        height: 200
    });

    // Handle Save Draft button click
    $('#saveDraftButton').click(function() {
        // Set the status to 'draft' and submit the form
        $('#status').val('draft');
        $('form').submit();  // Submit the form
    });

    // Handle Publish Post button click
    $('#publishPostButton').click(function(event) {
        // Set the status to 'published' and submit the form
        $('#status').val('published');
        $('form').submit();  // Submit the form
    });
});
</script>

          
    <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();

    
  </script>
</body>
</html>