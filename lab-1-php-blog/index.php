
<?php
// Sample blog posts data
$posts = [
    [
        'id' => 1,
        'title' => 'Welcome to Our Tech Blog',
        'content' => 'This is our first blog post about technology and cybersecurity. We cover various topics including web development, security practices, and emerging technologies.',
        'author' => 'Admin',
        'date' => '2024-01-15',
        'image' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=800&h=400&fit=crop'
    ],
    [
        'id' => 2,
        'title' => 'Understanding Web Security',
        'content' => 'Web security is crucial in today\'s digital world. This post explores common vulnerabilities and best practices for securing web applications.',
        'author' => 'Security Expert',
        'date' => '2024-01-20',
        'image' => 'https://images.unsplash.com/photo-1563986768609-322da13575f3?w=800&h=400&fit=crop'
    ],
    [
        'id' => 3,
        'title' => 'PHP Development Tips',
        'content' => 'PHP remains one of the most popular server-side languages. Here are some tips and tricks for modern PHP development.',
        'author' => 'PHP Developer',
        'date' => '2024-01-25',
        'image' => 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?w=800&h=400&fit=crop'
    ],
    [
        'id' => 4,
        'title' => 'Database Security Best Practices',
        'content' => 'Protecting your database is essential. Learn about SQL injection prevention, access controls, and encryption techniques.',
        'author' => 'Database Admin',
        'date' => '2024-02-01',
        'image' => 'https://images.unsplash.com/photo-1544383835-bda2bc66a55d?w=800&h=400&fit=crop'
    ],
    [
        'id' => 5,
        'title' => 'Container Security with Docker',
        'content' => 'Docker containers have revolutionized deployment, but they also introduce new security considerations. Learn how to secure your containers.',
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechBlog - Technology & Development</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 80px 0;
        }
        .blog-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        .blog-image {
            height: 250px;
            object-fit: cover;
            width: 100%;
        }
        .author-badge {
            background: linear-gradient(45deg, #667eea, #764ba2);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
        }
        .date-badge {
            background: #f8f9fa;
            color: #6c757d;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
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
            <a class="navbar-brand" href="#">
                <i class="fas fa-blog me-2"></i>TechBlog
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">Welcome to TechBlog</h1>
                    <p class="lead mb-4">Your source for the latest in technology and web development. Stay updated with expert insights and practical tutorials.</p>
                    <a href="#posts" class="btn btn-light btn-lg">
                        <i class="fas fa-arrow-down me-2"></i>Explore Posts
                    </a>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-laptop-code" style="font-size: 8rem; opacity: 0.3;"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <section id="posts" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-5">
                    <h2 class="display-5 fw-bold">Latest Blog Posts</h2>
                    <p class="lead text-muted">Discover insights from our expert authors</p>
                </div>
            </div>
            <div class="row">
                <?php foreach ($posts as $post): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card blog-card h-100">
                        <img src="<?php echo $post['image']; ?>" class="card-img-top blog-image" alt="<?php echo htmlspecialchars($post['title']); ?>" onclick="viewImage('<?php echo $post['image']; ?>')" style="cursor: pointer;">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="author-badge">
                                    <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($post['author']); ?>
                                </span>
                                <span class="date-badge">
                                    <i class="fas fa-calendar me-1"></i><?php echo $post['date']; ?>
                                </span>
                            </div>
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($post['title']); ?></h5>
                            <p class="card-text text-muted flex-grow-1"><?php echo htmlspecialchars(substr($post['content'], 0, 120)) . '...'; ?></p>
                            <div class="mt-auto">
                                <a href="post.php?id=<?php echo $post['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-readme me-2"></i>Read More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4">About TechBlog</h2>
                    <p class="lead mb-4">
                        We are passionate about technology and development. Our team of experts shares knowledge, 
                        insights, and practical advice to help developers stay ahead of the curve.
                    </p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                            <h4>Best Practices</h4>
                            <p>Expert insights on coding standards and development practices.</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-code fa-3x text-primary mb-3"></i>
                            <h4>Development</h4>
                            <p>Modern development techniques and best practices.</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-users fa-3x text-primary mb-3"></i>
                            <h4>Community</h4>
                            <p>Join our community of developers and tech professionals.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h2 class="display-5 fw-bold mb-4">Get In Touch</h2>
                    <p class="lead mb-4">
                        Have questions or want to contribute? We'd love to hear from you!
                    </p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-envelope fa-2x text-primary mb-3"></i>
                            <h5>Email</h5>
                            <p>contact@techblog.com</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-phone fa-2x text-primary mb-3"></i>
                            <h5>Phone</h5>
                            <p>+1 (555) 123-4567</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-map-marker-alt fa-2x text-primary mb-3"></i>
                            <h5>Location</h5>
                            <p>San Francisco, CA</p>
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
