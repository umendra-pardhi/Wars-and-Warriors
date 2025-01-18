<?php

include('includes/connection.php');


$categoryResult = $conn->query("SELECT id, name FROM categories");

// $sql = "SELECT * FROM posts WHERE status='published'";
// $posts = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wars & Warriors</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Blaka&family=Bokor&family=Ceviche+One&family=Jaro:opsz@6..72&family=New+Rocker&family=Sedgwick+Ave+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css" />
    <link rel="stylesheet" href="assets/css/loader.css" />
    <link rel="stylesheet" href="assets/css/nav.css" />

  </head>
  <body>
    <div class="loader">
      <div class="loader-bg">
        <div class="sword-container">
          <div class="sword sword-left">
            <img src="assets/img/sword.svg" alt="" />
          </div>
          <div class="sword sword-right">
            <img src="assets/img/sword.svg" alt="" />
          </div>
          <div class="clash-effect"></div>
        </div>
        <div class="loading-text">Wars & Warriors</div>
      </div>
    </div>

    <div class="torch"></div>
    <div class="homepage">

   

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
        <a href="#<?php echo $row['name']; ?>"><?php echo $row['name']; ?></a>
    <?php endwhile; ?>

    <div class="hamburger">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>
     
    </nav>

    <div class="main-content">


   <?php 

function trimTextByWords($text, $wordLimit) {
    // Convert the text into an array of words
    $words = explode(' ', $text);
    
    // If the number of words is greater than the limit, truncate the array
    if (count($words) > $wordLimit) {
        $words = array_slice($words, 0, $wordLimit);
        $text = implode(' ', $words) . ' <b>Read more...<b>';  // Add ellipsis after trimming
    }
    
    return $text;
}

$categoryResult = $conn->query("SELECT id, name FROM categories");

    if ($categoryResult && $categoryResult->num_rows > 0) {

    while ($rowc = $categoryResult->fetch_assoc()) {
        ?>
        <section class="featured-section" id="<?php echo $rowc['name']; ?>">
            <div class="corner-decoration top-left"></div>
            <div class="corner-decoration top-right"></div>
            <div class="corner-decoration bottom-left"></div>
            <div class="corner-decoration bottom-right"></div>
            <h2 class="section-title"><?php echo $rowc['name']; ?></h2>


            <?php
            // Fetch posts that belong to the current category
            $postsQuery = $conn->prepare("SELECT id, title, content, image, category FROM posts WHERE status='published' AND category = ?");
            $postsQuery->bind_param("s", $rowc['name']); // Bind the category name dynamically
            $postsQuery->execute();
            $posts = $postsQuery->get_result(); // Get the result

            if ($posts->num_rows > 0) {
                // Loop through posts and display them
                while ($row = $posts->fetch_assoc()) {
                    ?>
                    <a href="view_article.php?id=<?php echo $row['id']; ?>&title=<?php echo $row['title']; ?>" class="warrior-card">
                        <div class="warrior-image">
                            <?php
                            // Display the image if it exists
                            if ($row['image']) {
                                echo "<img src='" . $row['image'] . "' alt='Post Image' />";
                            }
                            ?>
                        </div>
                        <div class="warrior-info">
                            <h3 class="warrior-name"><?php echo $row['title']; ?></h3>
                            <div class='content-area'>
                                <?php
                               
                                $trimmedText = trimTextByWords($row['content'], 30);
                                echo $trimmedText;
                                ?>
                            </div>
                        </div>
                    </a>
                    <?php
                }
            } else {
                echo "No posts found in this category.";
            }

            $postsQuery->close(); // Close the posts query
            ?>

            



        </section>
        <?php
    }
} else {
    echo "No categories found.";
}

$conn->close();
?>


    </div>

    <footer>
      <p>⚔️ Wars & Warriors ⚔️</p>
      <p>Preserving the Legacy of History's Greatest Warriors</p>
    </footer>
</div>
    <script>
      // Torch effect
      document.addEventListener("mousemove", (e) => {
        const torch = document.querySelector(".torch");
        torch.style.left = e.pageX + "px";
        torch.style.top = e.pageY + "px";
      });
    </script>

    <script src="assets/js/loader.js"></script>
    <script src="assets/js/nav.js"></script>
  </body>
</html>
