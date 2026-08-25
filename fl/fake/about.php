<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FakeLess – About Us</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="check-style.css">
    <link rel="stylesheet" href="about-style.css">
</head>
<body>


<nav class="ck-nav">
    <a class="logo" href="index.php">
        <img src="logo.png" alt="FakeLess">
        FakeLess
    </a>
    <div class="nav-links">
        <a href="index.php">Check Image</a>
        <a href="about.php" style="border-color:rgba(192,66,138,.6);background:rgba(192,66,138,.15);">About</a>
        <a href="contact.php">Contact</a>
        <?php
        session_start();
        if (!isset($_SESSION["user"])): ?>
            <a href="login.php">Login</a>
        <?php else: ?>
            <a href="account.php">My Account</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
    </div>
</nav>

<main class="ck-main" style="padding-top:30px;">

    <div class="ck-hero" style="margin-bottom:36px;">
        <h1>About FakeLess</h1>
        <p>Built to bring trust back to digital media through advanced AI detection</p>
    </div>

    <div class="about-card">
        <div class="about-body">
            <p>
                FakeLess provides an advanced deep learning interface powered by EfficientNet-B4,
                allowing users to upload images and instantly detect manipulation or forgery with
                high accuracy.
            </p>
            <p>
                It analyzes pixel-level features and generates detailed authenticity reports
                that help users understand whether an image has been tampered with or digitally
                altered. The platform leverages a fine-tuned neural network trained on diverse
                datasets, delivering trusted results for forensic analysis and digital verification.
            </p>

            <div class="about-features">
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z" stroke="#c0428a" stroke-width="1.8"/>
                            <path d="M9 12l2 2 4-4" stroke="#c0428a" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="feat-text">
                        <strong>EfficientNet-B4 Model</strong>
                        <span>State-of-the-art deepfake detection architecture</span>
                    </div>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <rect x="3" y="3" width="18" height="18" rx="3" stroke="#7b2fff" stroke-width="1.8"/>
                            <path d="M7 12h10M12 7v10" stroke="#7b2fff" stroke-width="1.8" stroke-linecap="round"/>
                        </svg>
                    </div>
                    <div class="feat-text">
                        <strong>Pixel-Level Analysis</strong>
                        <span>Detects subtle manipulation artifacts invisible to the eye</span>
                    </div>
                </div>
                <div class="feat-item">
                    <div class="feat-icon">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 3L3 8v8l9 5 9-5V8L12 3z" stroke="#c0428a" stroke-width="1.8" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <div class="feat-text">
                        <strong>Instant Results</strong>
                        <span>Real-time authenticity score with probability breakdown</span>
                    </div>
                </div>
            </div>

            <a class="back-btn" href="index.php">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                    <path d="M19 12H5M12 5l-7 7 7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Back to detector
            </a>
        </div>
    </div>

</main>


</body>
</html>
