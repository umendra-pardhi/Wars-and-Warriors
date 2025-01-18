<?php
// Include the connection file
include('config/connection.php');

$categoryResult = $conn->query("SELECT id, name FROM categories");

// Check if the id and title are passed via the URL
if (isset($_GET['id']) && isset($_GET['title'])) {
    $postId = $_GET['id'];
    $postTitle = $_GET['title'];

    // Prepare the SQL query to fetch the post by its id and title
    $sql = "SELECT * FROM posts WHERE id = ? AND title = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $postId, $postTitle);  // 'i' for integer, 's' for string
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the post exists
    if ($result->num_rows > 0) {
        // Fetch the post data
        $post = $result->fetch_assoc();
    } else {
        // If no post is found
        echo "Post not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
    exit;
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wars & Warriors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Blaka&family=Bokor&family=Ceviche+One&family=Jaro:opsz@6..72&family=New+Rocker&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css" />
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "New Rocker", serif;
            background-color: #2c1810;
            color: #d4b888;
            line-height: 1.6;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h60v60H0z' fill='%23372318'/%3E%3Cpath d='M0 0h30v30H0zm30 30h30v30H30z' fill='%23251710' fill-opacity='0.4'/%3E%3C/svg%3E");
        }

        

.crossed-swords {
    display: inline-block;
    margin: 0 20px;
    font-size: 2rem;
    transform: rotate(45deg);
}


        .article-container {
            max-width: 80%;
            margin: 2rem auto;
            padding: 2rem;
            background: linear-gradient(to bottom right, #372318, #2c1810);
            border: 3px solid #826f4a;
            border-radius: 5px;
            position: relative;
            animation: pageLoad 1s ease-out;
        }

        /* .article-container::before {
    content: '';
    position: absolute;
    top: 10px;
    left: 10px;
    right: 10px;
    bottom: 10px;
    border: 1px solid #826f4a;
    pointer-events: none;
} */

        .article-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #826f4a;
        }

        .article-metadata {
            font-size: 0.9rem;
            color: #a08860;
            margin: 1rem 0;
            display:flex;
            justify-content:space-between;
        }

        .article-image {
            width: 100%;
            height: 400px;
            margin: 2rem 0;
            border: 3px solid #826f4a;
            overflow: hidden;
        }

        .article-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .article-content {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-top: 2rem;

            overflow-wrap: break-word;  
    word-wrap: break-word;  
        }

        .article-content    pre {
    white-space: normal;        
    overflow-wrap: break-word;  
  }

        .article-content p {
            margin-bottom: 1.5rem;
            text-align: justify;
            overflow-wrap: break-word;  
        }




        .decorative-border {
            position: absolute;
            width: 30px;
            height: 30px;
            border: 2px solid #826f4a;
        }

        .top-left { top: 5px; left: 5px; border-right: none; border-bottom: none; }
        .top-right { top: 5px; right: 5px; border-left: none; border-bottom: none; }
        .bottom-left { bottom: 5px; left: 5px; border-right: none; border-top: none; }
        .bottom-right { bottom: 5px; right: 5px; border-left: none; border-top: none; }

        .torch {
            position: fixed;
            width: 32px;
            height: 32px;
            background: radial-gradient(circle at center, #ffd700, transparent 70%);
            pointer-events: none;
            mix-blend-mode: screen;
            z-index: 1000;
        }

        .article-content img {
        /* width: fit-content !important; */
    }

        @media (max-width: 768px) {
            .article-container {
                margin: 1rem;
                padding: 1rem;
                max-width: 100%;
            }
            
            .header-title {
                font-size: 1.8rem;
            }

            .article-image {
                height: 250px;
            }
            .article-content img{
                width:100% !important;
            }
            
        }
    </style>

<link rel="stylesheet" href="assets/css/nav.css" />

</head>
<body>
    <div class="torch"></div>

    <header>
        <div class="header-decoration"></div>
        <h1 class="header-title">
          <!-- <span class="crossed-swords">⚔️</span> -->
          Wars & Warriors
          <!-- <span class="crossed-swords">⚔️</span> -->
        </h1>
        <p>Exploring the Epic Tales of History's Greatest Warriors</p>
      </header>
  
      <nav>

    <?php while ($row = $categoryResult->fetch_assoc()): ?>
        <a href="index.php#<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a>
    <?php endwhile; ?>

    <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
     
    </nav>

    <main class="article-container">
        <div class="decorative-border top-left"></div>
        <div class="decorative-border top-right"></div>
        <div class="decorative-border bottom-left"></div>
        <div class="decorative-border bottom-right"></div>

        <div class="article-header">
            <h2><?php echo htmlspecialchars($post['title']); ?></h2>
            
            <div class="article-metadata">
                <div>
                Category: <?php echo htmlspecialchars($post['category']); ?>
                </div>
                <div>
                <?php echo htmlspecialchars($post['created_at']); ?>
                </div>
            
           
            </div>
        </div>

        <div class="article-image">
        <?php
            if ($post['image']) {
                echo "<img src='" . htmlspecialchars($post['image']) . "' alt='Post Image' />";
            }
            ?>
        </div>

        <div class="article-content">
        <?php echo $post['content']; ?>
        </div>
    </main>

    <footer>
        <p>⚔️ Wars & Warriors ⚔️</p>
        <p>Preserving the Legacy of History's Greatest Warriors</p>
      </footer>

    <script>
        document.addEventListener("mousemove", (e) => {
            const torch = document.querySelector(".torch");
            torch.style.left = e.pageX - 16 + "px";
            torch.style.top = e.pageY - 16 + "px";
        });
    </script>
        <script src="assets/js/nav.js"></script>
</body>
</html>