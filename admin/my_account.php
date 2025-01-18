<?php
session_start();

if(!isset($_SESSION['email'])){
    header("Location:login.php");
}
    
include('includes/connection.php');

// Get the logged-in admin's email from session
$adminEmail = $_SESSION['email'];

// Fetch admin details from the database
$result = $conn->query("SELECT * FROM users WHERE email = '$adminEmail'");
if ($result->num_rows > 0) {
    $admin = $result->fetch_assoc();
} else {
    echo "Admin not found.";
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Details</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


    <style>
        * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: monospace;
    background-color: #1a0f0a;
    /* color: #e5ccaa; */
    line-height: 1.6;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h60v60H0z' fill='%23251710'/%3E%3Cpath d='M0 0h30v30H0zm30 30h30v30H30z' fill='%231a0f0a' fill-opacity='0.4'/%3E%3C/svg%3E");
}

.box{
    background: linear-gradient(145deg, #372318, #2c1810);
    padding: 2rem;
    color: #e5ccaa;
    border: 2px solid #826f4a;
    border-radius: 8px;
 
}

    </style>
</head>
<body>

<div class="container mt-5 d-flex justify-content-center ">

<div class="col-12 col-md-6  p-3 box">

<h1 class='text-center'>My Account</h1>

    <p><strong>Name:</strong> <?php echo $admin['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $admin['email']; ?></p>
    <br>
  
    <div class="d-flex justify-content-end gap-3 flex-wrap">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDetailsModal">
        Edit Details
    </button>

     <button type="button" class="btn btn-warning " data-bs-toggle="modal" data-bs-target="#passwordModal">Change Password</button>
    </div>
    

</div>
    
    <!-- Edit Details Modal -->
    <div class="modal fade" id="editDetailsModal" tabindex="-1" aria-labelledby="editDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDetailsModalLabel">Edit Admin Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateDetailsForm">
                        <div class="form-group mb-2">
                            <label for="name">Name:</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $admin['name']; ?>" required>
                        </div>
                        
                        <div class="form-group mb-2">
                            <label for="email">Email:</label>
                            <input type="email" name="email" class="form-control" value="<?php echo $admin['email']; ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="current_password">Password:</label>
                            <input type="password" name="current_password" class="form-control" placeholder='enter password here...' required>
                        </div>
                        <button type="submit" name="update_admin" class="btn btn-success mb-3">Update Details</button>
                    </form>
                    <div id="updateResponse"></div>
                </div>
            </div>
        </div>
    </div>

   

    <!-- Change Password Modal -->
    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="changePasswordForm">
                        <div class="form-group mb-2">
                            <label for="current_password_modal">Current Password:</label>
                            <input type="password" name="current_password_modal" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password_modal">New Password:</label>
                            <div class="input-group">
                            <input type="password" name="new_password_modal" class="form-control" id="new_password_modal" required>
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">Show</button>
                            </div>
                            
                        </div>
                        <button type="submit" name="update_password" class="btn btn-success mb-3">Update Password</button>
                    </form>
                    <div id="passwordResponse"></div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<script>
    // Toggle password visibility
    function togglePassword() {
        var passwordField = document.getElementById('new_password_modal');
        var btn = event.target;
        if (passwordField.type === "password") {
            passwordField.type = "text";
            btn.innerText = "Hide";
        } else {
            passwordField.type = "password";
            btn.innerText = "Show";
        }
    }


    // Handle update admin details form submission
document.getElementById('updateDetailsForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    formData.append('update_admin', true);  // Adding custom field to distinguish the form
    
    fetch('update_admin_details.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const updateResponse = document.getElementById('updateResponse');
        updateResponse.innerHTML = `<div class="alert p-2 alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;
    })
    .catch(error => {
        console.error('Error:', error);
    });
});

// Handle change password form submission
document.getElementById('changePasswordForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    formData.append('update_password', true);  // Adding custom field to distinguish the form
    
    fetch('update_admin_details.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        const passwordResponse = document.getElementById('passwordResponse');
        passwordResponse.innerHTML = `<div class="alert p-2 alert-${data.status === 'success' ? 'success' : 'danger'}">${data.message}</div>`;
    })
    .catch(error => {
        console.error('Error:', error);
    });
});


</script>

</body>
</html>

<?php
$conn->close();
?>
