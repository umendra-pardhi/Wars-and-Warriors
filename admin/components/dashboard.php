<!-- dashboard.php -->
<main class="main-content">
            <div class="dashboard-header">
                <h1 class="page-title">Dashboard Overview</h1>
               <a href="create_post.php"> <button class="action-button">Create New Post</button></a>
            </div>

            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="stat-number"><?php echo $result->num_rows ?></div>
                    <div class="stat-label">Total Posts</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number"><?php echo $categories->num_rows ?></div>
                    <div class="stat-label">Categories</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">12.5K</div>
                    <div class="stat-label">Total Views</div>
                </div>
            </div>

            <div class="content-table-container">
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <!-- <th>Date</th> -->
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
                    if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {


?>

<tr>
                            <td><?php echo $row['title']  ?></td>
                            <td><?php echo $row['category']  ?></td>
                            <!-- <td>Aug 12, 480 BC</td> -->
                            <td><span class="status-badge status-published">Published</span></td>
                            <td >
                            <a href="edit_post.php?id=<?php echo $row['id']; ?>" class="btn btn-warning">
                                        <i data-lucide="edit" class="icon"></i> 
                                    </a>
                                    <button class="btn btn-danger" onclick="deletePost(<?php echo $row['id']; ?>)">
                                        <i data-lucide="trash-2" class="icon"></i> 
                                    </button>
                            </td>
                        </tr>

        <?php
    }
} else {
    echo "No posts found.";
}

?>
                        
                    </tbody>
                </table>
            </div>
        </main>