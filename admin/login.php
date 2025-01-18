<?php 
 session_start();

      if(isset($_SESSION['email'])){
        header("Location:index.php");
    }
        
        ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Warrior's Portal</title>
    <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> -->
    <link href="https://fonts.googleapis.com/css2?family=Blaka&family=Bokor&family=Ceviche+One&family=Jaro:opsz@6..72&family=New+Rocker&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet" >
    
    <link rel="stylesheet" href="css/login.css">

</head>
<body>
    <div class="login-container">
        <div class="shield-border"></div>
        <div class="corner-armor armor-tl"></div>
        <div class="corner-armor armor-tr"></div>
        <div class="corner-armor armor-bl"></div>
        <div class="corner-armor armor-br"></div>
        
        <form id="login-form">
            <h1>Login</h1>
            <input type="hidden" name="login" value='login'>
            <div class="input-group">
                <input type="email" name="email" placeholder="Email" required />
            </div>
            
            <div class="sword-divider"></div>
            
            <div class="input-group">
                <input type="password" name="password" placeholder="Password" required />
            </div>
            
            <button type="submit">Login</button>

            <div class="battle-quote">
                "Victory belongs to those who dare to claim it"
            </div>
        </form>
    </div>


    <script>
      const form = document.querySelector("#login-form");

      form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const formData = new FormData(form);

        try {
          const response = await fetch("login_verify.php", {
            method: "POST",
            body: formData,
          });

          if (response.ok) {
            const result = await response.json();
            alert(result.message);

            if (result.status === "success") {
              window.location.href = "index.php"; // Redirect on success
            }
          } else {
            const error = await response.json();
            alert(error.message);
          }
        } catch (error) {
          console.error("Fetch error:", error);
          alert("Something went wrong. Please try again later.");
        }
      });
    </script>
</body>
</html>