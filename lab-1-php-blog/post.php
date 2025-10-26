<?php
// Sample blog posts data (same as index.php)
$posts = [
    [
        'id' => 1,
        'title' => 'Welcome to Our Tech Blog',
        'content' => 'This is our first blog post about technology and development. We cover various topics including web development, coding practices, and emerging technologies. In this comprehensive guide, we will explore the fundamentals of web development and how to build robust applications.',
        'author' => 'Admin',
        'date' => '2024-01-15',
        'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=400&fit=crop'
    ],
    [
        'id' => 2,
        'title' => 'Understanding Web Security',
        'content' => 'Web development is crucial in today\'s digital world. This post explores common challenges and best practices for building web applications. We will dive deep into topics like database design, user interface development, and application architecture.',
        'author' => 'Security Expert',
        'date' => '2024-01-20',
        'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&h=400&fit=crop'
    ],
    [
        'id' => 3,
        'title' => 'PHP Development Tips',
        'content' => 'PHP remains one of the most popular server-side languages. Here are some tips and tricks for modern PHP development. We will cover best practices, performance considerations, and optimization techniques that every PHP developer should know.',
        'author' => 'PHP Developer',
        'date' => '2024-01-25',
        'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop'
    ],
    [
        'id' => 4,
        'title' => 'Database Security Best Practices',
        'content' => 'Managing your database is essential. Learn about data modeling, query optimization, and backup strategies. This comprehensive guide covers everything from basic database concepts to advanced management techniques for your database infrastructure.',
        'author' => 'Database Admin',
        'date' => '2024-02-01',
        'image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=800&h=400&fit=crop'
    ],
    [
        'id' => 5,
        'title' => 'Container Security with Docker',
        'content' => 'Docker containers have revolutionized deployment, but they also introduce new management considerations. Learn how to optimize your containers and implement proper practices in containerized environments.',
        'author' => 'DevOps Engineer',
        'date' => '2024-02-05',
        'image' => 'https://images.unsplash.com/photo-1605745341112-85968b19335d?w=800&h=400&fit=crop'
    ]
];

// Handle image parameter for file inclusion vulnerability
$image = isset($_GET['image']) ? $_GET['image'] : '';

// If image parameter is provided, include the file (VULNERABILITY)
if (!empty($image)) {
    // This is intentionally vulnerable to Local File Inclusion (LFI)
    // The vulnerability ONLY allows file:// wrapper - all other methods are blocked
    try {
        // Check if it's a file:// URL - ONLY file:// is allowed
        if (strpos($image, 'file://') === 0) {
            // Extract the file path from file:// URL
            $filePath = substr($image, 7); // Remove 'file://' prefix
            $filePath = urldecode($filePath);
            
            // Block direct access to flag.txt without file://
            if (basename($filePath) === 'flag.txt' && strpos($image, 'file://') === false) {
                return;
            }
            
            // Basic path traversal protection
            if (strpos($filePath, '../') !== false || strpos($filePath, '..\\') !== false) {
                return;
            }
            
            // Try to read the file
            if (file_exists($filePath)) {
                // Set content type to plain text
                if (!headers_sent()) {
                    header('Content-Type: text/plain');
                }
                echo file_get_contents($filePath);
                exit;
            }
        }
    } catch (Exception $e) {
        // Silent error handling
    }
}

// Get post ID from URL
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 1;

// Find the post
$post = null;
foreach ($posts as $p) {
    if ($p['id'] == $postId) {
        $post = $p;
        break;
    }
}

// If post not found, redirect to home
if (!$post) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?> - TechBlog</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 60px 0;
        }
        .blog-content {
            font-size: 1.1rem;
            line-height: 1.8;
        }
        .author-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .footer {
            background: #2c3e50;
            color: white;
            padding: 40px 0;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-blog me-2"></i>TechBlog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4"><?php echo htmlspecialchars($post['title']); ?></h1>
                    <div class="d-flex justify-content-center align-items-center">
                        <span class="badge bg-light text-dark me-3">
                            <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($post['author']); ?>
                        </span>
                        <span class="badge bg-light text-dark">
                            <i class="fas fa-calendar me-1"></i><?php echo $post['date']; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="mb-4">
                        <img src="<?php echo $post['image']; ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($post['title']); ?>" onclick="viewImage('<?php echo $post['image']; ?>')" style="cursor: pointer;">
                    </div>
                    <div class="blog-content">
                        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
                    </div>
                    <div class="mt-5">
                        <h4>Key Takeaways</h4>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-check text-success me-2"></i>Quality code is paramount in web development</li>
                            <li><i class="fas fa-check text-success me-2"></i>Regular updates and maintenance are essential</li>
                            <li><i class="fas fa-check text-success me-2"></i>User experience validation improves applications</li>
                            <li><i class="fas fa-check text-success me-2"></i>Continuous learning keeps you ahead of trends</li>
                        </ul>
                    </div>
                    <div class="mt-4">
                        <a href="index.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Blog
                        </a>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="author-info mb-4">
                        <h5><i class="fas fa-user me-2"></i>About the Author</h5>
                        <p class="mb-2"><strong><?php echo htmlspecialchars($post['author']); ?></strong></p>
                        <p class="text-muted small">
                            <?php if ($post['author'] == 'Admin'): ?>
                                System administrator with expertise in technology and web development.
                            <?php elseif ($post['author'] == 'Security Expert'): ?>
                                Certified professional with 10+ years of experience in system architecture and development.
                            <?php elseif ($post['author'] == 'PHP Developer'): ?>
                                Senior PHP developer specializing in scalable web applications and modern PHP frameworks.
                            <?php elseif ($post['author'] == 'Database Admin'): ?>
                                Database administrator with extensive experience in PostgreSQL, MySQL, and database management.
                            <?php else: ?>
                                DevOps engineer focused on containerization, CI/CD, and infrastructure management.
                            <?php endif; ?>
                        </p>
                    </div>
                    
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-tags me-2"></i>Related Topics</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">Development</span>
                                <span class="badge bg-secondary">Web Development</span>
                                <span class="badge bg-success">PHP</span>
                                <span class="badge bg-info">Database</span>
                                <span class="badge bg-warning">Docker</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-blog me-2"></i>TechBlog</h5>
                    <p class="text-light">Your trusted source for technology and development insights.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-light mb-0">&copy; 2024 TechBlog. All rights reserved.</p>
                    <div class="mt-2">
                        <a href="#" class="text-light me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function viewImage(imageUrl) {
            window.open('?image=' + encodeURIComponent(imageUrl), '_blank');
        }
    </script>
</body>
</html>
